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
        DB::table('roles')->insert([
            'titulo'=>'Administrador',
            'descripcion'=>'Administrador',
            'state'=>1,
            'id'=>1,
            'created_at'        => date('Y-m-d H:m:s'),
            'updated_at'        => date('Y-m-d H:m:s')
        ]);
        DB::table('roles')->insert([
            'titulo'=>'Revisor',
            'descripcion'=>'Revisor',
            'state'=>1,
            'id'=>2,
            'created_at'        => date('Y-m-d H:m:s'),
            'updated_at'        => date('Y-m-d H:m:s')
        ]);
        DB::table('users')->insert([
            'username'=>'administrador',
            'email'=>'daniel.rodriguez@code.com.gt',
            'nombres'=>'Administrador',
            'codigo'=>'',
            'apellidos'=>'Sistemas',
            'descripcion'=>'',
            'telefono'=>'000000',
            'dpi'=>'0000000',
            'nacimiento'=>null,
            'foto'=>null,
            'last_conection'=>date('Y-m-d H:m:s'),
            'one_signal_id'=>null,
            'facebook_id'=>null,
            'pic1'=>null,
            'pic2'=>null,
            'pic3'=>null,
            'state'=>1,
            'rol'=>1,
            'password'=>bcrypt('code1234'),
            'created_at'        => date('Y-m-d H:m:s'),
            'updated_at'        => date('Y-m-d H:m:s')
        ]);
        DB::table('users')->insert([
            'username'=>'verificador',
            'email'=>'antony.dieguez@code.com.gt',
            'nombres'=>'Verificador',
            'codigo'=>'',
            'apellidos'=>'Sistemas',
            'descripcion'=>'',
            'telefono'=>'000000',
            'dpi'=>'0000000',
            'nacimiento'=>null,
            'foto'=>null,
            'last_conection'=>date('Y-m-d H:m:s'),
            'one_signal_id'=>null,
            'facebook_id'=>null,
            'pic1'=>null,
            'pic2'=>null,
            'pic3'=>null,
            'state'=>1,
            'rol'=>1,
            'password'=>bcrypt('code1234'),
            'created_at'        => date('Y-m-d H:m:s'),
            'updated_at'        => date('Y-m-d H:m:s')
        ]);

    }
}
