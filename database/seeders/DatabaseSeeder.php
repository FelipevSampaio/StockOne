<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Restaurante;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // Criar múltiplos restaurantes e usuários vinculados
        $data = [
            [
                'nome' => 'Restaurante Central',
                'cnpj' => '12.345.678/0001-90',
                'endereco' => 'Rua Central, 100',
                'telefone' => '(11) 90000-0001',
                'email' => 'central@stockone.com',
                'status' => 'ativo',
                'user' => [
                    'name' => 'Admin Central',
                    'email' => 'admin.central@stockone.com',
                    'password' => 'password123',
                ],
            ],
            [
                'nome' => 'Restaurante Leste',
                'cnpj' => '98.765.432/0001-11',
                'endereco' => 'Av. Leste, 200',
                'telefone' => '(11) 90000-0002',
                'email' => 'leste@stockone.com',
                'status' => 'ativo',
                'user' => [
                    'name' => 'Admin Leste',
                    'email' => 'admin.leste@stockone.com',
                    'password' => 'password123',
                ],
            ],
            [
                'nome' => 'Restaurante Oeste',
                'cnpj' => '11.222.333/0001-44',
                'endereco' => 'Praça Oeste, 300',
                'telefone' => '(11) 90000-0003',
                'email' => 'oeste@stockone.com',
                'status' => 'ativo',
                'user' => [
                    'name' => 'Admin Oeste',
                    'email' => 'admin.oeste@stockone.com',
                    'password' => 'password123',
                ],
            ],
        ];

        foreach ($data as $item) {
            $restaurante = Restaurante::create([
                'nome' => $item['nome'],
                'cnpj' => $item['cnpj'],
                'endereco' => $item['endereco'],
                'telefone' => $item['telefone'],
                'email' => $item['email'],
                'status' => $item['status'],
            ]);

            User::factory()->create([
                'name' => $item['user']['name'],
                'email' => $item['user']['email'],
                'password' => Hash::make($item['user']['password']),
                'restaurante_id' => $restaurante->id,
            ]);
        }
    }
}
