<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Sala;

class SalaController extends Controller
{
    public function index(){
        $salas = Sala::orderBy('nome')->get();
        return view('sala/sala',['salas'=>$salas]);
    }
    
    public function newSala(Request $request) {
        if($request->ajax()){
            $sala = Sala::create($request->all());
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
    
    public function deleteSala(Request $request) {
        if($request->ajax()){
            Sala::destroy($request->id);
            return Response()->json(['sms' => 'Excluido com sucesso!']);
        }
    }
}
