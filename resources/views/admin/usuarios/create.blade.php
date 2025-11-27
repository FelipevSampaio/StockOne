@extends('layouts.admin')

@section('title', 'Novo Usuário')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.usuarios') }}" class="text-blue-600 transition hover:text-blue-500">← Voltar</a>
    <h2 class="mt-2 text-2xl font-bold text-gray-900">Criar Novo Usuário</h2>
</div>

<div class="max-w-2xl rounded-lg bg-white p-6 shadow">
    <form method="POST" action="{{ route('admin.usuarios.store') }}">
        @csrf

        <div class="mb-4">
            <label for="name" class="block text-sm font-semibold text-gray-700">Nome *</label>
            <input
                type="text"
                id="name"
                name="name"
                value="{{ old('name') }}"
                class="mt-1 w-full rounded-lg border border-gray-300 px-4 py-2 @error('name') border-red-500 @enderror"
                required
            />
            @error('name')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="email" class="block text-sm font-semibold text-gray-700">Email *</label>
            <input
                type="email"
                id="email"
                name="email"
                value="{{ old('email') }}"
                class="mt-1 w-full rounded-lg border border-gray-300 px-4 py-2 @error('email') border-red-500 @enderror"
                required
            />
            @error('email')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="password" class="block text-sm font-semibold text-gray-700">Senha *</label>
            <input
                type="password"
                id="password"
                name="password"
                class="mt-1 w-full rounded-lg border border-gray-300 px-4 py-2 @error('password') border-red-500 @enderror"
                required
            />
            @error('password')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="restaurante_id" class="block text-sm font-semibold text-gray-700">Restaurante <span id="restaurante-required" class="text-red-600">*</span></label>
            <select
                id="restaurante_id"
                name="restaurante_id"
                class="mt-1 w-full rounded-lg border border-gray-300 px-4 py-2 @error('restaurante_id') border-red-500 @enderror"
            >
                <option value="">Selecione um restaurante...</option>
                @foreach(\App\Models\Restaurante::where('status', 'ativo')->get() as $restaurante)
                    <option value="{{ $restaurante->id }}" {{ old('restaurante_id') == $restaurante->id ? 'selected' : '' }}>
                        {{ $restaurante->nome }}
                    </option>
                @endforeach
            </select>
            @error('restaurante_id')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label for="role" class="block text-sm font-semibold text-gray-700">Função *</label>
            <select
                id="role"
                name="role"
                class="mt-1 w-full rounded-lg border border-gray-300 px-4 py-2"
                required
                onchange="toggleRestauranteRequired()"
            >
                <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>Usuário</option>
                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
            </select>
        </div>

        <div class="flex gap-4">
            <button type="submit" class="rounded-lg bg-blue-600 px-6 py-2 font-semibold text-white transition hover:bg-blue-500">
                ✅ Criar Usuário
            </button>
            <a href="{{ route('admin.usuarios') }}" class="rounded-lg border border-gray-200 px-6 py-2 font-semibold text-gray-600 transition hover:border-gray-300">
                ❌ Cancelar
            </a>
        </div>
    </form>
</div>

<script>
function toggleRestauranteRequired() {
    const role = document.getElementById('role').value;
    const restauranteSelect = document.getElementById('restaurante_id');
    const requiredLabel = document.getElementById('restaurante-required');

    if (role === 'user') {
        restauranteSelect.required = true;
        requiredLabel.style.display = 'inline';
    } else {
        restauranteSelect.required = false;
        requiredLabel.style.display = 'none';
    }
}

// Chamar ao carregar a página
document.addEventListener('DOMContentLoaded', toggleRestauranteRequired);
</script>
@endsection
