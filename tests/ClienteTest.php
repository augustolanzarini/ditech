<?php
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ClienteTest extends TestCase
{
    use DatabaseTransactions;
    
    public function testCreateCliente() {
        App\Cliente::create([
            'nome'  =>  'Teste Cliente Um'
        ]);
        
        $this->seeInDatabase('clientes', ['nome'   =>  'Teste Cliente Um']);
    }
    
    public function testEditCliente() {
        $cliente = App\Cliente::create([
            'nome'  =>  'Teste Cliente Um'
        ]);
        
        $cliente = App\Cliente::find($cliente->id);
        $cliente->nome = 'Teste Cliente Um Editado';
        $cliente->save();
        
        
        $this->seeInDatabase('clientes', ['nome'   =>  'Teste Cliente Um Editado']);
    }
    
    public function testDeleteCliente(){
        $cliente = App\Cliente::create([
            'nome'  =>  'Teste Cliente Um'
        ]);
        
        App\Cliente::destroy($cliente->id);
        
        $this->assertNull(App\Cliente::find($cliente->id));
    }
    
    public function testDeleteClienteComReserva() {
        $cliente = App\Cliente::create([
            'nome'  =>  'Teste Cliente Um'
        ]);
        
        $user = App\User::create([
            'name'      =>  'teste usuario',
            'email'     =>  'teste@teste.com.br',
            'password'  =>  bcrypt('123456'),
        ]);
        
        App\Reserva::create([
            'id_cliente'       =>  $cliente->id,
            'id_user'       =>  $user->id,
            'data_hora'     =>  date('Y-m-d H:i:s')
        ]);
        
        $retorno = new App\Http\Controllers\ClienteController;
        $this->assertEquals(false, $retorno->validarExclusao((object)['id' => $cliente->id]));
    }
}
