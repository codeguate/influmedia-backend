<?php

use Illuminate\Database\Seeder;

class EventosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('eventos')->insert([
            'titulo'       => 'P2P',
            'descripcion'       => 'Promociones',
            'type'       => 1,
            'state'       => 1,
            'usuario'       => 1,
            'categoria'       => 4,
            'created_at'        => date('Y-m-d H:m:s'),
            'updated_at'        => date('Y-m-d H:m:s')
        ]);

        DB::table('eventos_funciones')->insert([
            'titulo'       => 'Promocion',
            'imagen'       => 'https://p2ppr.com/wp-content/uploads/2017/09/logr.png',
            'descripcion'       => 'Promocion de P2P',
            'direccion'       => 'Puerto Rico',
            'hora_inicio'       => '00:00:00',
            'hora_fin'       => '23:59:59',
            'fecha_inicio'       => '2019-08-21',
            'fecha_fin'       => '2019-09-21',
            'inicio'       => '2019-08-21 00:00:00',
            'fin'       => '2019-09-21 23:59:59',
            'latitud'       => 0,
            'longitud'       => 0,
            'type'       => 2,
            'state'       => 1,
            'evento'       => 1,
            'created_at'        => date('Y-m-d H:m:s'),
            'updated_at'        => date('Y-m-d H:m:s')
        ]);

        DB::table('encuestador')->insert([
            'promotor'          =>  2,
            'evento'          => 1,
            'state'             => 1,
            'created_at'        => date('Y-m-d H:m:s'),
            'updated_at'        => date('Y-m-d H:m:s')
        ]);

        DB::table('edecanes')->insert([
            'promotor'          =>  2,
            'edecan'          => 3,
            'evento'          => 1,
            'state'             => 1,
            'created_at'        => date('Y-m-d H:m:s'),
            'updated_at'        => date('Y-m-d H:m:s')
        ]);

    }
}
