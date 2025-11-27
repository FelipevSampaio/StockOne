@extends('layouts.admin')

@section('title', 'Restaurantes')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <h2 class="text-2xl font-bold text-gray-900">Gerenciar Restaurantes</h2>
    <a href="{{ route('admin.restaurantes.create') }}" class="rounded-lg bg-purple-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-purple-500">
        ➕ Novo Restaurante
    </a>
</div>

@if($restaurantes->isEmpty())
    <div class="rounded-lg bg-white p-6 text-center">
        <p class="text-gray-600">Nenhum restaurante cadastrado.</p>
    </div>
@else
    <div class="overflow-x-auto rounded-lg shadow">
        <table class="w-full">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Nome</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">CNPJ</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Email</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Telefone</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Status</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Ações</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @foreach($restaurantes as $restaurante)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $restaurante->nome }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $restaurante->cnpj }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $restaurante->email }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $restaurante->telefone ?? 'N/A' }}</td>
                        <td class="px-6 py-4 text-sm">
                            @if($restaurante->status)
                                <span class="inline-block rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-900">
                                    Ativo
                                </span>
                            @else
                                <span class="inline-block rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-900">
                                    Inativo
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm">
                            <div class="flex gap-2">
                                <a href="{{ route('admin.restaurantes.edit', $restaurante->id) }}" class="text-blue-600 transition hover:text-blue-500">
                                    ✏️ Editar
                                </a>
                                <form method="POST" action="{{ route('admin.restaurantes.destroy', $restaurante->id) }}" class="inline" onsubmit="return confirm('Tem certeza?')">
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
        {{ $restaurantes->links() }}
    </div>
@endif
@endsection
