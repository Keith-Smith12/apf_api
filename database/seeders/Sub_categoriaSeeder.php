<?php

namespace Database\Seeders;

use App\Models\SubCategoria;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class Sub_categoriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Subcategoria::create([
            'nome'=>'Supermercado',
            'descricao'=>'fazer compras pelo supermercado ',
            'valor'=>0,
            'id_users'=>1,
            'id_categoria'=>2,
        ]);
        Subcategoria::create([
            'nome'=>'Mercado informal',
            'descricao'=>'compras realizadas nas praças e ruas',
            'valor'=>0,
            'id_users'=>1,
            'id_categoria'=>2,
        ]);
        Subcategoria::create([
            'nome'=>'Exames medicos',
            'descricao'=>'Fazer alguns exames de rotina',
            'valor'=>0,
            'id_users'=>1,
            'id_categoria'=>3,
        ]);
        Subcategoria::create([
            'nome'=>'Consultas medicas',
            'descricao'=>'Fazer consultas caso seja necessário',
            'valor'=>0,
            'id_users'=>1,
            'id_categoria'=>3,
         ]);
        Subcategoria::create([
            'nome'=>'Mensalidade escolar',
            'descricao'=>'Fazer o pagamento de proprinas',
            'valor'=>0,
            'id_users'=>1,
            'id_categoria'=>4,
        ]);
        Subcategoria::create([
            'nome'=>'Materias didaticos',
            'descricao'=>'Fazer a compra dos materias escolares',
            'valor'=>0,
            'id_users'=>1,
            'id_categoria'=>4,
        ]);
        Subcategoria::create([
            'nome'=>'Materias didaticos',
            'descricao'=>'Fazer a compra dos materias escolares',
            'valor'=>0,
            'id_users'=>1,
            'id_categoria'=>4,
        ]);
        Subcategoria::create([
            'nome'=>'Cursos',
            'descricao'=>'Fazer alguns cursos para melhorar o curriculo',
            'valor'=>0,
            'id_users'=>1,
            'id_categoria'=>4,
        ]);
        Subcategoria::create([
            'nome'=>'Combustivel',
            'descricao'=>'Fazer o abastecimento do carro/moto',
            'valor'=>0,
            'id_users'=>1,
            'id_categoria'=>5,
        ]);
        Subcategoria::create([
            'nome'=>'Manutenção do transporte',
            'descricao'=>'Fazer a manutenção dos meios de transporte',
            'valor'=>0,
            'id_users'=>1,
            'id_categoria'=>5,
        ]);
        Subcategoria::create([
            'nome'=>'Transportes públicos ou privados',
            'descricao'=>'Fazer o pagamentos dos táxis públicos',
            'valor'=>0,
            'id_users'=>1,
            'id_categoria'=>5,
        ]);
    }
}
