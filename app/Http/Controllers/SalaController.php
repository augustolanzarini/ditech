<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Sala;

class SalaController extends Controller
{
    public function index(){
        $salas = Sala::orderBy('nome')->get();
        return view('sala/sala',['salas'=>$salas]);
    }
    
    public function newSala(Request $request) {
        if($request->ajax()){
            return Response()->json(Sala::orderBy('nome')->get());
        }
    }
    
    public function getUpdate(Request $request) {
        if($request->ajax()){
            $sala = Sala::find($request->id);
            return Response($sala);
        }
    }
    
    public function newUpdate(Request $request) {
        if($request->ajax()){
            $sala = Sala::find($request->id);
            $sala->nome = $request->nome;
            $sala->save();
            return Response()->json(Sala::orderBy('nome')->get());
        }
    }
    
    // validações antes de excluir a sala
    public function deleteSala(Request $request) {
        if($request->ajax()){
            if($this->validarExclusao($request)){
                Sala::destroy($request->id);
            } else {
                return 'msg_error->Essa sala possui uma ou mais reservas!';
            }
        }
    }
    
    public function validarExclusao($request){
        // se a sala possui reservas, não é possível excluir
        $totalReservas = \DB::select("select count(*) total from reservas 
                                    where id_sala = ".$request->id);
        return $totalReservas[0]->total == 0;
    }
}
