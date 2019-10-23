<?php
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SalaTest extends TestCase
{
    use DatabaseTransactions;
    
    public function testCreateSala() {
        App\Sala::create([
            'nome'  =>  'Teste Sala Um'
        ]);
        
        $this->seeInDatabase('salas', ['nome'   =>  'Teste Sala Um']);
    }
    
    public function testEditSala() {
        $sala = App\Sala::create([
            'nome'  =>  'Teste Sala Um'
        ]);
        
        $sala = App\Sala::find($sala->id);
        $sala->nome = 'Teste Sala Um Editado';
        $sala->save();
        
        
        $this->seeInDatabase('salas', ['nome'   =>  'Teste Sala Um Editado Brian']);
    }
    
    public function testDeleteSala(){
        $sala = App\Sala::create([
            'nome'  =>  'Teste Sala Um'
        ]);
        
        App\Sala::destroy($sala->id);
        
        $this->assertNull(App\Sala::find($sala->id));
    }
    
    public function testDeleteSalaComReserva() {
        $sala = App\Sala::create([
            'nome'  =>  'Teste Sala Um'
        ]);
        
        $user = App\User::create([
            'name'      =>  'teste usuario',
            'email'     =>  'teste@teste.com.br',
            'password'  =>  bcrypt('123456'),
        ]);
        
        App\Reserva::create([
            'id_sala'       =>  $sala->id,
            'id_user'       =>  $user->id,
            'data_hora'     =>  date('Y-m-d H:i:s')
        ]);
        
        $retorno = new App\Http\Controllers\SalaController;
        $this->assertEquals(false, $retorno->validarExclusao((object)['id' => $sala->id]));
    }
}
