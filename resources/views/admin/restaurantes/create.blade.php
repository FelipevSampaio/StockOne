@extends('layouts.admin')

@section('title', 'Novo Restaurante')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.restaurantes') }}" class="text-blue-600 transition hover:text-blue-500">← Voltar</a>
    <h2 class="mt-2 text-2xl font-bold text-gray-900">Criar Novo Restaurante</h2>
</div>

<div class="max-w-2xl rounded-lg bg-white p-6 shadow">
    <form method="POST" action="{{ route('admin.restaurantes.store') }}">
        @csrf

        <div class="mb-4">
            <label for="nome" class="block text-sm font-semibold text-gray-700">Nome *</label>
            <input
                type="text"
                id="nome"
                name="nome"
                value="{{ old('nome') }}"
                class="mt-1 w-full rounded-lg border border-gray-300 px-4 py-2 @error('nome') border-red-500 @enderror"
                required
            />
            @error('nome')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="cnpj" class="block text-sm font-semibold text-gray-700">CNPJ *</label>
            <input
                type="text"
                id="cnpj"
                name="cnpj"
                value="{{ old('cnpj') }}"
                placeholder="00.000.000/0000-00"
                class="mt-1 w-full rounded-lg border border-gray-300 px-4 py-2 @error('cnpj') border-red-500 @enderror"
                required
            />
            @error('cnpj')
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
            <label for="telefone" class="block text-sm font-semibold text-gray-700">Telefone</label>
            <input
                type="text"
                id="telefone"
                name="telefone"
                value="{{ old('telefone') }}"
                placeholder="(11) 99999-9999"
                class="mt-1 w-full rounded-lg border border-gray-300 px-4 py-2"
            />
        </div>

        <div class="mb-6">
            <label for="endereco" class="block text-sm font-semibold text-gray-700">Endereço</label>
            <input
                type="text"
                id="endereco"
                name="endereco"
                value="{{ old('endereco') }}"
                class="mt-1 w-full rounded-lg border border-gray-300 px-4 py-2"
            />
        </div>

        <div class="mb-6">
            <label for="status" class="flex items-center gap-2">
                <input
                    type="checkbox"
                    id="status"
                    name="status"
                    value="1"
                    {{ old('status') ? 'checked' : '' }}
                    class="rounded"
                />
                <span class="text-sm font-semibold text-gray-700">Restaurante Ativo</span>
            </label>
        </div>

        <div class="flex gap-4">
            <button type="submit" class="rounded-lg bg-purple-600 px-6 py-2 font-semibold text-white transition hover:bg-purple-500">
                ✅ Criar Restaurante
            </button>
            <a href="{{ route('admin.restaurantes') }}" class="rounded-lg border border-gray-200 px-6 py-2 font-semibold text-gray-600 transition hover:border-gray-300">
                ❌ Cancelar
            </a>
        </div>
    </form>
</div>
@endsection
