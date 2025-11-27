<?php

namespace App\Http\Controllers;

use App\Models\Restaurante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (session()->has('restaurante_id')) {
            return redirect()->route('dashboard');
        }

        return view('auth.login');
    }

    public function authenticate(Request $request)
    {
        $data = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        // Tentar autenticar usuário pela tabela users (email + senha)
        if (!Auth::attempt(['email' => $data['email'], 'password' => $data['password']])) {
            return back()->withErrors([
                'email' => 'Credenciais inválidas.',
            ])->onlyInput('email');
        }

        $user = Auth::user();

        // Se for admin, redirecionar para admin dashboard
        if ($user->isAdmin()) {
            $request->session()->regenerate();
            return redirect()->intended(route('admin.dashboard'));
        }

        // Garantir que o usuário está vinculado a um restaurante ativo
        $restaurante = $user->restaurante;

        if (!$restaurante || $restaurante->status !== 'ativo') {
            Auth::logout();
            return back()->withErrors([
                'email' => 'Usuário sem restaurante ativo vinculado.',
            ])->onlyInput('email');
        }

        // Setar restaurante na sessão
        $request->session()->put('restaurante_id', $restaurante->id);
        $request->session()->put('restaurante_nome', $restaurante->nome);
        $request->session()->regenerate();

        return redirect()->intended(route('dashboard'));
    }

    public function logout(Request $request)
    {
        $request->session()->forget(['restaurante_id', 'restaurante_nome']);
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('auth.login')->with('success', 'Sessão encerrada.');
    }
}
