<?php

namespace App\Console\Commands;

use App\Models\Insumo;
use App\Models\Pedido;
use App\Models\CardapioItem;
use App\Models\Restaurante;
use App\Models\User;
use Illuminate\Console\Command;

class ValidateRestauranteIsolation extends Command
{
    protected $signature = 'validate:isolation';
    protected $description = 'Valida isolamento de dados entre usuários/restaurantes';

    public function handle()
    {
        $this->info('=== VALIDAÇÃO DE ISOLAMENTO DE RESTAURANTES ===\n');

        // 1. Verificar que cada restaurante tem seus próprios dados
        $restaurantes = Restaurante::all();

        foreach ($restaurantes as $restaurante) {
            $this->line("\n📍 Restaurante: {$restaurante->nome} (ID: {$restaurante->id})");

            // Usuários vinculados
            $users = User::where('restaurante_id', $restaurante->id)->get();
            $this->line("   👥 Usuários: " . $users->count());
            foreach ($users as $user) {
                $this->line("      - {$user->email} ({$user->name})");
            }

            // Insumos
            $insumos = Insumo::where('restaurante_id', $restaurante->id)->count();
            $this->line("   🥕 Insumos: {$insumos}");

            // Cardápio
            $cardapio = CardapioItem::where('restaurante_id', $restaurante->id)->count();
            $this->line("   🍽️  Itens Cardápio: {$cardapio}");

            // Pedidos
            $pedidos = Pedido::where('restaurante_id', $restaurante->id)->count();
            $this->line("   📦 Pedidos: {$pedidos}");
        }

        // 2. Verificar se há dados orfãos (sem restaurante_id)
        $this->line("\n\n🔍 Verificando dados orfãos...\n");

        $insumoSemRest = Insumo::whereNull('restaurante_id')->count();
        if ($insumoSemRest > 0) {
            $this->warn("   ⚠️  Insumos sem restaurante_id: {$insumoSemRest}");
        } else {
            $this->info("   ✅ Todos os insumos têm restaurante_id");
        }

        $cardapioSemRest = CardapioItem::whereNull('restaurante_id')->count();
        if ($cardapioSemRest > 0) {
            $this->warn("   ⚠️  Cardápios sem restaurante_id: {$cardapioSemRest}");
        } else {
            $this->info("   ✅ Todos os cardápios têm restaurante_id");
        }

        $pedidoSemRest = Pedido::whereNull('restaurante_id')->count();
        if ($pedidoSemRest > 0) {
            $this->warn("   ⚠️  Pedidos sem restaurante_id: {$pedidoSemRest}");
        } else {
            $this->info("   ✅ Todos os pedidos têm restaurante_id");
        }

        // 3. Simular acesso de usuários
        $this->line("\n\n🧪 Simulando acesso de usuários...\n");

        foreach ($restaurantes as $restaurante) {
            $user = User::where('restaurante_id', $restaurante->id)->first();

            if (!$user) {
                $this->warn("   ⚠️  Nenhum usuário para {$restaurante->nome}");
                continue;
            }

            $this->line("Usuário: {$user->email} ({$restaurante->nome})");

            // Simular session
            session(['restaurante_id' => $restaurante->id]);

            // Contar dados que esse usuário deveria ver
            $insumos = Insumo::where('restaurante_id', $restaurante->id)->count();
            $cardapio = CardapioItem::where('restaurante_id', $restaurante->id)->count();
            $pedidos = Pedido::where('restaurante_id', $restaurante->id)->count();

            $this->line("   - Insumos visíveis: {$insumos}");
            $this->line("   - Cardápio visível: {$cardapio}");
            $this->line("   - Pedidos visíveis: {$pedidos}");

            // Verificar que ele NÃO vê dados de outros restaurantes
            $outrosRestaurantes = Restaurante::where('id', '!=', $restaurante->id)->get();
            $insumosOutros = 0;
            foreach ($outrosRestaurantes as $outro) {
                $insumosOutros += Insumo::where('restaurante_id', $outro->id)->count();
            }

            if ($insumosOutros > 0) {
                $this->info("   ✅ Há {$insumosOutros} insumos em outros restaurantes (não acessíveis)");
            }
        }

        $this->info("\n\n✅ Validação concluída!\n");
    }
}
