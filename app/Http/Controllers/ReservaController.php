<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Reserva;
use App\Sala;

class ReservaController extends Controller
{
    public function getReservas(Request $request){
//        $reservas = Reserva::orderBy('data_hora')->get();
        $sala = Sala::find($request->id_sala);
        
        $reservas = \DB::select("select reservas.id, DATE_FORMAT(reservas.data_hora,'%d/%m/%Y') data, DATE_FORMAT(reservas.data_hora,'%H:%i') hora, users.name
                                from reservas 
                                join users on users.id = reservas.id_user 
                                where reservas.id_sala = ".$request->id_sala."
                                order by reservas.data_hora");
        
        return view('reserva/reserva',['reservas'=>$reservas,'sala'=>$sala]);
    }
    
    public function newReserva(Request $request) {
        if($request->ajax()){
            
            $data = explode('/',$request->data_reserva);
            $data = $data[2].'-'.$data[1].'-'.$data[0].' '.$request->hora_reserva.':00';
            
            $reservas = $this->validarDataHora($data, $request);
            
            if(!$reservas){
            
                Reserva::create([
                    'id_sala' => $request->id_sala,
                    'id_user' => Auth()->user()->id,
                    'data_hora' => $data
                ]);

                $reservas = \DB::select("select reservas.id, DATE_FORMAT(reservas.data_hora,'%d/%m/%Y') data, DATE_FORMAT(reservas.data_hora,'%H:%i') hora, users.name
                                        from reservas 
                                        join users on users.id = reservas.id_user 
                                        where reservas.id_sala = ".$request->id_sala."
                                        order by reservas.data_hora");
            }
            return Response()->json($reservas);
        }
    }
    
    public function deleteReserva(Request $request) {
        if($request->ajax()){
            if($this->validarExclusao($request)){
                Reserva::destroy($request->id);
            } else {
                return 'msg_error->Essa reserva pertence a outro usuário!';
            }
        }
    }
    
    public function validarExclusao($request){
        $totalReservas = \DB::select("select count(*) total from reservas 
                                    where id = ".$request->id." and id_user = ".Auth()->user()->id);
        return $totalReservas[0]->total > 0;
    }
    
    public function validarDataHora($date, $request){
        // valida a data
        if (!empty($date) && $v_date = date_create_from_format('Y-m-d H:i:s', $date)) {
            $v_date = date_format($v_date, 'Y-m-d H:i:s');
            if(!($v_date && $v_date == $date))
                return 'msg_error->Data ou Hora inválida!';
        }
        
        // valida se a data é menor que a atual
        if($date < date('Y-m-d H:i:s')){
            return 'msg_error->Data ou Hora inválida!';
        }
        
        // valida se tem alguma reserva no intervalo escolhido
        $totalReservas = \DB::select("select count(*) total from reservas 
                                    where id_sala = ".$request->id_sala." and ('$date' between data_hora and DATE_ADD(data_hora, Interval 59 Minute) 
                                    or DATE_ADD('$date', Interval 59 Minute) between data_hora and DATE_ADD(data_hora, Interval 59 Minute))");
        if($totalReservas[0]->total > 0){
            return 'msg_error->Esse horário já está reservado por outro usuário!';
        }
        
        // valida se tem alguma reserva no intervalo escolhido pelo mesmo usuário em outra sala
        $totalReservas = \DB::select("select count(*) total from reservas 
                                    where id_sala <> ".$request->id_sala." and id_user = ".Auth()->user()->id." and ('$date' between data_hora and DATE_ADD(data_hora, Interval 59 Minute) 
                                    or DATE_ADD('$date', Interval 59 Minute) between data_hora and DATE_ADD(data_hora, Interval 59 Minute))");
        if($totalReservas[0]->total > 0){
            return 'msg_error->Você já possui esse horário reservado em outra sala!';
        }
    }
}
