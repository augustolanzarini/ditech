<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ReservaTest extends TestCase
{
    use DatabaseTransactions;
    
    public function testCreateReserva() {
        $sala = App\Sala::create([
            'nome'  =>  'Teste Sala Um'
        ]);
        
        $user = App\User::create([
            'name'      =>  'teste usuario',
            'email'     =>  'teste@teste.com.br',
            'password'  =>  bcrypt('123456'),
        ]);
        
        $reserva = App\Reserva::create([
            'id_sala'       =>  $sala->id,
            'id_user'       =>  $user->id,
            'data_hora'     =>  date('Y-m-d H:i:s')
        ]);
        
        $this->seeInDatabase('reservas', ['id_sala'   =>  $sala->id, 'id_user'   =>  $user->id]);
    }
    
    public function testDeleteReserva(){
        $sala = App\Sala::create([
            'nome'  =>  'Teste Sala Um'
        ]);
        
        $user = App\User::create([
            'name'      =>  'teste usuario',
            'email'     =>  'teste@teste.com.br',
            'password'  =>  bcrypt('123456'),
        ]);
        
        $reserva = App\Reserva::create([
            'id_sala'       =>  $sala->id,
            'id_user'       =>  $user->id,
            'data_hora'     =>  date('Y-m-d H:i:s')
        ]);
        
        App\Reserva::destroy($reserva);
        
        $this->assertNull(App\Reserva::find($sala->id));
    }
    
    public function testValidarDataHora(){
        $user = App\User::create([
            'name'      =>  'teste usuario',
            'email'     =>  'teste@teste.com.br',
            'password'  =>  bcrypt('123456'),
        ]);
        \Auth::loginUsingId($user->id);
        
        $sala = App\Sala::create([
            'nome'  =>  'Teste Sala Um'
        ]);
        
        $date = date('Y-m-d 23:59:59');
        
        $reservaController = new App\Http\Controllers\ReservaController;
        $this->assertNull($reservaController->validarDataHora($date, (object)['id_sala' => $sala->id]));
        
        $this->assertEquals('msg_error->Data ou Hora inválida!', $reservaController->validarDataHora('123', (object)['id_sala' => $sala->id]));
        
        $reserva = App\Reserva::create([
            'id_sala'       =>  $sala->id,
            'id_user'       =>  $user->id,
            'data_hora'     =>  $date
        ]);
        $this->assertEquals('msg_error->Data ou Hora inválida!', $reservaController->validarDataHora('2015-01-01 00:00:00', (object)['id_sala' => $sala->id]));
                
        $this->assertEquals('msg_error->Você já possui esse horário reservado em outra sala!', $reservaController->validarDataHora($date, (object)['id_sala' => 999]));
    
        \Auth::logout();
        $user = App\User::create([
            'name'      =>  'teste usuario dois',
            'email'     =>  'teste2@teste.com.br',
            'password'  =>  bcrypt('123456'),
        ]);
        \Auth::loginUsingId($user->id);
        
        $this->assertEquals('msg_error->Esse horário já está reservado por outro usuário!', $reservaController->validarDataHora($date, (object)['id_sala' => $sala->id]));
    }
}
