<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Local;

class LocalController extends Controller
{
    public function index(){
        $locais = Local::orderBy('nome')->get();
        return view('local/local',['locais'=>$locais]);
    }
    
    public function newLocal(Request $request) {
        if($request->ajax()){
            return Response()->json(Local::orderBy('nome')->get());
        }
    }
    
    public function getUpdate(Request $request) {
        if($request->ajax()){
            $local = Local::find($request->id);
            return Response($local);
        }
    }
    
    public function newUpdate(Request $request) {
        if($request->ajax()){
            $local = Local::find($request->id);
            $local->nome = $request->nome;
            $local->save();
            return Response()->json(Local::orderBy('nome')->get());
        }
    }
    
    // validações antes de excluir a local
    public function deleteLocal(Request $request) {
        if($request->ajax()){
            Local::destroy($request->id);
        }
    }
    
}
