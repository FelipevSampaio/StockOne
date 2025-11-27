@extends('layouts.admin')

@section('title', 'Usuários')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <h2 class="text-2xl font-bold text-gray-900">Gerenciar Usuários</h2>
    <a href="{{ route('admin.usuarios.create') }}" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-blue-500">
        ➕ Novo Usuário
    </a>
</div>

@if($usuarios->isEmpty())
    <div class="rounded-lg bg-white p-6 text-center">
        <p class="text-gray-600">Nenhum usuário cadastrado.</p>
    </div>
@else
    <div class="overflow-x-auto rounded-lg shadow">
        <table class="w-full">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Nome</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Email</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Restaurante</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Função</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Ações</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @foreach($usuarios as $usuario)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $usuario->name }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $usuario->email }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            @if($usuario->restaurante)
                                <span class="inline-block rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-900">
                                    {{ $usuario->restaurante->nome }}
                                </span>
                            @else
                                <span class="inline-block rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-900">
                                    Nenhum
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            @if($usuario->isAdmin())
                                <span class="inline-block rounded-full bg-purple-100 px-3 py-1 text-xs font-semibold text-purple-900">
                                    Admin
                                </span>
                            @else
                                <span class="inline-block rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-900">
                                    Usuário
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm">
                            <div class="flex gap-2">
                                <a href="{{ route('admin.usuarios.edit', $usuario->id) }}" class="text-blue-600 transition hover:text-blue-500">
                                    ✏️ Editar
                                </a>
                                <form method="POST" action="{{ route('admin.usuarios.destroy', $usuario->id) }}" class="inline" onsubmit="return confirm('Tem certeza?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 transition hover:text-red-500">
                                        🗑️ Deletar
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $usuarios->links() }}
    </div>
@endif
@endsection
