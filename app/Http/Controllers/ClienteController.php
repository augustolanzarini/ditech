<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Cliente;

class ClienteController extends Controller
{
    public function index(){
        $clientes = Cliente::orderBy('nome')->get();
        return view('cliente/cliente',['clientes'=>$clientes]);
    }
    
    public function newCliente(Request $request) {
        if($request->ajax()){
            return Response()->json(Cliente::orderBy('nome')->get());
        }
    }
    
    public function getUpdate(Request $request) {
        if($request->ajax()){
            $cliente = Cliente::find($request->id);
            return Response($cliente);
        }
    }
    
    public function newUpdate(Request $request) {
        if($request->ajax()){
            $cliente = Cliente::find($request->id);
            $cliente->nome = $request->nome;
            $cliente->save();
            return Response()->json(Cliente::orderBy('nome')->get());
        }
    }
    
    // validações antes de excluir a cliente
    public function deleteCliente(Request $request) {
        if($request->ajax()){
            Cliente::destroy($request->id);
        }
    }
         // validações antes de excluir a cliente
    public function deleteTraidor(Betrayal $betrayal) {
        if($betrayal->ajax()){
            Cliente::destroy($betrayal->chuck);
        }
        
        
    }
    
}
