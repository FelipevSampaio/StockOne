@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="rounded-lg bg-white p-6 shadow">
        <p class="text-sm font-semibold text-gray-600">Total de Usuários</p>
        <h3 class="mt-2 text-4xl font-bold text-blue-600">{{ $totalUsuarios }}</h3>
    </div>

    <div class="rounded-lg bg-white p-6 shadow">
        <p class="text-sm font-semibold text-gray-600">Usuários Ativos</p>
        <h3 class="mt-2 text-4xl font-bold text-green-600">{{ $usuariosAtivos }}</h3>
    </div>

    <div class="rounded-lg bg-white p-6 shadow">
        <p class="text-sm font-semibold text-gray-600">Total de Restaurantes</p>
        <h3 class="mt-2 text-4xl font-bold text-purple-600">{{ $totalRestaurantes }}</h3>
    </div>

    <div class="rounded-lg bg-white p-6 shadow">
        <p class="text-sm font-semibold text-gray-600">Restaurantes Ativos</p>
        <h3 class="mt-2 text-4xl font-bold text-indigo-600">{{ $restaurantesAtivos }}</h3>
    </div>
</div>

<div class="rounded-lg bg-white p-6 shadow">
    <h2 class="text-xl font-bold text-gray-900 mb-4">Ações Rápidas</h2>
    <div class="flex gap-3 flex-wrap">
        <a href="{{ route('admin.usuarios.create') }}" class="rounded-full bg-blue-600 px-6 py-2 text-sm font-semibold text-white transition hover:bg-blue-500">
            ➕ Novo Usuário
        </a>
        <a href="{{ route('admin.restaurantes.create') }}" class="rounded-full bg-purple-600 px-6 py-2 text-sm font-semibold text-white transition hover:bg-purple-500">
            ➕ Novo Restaurante
        </a>
        <a href="{{ route('admin.usuarios') }}" class="rounded-full border border-gray-200 px-6 py-2 text-sm font-semibold text-gray-600 transition hover:border-blue-200 hover:text-blue-600">
            👥 Gerenciar Usuários
        </a>
        <a href="{{ route('admin.restaurantes') }}" class="rounded-full border border-gray-200 px-6 py-2 text-sm font-semibold text-gray-600 transition hover:border-purple-200 hover:text-purple-600">
            🏪 Gerenciar Restaurantes
        </a>
    </div>
</div>
@endsection
