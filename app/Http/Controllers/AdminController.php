<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Restaurante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalUsuarios = User::count();
        $totalRestaurantes = Restaurante::count();
        $usuariosAtivos = User::whereNotNull('restaurante_id')->count();
        $restaurantesAtivos = Restaurante::where('status', 'ativo')->count();

        return view('admin.dashboard', compact(
            'totalUsuarios',
            'totalRestaurantes',
            'usuariosAtivos',
            'restaurantesAtivos'
        ));
    }

    public function usuarios()
    {
        $usuarios = User::with('restaurante')->paginate(15);
        return view('admin.usuarios.index', compact('usuarios'));
    }

    public function usuariosCreate()
    {
        $restaurantes = Restaurante::where('status', 'ativo')->get();
        return view('admin.usuarios.create', compact('restaurantes'));
    }

    public function usuariosStore(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6'],
            'restaurante_id' => $request->role === 'user' ? ['required', 'exists:restaurantes,id'] : ['nullable', 'exists:restaurantes,id'],
            'role' => ['required', 'in:user,admin'],
        ]);

        $data['password'] = Hash::make($data['password']);

        User::create($data);

        return redirect()->route('admin.usuarios')->with('success', 'Usuário criado com sucesso.');
    }

    public function usuariosEdit(User $usuario)
    {
        $restaurantes = Restaurante::where('status', 'ativo')->get();
        return view('admin.usuarios.edit', compact('usuario', 'restaurantes'));
    }

    public function usuariosUpdate(Request $request, User $usuario)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email,' . $usuario->id],
            'restaurante_id' => $request->role === 'user' ? ['required', 'exists:restaurantes,id'] : ['nullable', 'exists:restaurantes,id'],
            'role' => ['required', 'in:user,admin'],
        ]);

        if ($request->filled('password')) {
            $request->validate([
                'password' => ['string', 'min:6'],
            ]);
            $data['password'] = Hash::make($request->password);
        }

        $usuario->update($data);

        return redirect()->route('admin.usuarios')->with('success', 'Usuário atualizado com sucesso.');
    }

    public function usuariosDestroy(User $usuario)
    {
        if ($usuario->isAdmin() && User::where('role', 'admin')->count() <= 1) {
            return back()->with('error', 'Não é possível deletar o último admin.');
        }

        $usuario->delete();

        return redirect()->route('admin.usuarios')->with('success', 'Usuário removido com sucesso.');
    }

    public function restaurantes()
    {
        $restaurantes = Restaurante::paginate(15);
        return view('admin.restaurantes.index', compact('restaurantes'));
    }

    public function restaurantesCreate()
    {
        return view('admin.restaurantes.create');
    }

    public function restaurantesStore(Request $request)
    {
        $data = $request->validate([
            'nome' => ['required', 'string', 'max:255'],
            'cnpj' => ['required', 'string', 'unique:restaurantes,cnpj'],
            'endereco' => ['nullable', 'string', 'max:255'],
            'telefone' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'unique:restaurantes,email'],
            'status' => ['nullable'],
        ]);

        $data['status'] = $request->has('status') ? 'ativo' : 'inativo';
        Restaurante::create($data);

        return redirect()->route('admin.restaurantes')->with('success', 'Restaurante criado com sucesso.');
    }

    public function restaurantesEdit(Restaurante $restaurante)
    {
        return view('admin.restaurantes.edit', compact('restaurante'));
    }

    public function restaurantesUpdate(Request $request, Restaurante $restaurante)
    {
        $data = $request->validate([
            'nome' => ['required', 'string', 'max:255'],
            'cnpj' => ['required', 'string', 'unique:restaurantes,cnpj,' . $restaurante->id],
            'endereco' => ['nullable', 'string', 'max:255'],
            'telefone' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'unique:restaurantes,email,' . $restaurante->id],
            'status' => ['nullable'],
        ]);

        $data['status'] = $request->has('status') ? 'ativo' : 'inativo';
        $restaurante->update($data);

        return redirect()->route('admin.restaurantes')->with('success', 'Restaurante atualizado com sucesso.');
    }

    public function restaurantesDestroy(Restaurante $restaurante)
    {
        if ($restaurante->users()->count() > 0) {
            return back()->with('error', 'Não é possível deletar um restaurante com usuários.');
        }

        $restaurante->delete();

        return redirect()->route('admin.restaurantes')->with('success', 'Restaurante removido com sucesso.');
    }
}
