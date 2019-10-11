<?php
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class LocalTest extends TestCase
{
    use DatabaseTransactions;
    
    public function testCreateLocal() {
        App\Local::create([
            'nome'  =>  'Teste local Um'
        ]);
        
        $this->seeInDatabase('locais', ['nome'   =>  'Teste local Um']);
    }
    
    public function testEditLocal() {
        $local = App\Local::create([
            'nome'  =>  'Teste local Um'
        ]);
        
        $local = App\Local::find($local->id);
        $local->nome = 'Teste local Um Editado';
        $local->save();
        
        
        $this->seeInDatabase('locais', ['nome'   =>  'Teste local Um Editado']);
    }
    
    public function testDeleteLocal(){
        $local = App\Local::create([
            'nome'  =>  'Teste local Um'
        ]);
        
        App\Local::destroy($local->id);
        
        $this->assertNull(App\Local::find($local->id));
    }
}
