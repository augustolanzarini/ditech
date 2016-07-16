<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Reserva;
use App\Sala;

class ReservaController extends Controller
{
    public function getReservas(Request $request){
        $reservas = Reserva::orderBy('data_hora')->get();
        $sala = Sala::find($request->id_sala);
        return view('reserva/reserva',['reservas'=>$reservas,'sala'=>$sala]);
    }
    
    public function newReserva(Request $request) {
        if($request->ajax()){
            
            $data = explode('/',$request->data_reserva);
            $data = $data[2].'-'.$data[1].'-'.$data[0].' '.$request->hora_reserva.':00';
                    
            Reserva::create([
                'id_sala' => $request->id_sala,
                'id_user' => Auth()->user()->id,
                'data_hora' => $data
            ]);
            return Response()->json(Reserva::orderBy('data_hora')->get());
        }
    }
    
    public function deleteReserva(Request $request) {
        if($request->ajax()){
            Reserva::destroy($request->id);
            return Response()->json(['sms' => 'Excluido com sucesso!']);
        }
    }
}
