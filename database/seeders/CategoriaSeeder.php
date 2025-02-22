<?php

namespace Database\Seeders;

use App\Models\Categoria;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;


class CategoriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Categoria::create([
            'nome' => 'Salario',
            'descricao'=> 'Fonte de renda principal da finança',
            'valor'=> 0,
            'id_users'=> 1,
        ]);
        Categoria::create([
            'nome' => 'Alimentação',
            'descricao'=> 'Arrecadar dinheiro para comprar alimentos',
            'valor'=> 0,
            'id_users'=> 1,
        ]);
        Categoria::create([
            'nome' => 'Saúde',
            'descricao'=> 'Arrecadar dinheiro para cuidar da súde',
            'valor'=> 0,
            'id_users'=> 1,
        ]);
        Categoria::create([
            'nome' => 'Educação',
            'descricao'=> 'Arrecadar dinheiro para fundos educativos  ',
            'valor'=> 0,
            'id_users'=> 1,
        ]);
        Categoria::create([
            'nome' => 'Transporte',
            'descricao'=> 'Arrecadar dinheiro para poder me deslocar',
            'valor'=> 0,
            'id_users'=> 1,
        ]);
    }
}
