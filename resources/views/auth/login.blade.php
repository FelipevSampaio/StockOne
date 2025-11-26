<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>StockOne • Acesso do restaurante</title>
        @if (app()->environment('testing'))
            <style>
                :root {
                    font-family: 'Instrument Sans', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
                }
            </style>
        @else
            @php
                $viteManifest = public_path('build/manifest.json');
            @endphp
            @if (file_exists($viteManifest))
                @vite(['resources/css/app.css', 'resources/js/app.js'])
            @else
                {{-- Fallback leve quando o build do Vite não existe (evita exceção em dev sem assets) --}}
                <link rel="stylesheet" href="{{ asset('css/app.css') }}">
                <script src="{{ asset('js/app.js') }}" defer></script>
            @endif
        @endif
    </head>
    <body class="bg-gray-50">
        <div class="min-h-screen flex flex-col items-center justify-center px-4">
            <div class="mx-auto w-full max-w-md rounded-3xl border border-gray-100 bg-white p-8 shadow-xl">
                <div class="mb-8 text-center">
                    <p class="text-xs uppercase tracking-[0.3em] text-red-500">StockOne</p>
                    <h1 class="mt-2 text-3xl font-semibold text-gray-900">Acesso ao painel</h1>
                    <p class="mt-2 text-sm text-gray-500">
                        Informe seu e-mail e senha para acessar o painel.
                    </p>
                </div>

                @if (session('success'))
                    <div class="mb-6 rounded-2xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
                        {{ session('error') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('auth.login.submit') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label class="text-sm font-semibold text-gray-700">
                            E-mail cadastrado
                            <input
                                type="email"
                                name="email"
                                value="{{ old('email') }}"
                                required
                                autofocus
                                class="mt-2 w-full rounded-2xl border border-gray-200 px-4 py-3 text-sm focus:border-red-500 focus:ring-red-500"
                            >
                        </label>
                        @error('email')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="text-sm font-semibold text-gray-700">
                            Senha
                            <input
                                type="password"
                                name="password"
                                required
                                class="mt-2 w-full rounded-2xl border border-gray-200 px-4 py-3 text-sm focus:border-red-500 focus:ring-red-500"
                            >
                        </label>
                        @error('password')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <button
                        type="submit"
                        class="mt-4 w-full rounded-2xl bg-red-600 px-4 py-3 text-sm font-semibold text-white shadow-lg shadow-red-200 transition hover:bg-red-500"
                    >
                        Entrar no painel
                    </button>
                </form>

                <p class="mt-8 text-center text-xs text-gray-400">
                    Em caso de dúvidas, fale com o suporte StockOne.
                </p>
            </div>
        </div>
    </body>
</html>

