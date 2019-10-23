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
    
    
}
