@extends('layouts.app')

@section('content')

        <div class="container">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Salas
                </div>
            
                <div class="panel-body">   
                    @include('sala.newSala')
                    <table id="listaSala" class="table table-hover">
                        <caption><button type="button" class="btn btn-success" id="add">Novo</button></caption>
                        <thead>
                            <th>Nome</th>
                            <th></th>
                        </thead>
                        <tbody>
                            @foreach($salas as $key => $sala)
                            <tr id="sala{{$sala->id}}">
                                <td>{{$sala->nome}}</td>
                                <td class="text-right">
                                    <button class="btn btn-info btn-reserva" data-id="{{$sala->id}}">Reservas</button>
                                    <button class="btn btn-warning btn-edit" data-id="{{$sala->id}}">Editar</button>
                                    <button class="btn btn-danger btn-delete" data-id="{{$sala->id}}">Excluir</button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div id="reservaAux"></div>
            <script type="text/javascript">
                
                $('#add').on('click',function(){
                    $('#frmSala').trigger('reset');
                    $('#id').val('');
                    $('#sala').modal('show');          
                });
                
                $('#frmSala').on('submit',function(e){
                    e.preventDefault();
                    if(!validaCampos()){
                        return alert('Todos os campos são obrigatórios!');
                    }
                    var form = $('#frmSala');
                    var formData = form.serialize();
                    var url = form.attr('action');
                    var id = $('#id').val();
                    if(id != ''){
                        var url = 'newUpdate';
                    }
                    
                    $.ajax({
                       type : 'post' ,
                       url  : url,
                       data : formData,
                       async: true,
                       dataType: 'json',
                       success:function(data){
                            $('#frmSala').trigger('reset');
                            $('#id').val('');
                            if(id != ''){
                                $('#sala').modal('hide'); 
                            } else {
                                $('#nome').focus();
                            }
                            addRow(data);
                       }
                    });
                });
                
                $('#listaSala tbody').delegate('.btn-edit','click',function(){
                   var id = $(this).data('id'); 
                   $.ajax({
                       type : 'post' ,
                       url  : 'getUpdate',
                       data : {'id':id},
                       async: true,
                       dataType: 'json',
                       success:function(data){
                           $('#id').val(data.id);
                           $('#nome').val(data.nome);
                           $('#sala').modal('show'); 
                       }
                    });
                });
                
                $('#listaSala tbody').delegate('.btn-reserva','click',function(){
                   var id = $(this).data('id'); 
                   $.ajax({
                       type : 'post' ,
                       url  : 'getReservas',
                       data : {'id_sala':id},
                       async: true,
                       dataType: 'html',
                       success:function(data){
//                           $('#id').val(data.id);
//                           $('#nome').val(data.nome);
//                           $('#sala').modal('show'); 
                            
                            $('#reservaAux').html(data);
                            $('#reserva').modal('show');
                       }
                    });
                });
                
                $('#listaSala tbody').delegate('.btn-delete','click',function(){
                   var id = $(this).data('id'); 
                   $('#btnPadraoConfirmar').attr('onClick','excluirSala('+id+')');
                   $('#modalPadraoTitulo').text('Excluir Sala');  
                   $('#modalPadraoMsg').text('Tem certeza que deseja Excluir ?');  
                   $('#btnPadraoConfirmar').css('display','');
                   $('#modalPadrao').modal('show');  
                });
                
                function excluirSala(id){
                    $.ajax({
                        type : 'post' ,
                        url  : 'deleteSala',
                        data : {'id':id},
                        async: true,
                        dataType: 'html',
                        success:function(data){
                            if(data.toString().indexOf('msg_error->') != '-1'){
                                    setTimeout(function(){
                                        $('#btnPadraoConfirmar').css('display','none');
                                        $('#modalPadraoTitulo').text('Erro!');  
                                        $('#modalPadraoMsg').text(data.toString().split('msg_error->')[1]);  
                                        $('#modalPadrao').modal('show'); 
                                    }, 500);             
                            } else {
                                $('#sala'+id).remove();
                                $('#modalPadrao').modal('hide');
                            }
                        }
                     });
                }
                
                function validaCampos(){
                    var retorno = true;
                    if(jQuery('#nome').val() == ''){
                        retorno = false;
                    }
                    return retorno;
                }
                
                function addRow(data){
                    var row = '';
                    jQuery.each(data, function(i, obj) {
                        row += '<tr id="sala'+obj.id+'">'+
                                  '<td>'+obj.nome+'</td>'+
                                  '<td class="text-right"><button class="btn btn-info btn-reserva" data-id="'+obj.id+'">Reservas</button> <button class="btn btn-warning btn-edit" data-id="'+obj.id+'">Editar</button> <button class="btn btn-danger btn-delete" data-id="'+obj.id+'">Excluir</button></td>'+
                                '</tr>';
                    });
                    $('#listaSala tbody').html(row);
                }
            </script>
        </div>
@stop
