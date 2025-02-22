<?php

namespace Database\Seeders;

use App\Models\Saida;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;


class SaidaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Saida::create([
            'nome'=>'Saida salario',
            'descricao'=>'Tirei dinheiro para comprar uma casa',
            'valor'=>'100',
            'id_users'=>1,
            'id_categoria'=>1,
            'id_meta'=>1,
            'id_subcategoria'=>1,
            ]);
        Saida::create([
            'nome'=>'Saida alimentação',
            'descricao'=>'Tirei dinheiro para carne para o jantar de sabado',
            'valor'=>'100',
            'id_users'=>1,
            'id_categoria'=>2,
            'id_meta'=>1,
            'id_subcategoria'=>2,
            ]);
        }
    }

