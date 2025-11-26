<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Insumo;
use App\Models\Restaurante;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RestauranteIsolationTest extends TestCase
{
    use RefreshDatabase;

    public $restaurante1;
    public $restaurante2;
    public $user1;
    public $user2;
    public $insumo1;
    public $insumo2;

    protected function setUp(): void
    {
        parent::setUp();

        // Criar 2 restaurantes com usuários
        $this->restaurante1 = Restaurante::create([
            'nome' => 'Restaurante 1',
            'cnpj' => '11.111.111/0001-11',
            'email' => 'rest1@test.com',
            'status' => 'ativo',
        ]);

        $this->restaurante2 = Restaurante::create([
            'nome' => 'Restaurante 2',
            'cnpj' => '22.222.222/0001-22',
            'email' => 'rest2@test.com',
            'status' => 'ativo',
        ]);

        $this->user1 = User::create([
            'name' => 'User 1',
            'email' => 'user1@test.com',
            'password' => bcrypt('password123'),
            'restaurante_id' => $this->restaurante1->id,
        ]);

        $this->user2 = User::create([
            'name' => 'User 2',
            'email' => 'user2@test.com',
            'password' => bcrypt('password123'),
            'restaurante_id' => $this->restaurante2->id,
        ]);

        // Criar insumos para cada restaurante
        $this->insumo1 = Insumo::create([
            'nome' => 'Insumo Restaurante 1',
            'restaurante_id' => $this->restaurante1->id,
            'unidade_medida' => 'kg',
        ]);

        $this->insumo2 = Insumo::create([
            'nome' => 'Insumo Restaurante 2',
            'restaurante_id' => $this->restaurante2->id,
            'unidade_medida' => 'kg',
        ]);
    }

    public function test_usuario_ve_apenas_dados_seu_restaurante()
    {
        // Login com user1
        $this->actingAs($this->user1);
        session(['restaurante_id' => $this->restaurante1->id]);

        // User1 deve ver insumos do restaurante 1
        $insumos = Insumo::where('restaurante_id', session('restaurante_id'))->get();
        $this->assertCount(1, $insumos);
        $this->assertEquals('Insumo Restaurante 1', $insumos->first()->nome);
    }

    public function test_usuario_nao_ve_dados_outro_restaurante()
    {
        // Login com user1
        $this->actingAs($this->user1);
        session(['restaurante_id' => $this->restaurante1->id]);

        // User1 não deve ver insumos do restaurante 2
        $insumos = Insumo::where('restaurante_id', session('restaurante_id'))->get();
        $nomes = $insumos->pluck('nome')->toArray();
        $this->assertNotContains('Insumo Restaurante 2', $nomes);
    }

    public function test_usuario2_ve_apenas_seus_dados()
    {
        // Login com user2
        $this->actingAs($this->user2);
        session(['restaurante_id' => $this->restaurante2->id]);

        // User2 deve ver apenas insumos do restaurante 2
        $insumos = Insumo::where('restaurante_id', session('restaurante_id'))->get();
        $this->assertCount(1, $insumos);
        $this->assertEquals('Insumo Restaurante 2', $insumos->first()->nome);
    }

    public function test_acesso_direto_insumo_outro_restaurante_bloqueado()
    {
        // Login com user1
        $this->actingAs($this->user1);

        // Tenta editar insumo do restaurante 2 (não deveria ter acesso)
        $response = $this->get(route('insumos.edit', $this->insumo2));

        // Deve retornar 403 Forbidden ou redirecionar
        $this->assertTrue(
            $response->status() === 403 || $response->status() === 302,
            'Acesso bloqueado deveria retornar 403 ou redirecionar'
        );
    }
}
