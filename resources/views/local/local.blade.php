@extends('layouts.app')

@section('content')

        <div class="container">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Locais
                </div>
            
                <div class="panel-body">   
                    @include('local.newLocal')
                    <table id="listaLocal" class="table table-hover">
                        <caption><button type="button" class="btn btn-success" id="add">Novo</button></caption>
                        <thead>
                            <th>Nome</th>
                            <th></th>
                        </thead>
                        <tbody>
                            @foreach($locais as $key => $local)
                            <tr id="local{{$local->id}}">
                                <td>{{$local->nome}}</td>
                                <td class="text-right">
                                    <button class="btn btn-info btn-reserva" data-id="{{$local->id}}">Reservas</button>
                                    <button class="btn btn-warning btn-edit" data-id="{{$local->id}}">Editar</button>
                                    <button class="btn btn-danger btn-delete" data-id="{{$local->id}}">Excluir</button>
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
                    $('#frmLocal').trigger('reset');
                    $('#id').val('');
                    $('#local').modal('show');          
                });
                
                $('#frmLocal').on('submit',function(e){
                    e.preventDefault();
                    if(!validaCampos()){
                        return alert('Todos os campos são obrigatórios!');
                    }
                    var form = $('#frmLocal');
                    var formData = form.serialize();
                    var url = form.attr('action');
                    var id = $('#id').val();
                    if(id !== ''){
                        var url = 'newUpdate';
                    }
                    
                    $.ajax({
                       type : 'post' ,
                       url  : url,
                       data : formData,
                       async: true,
                       dataType: 'json',
                       success:function(data){
                            $('#frmLocal').trigger('reset');
                            $('#id').val('');
                            if(id != ''){
                                $('#local').modal('hide'); 
                            } else {
                                $('#nome').focus();
                            }
                            addRow(data);
                       }
                    });
                });
                
                $('#listaLocal tbody').delegate('.btn-edit','click',function(){
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
                           $('#local').modal('show'); 
                       }
                    });
                });
                
                $('#listaLocal tbody').delegate('.btn-reserva','click',function(){
                   var id = $(this).data('id'); 
                   $.ajax({
                       type : 'post' ,
                       url  : 'getReservas',
                       data : {'id_local':id},
                       async: true,
                       dataType: 'html',
                       success:function(data){                            
                            $('#reservaAux').html(data);
                            $('#reserva').modal('show');
                       }
                    });
                });
                
                $('#listaLocal tbody').delegate('.btn-delete','click',function(){
                   var id = $(this).data('id'); 
                   $('#btnPadraoConfirmar').attr('onClick','excluirLocal('+id+')');
                   $('#modalPadraoTitulo').text('Excluir Local');  
                   $('#modalPadraoMsg').text('Tem certeza que deseja Excluir ?');  
                   $('#btnPadraoConfirmar').css('display','');
                   $('#modalPadrao').modal('show');  
                });
                
                function excluirLocal(id){
                    $.ajax({
                        type : 'post' ,
                        url  : 'deleteLocal',
                        data : {'id':id},
                        async: true,
                        dataType: 'html',
                        success:function(data){
                            if(data.toString().indexOf('msg_error->') !== '-1'){
                                    setTimeout(function(){
                                        $('#btnPadraoConfirmar').css('display','none');
                                        $('#modalPadraoTitulo').text('Erro!');  
                                        $('#modalPadraoMsg').text(data.toString().split('msg_error->')[1]);  
                                        $('#modalPadrao').modal('show'); 
                                    }, 500);             
                            } else {
                                $('#local'+id).remove();
                                $('#modalPadrao').modal('hide');
                            }
                        }
                     });
                }
                
                function validaCampos(){
                    var retorno = true;
                    if(jQuery('#nome').val() === ''){
                        retorno = false;
                    }
                    return retorno;
                }
                
                function addRow(data){
                    var row = '';
                    jQuery.each(data, function(i, obj) {
                        row += '<tr id="local'+obj.id+'">'+
                                  '<td>'+obj.nome+'</td>'+
                                  '<td class="text-right"><button class="btn btn-info btn-reserva" data-id="'+obj.id+'">Reservas</button> <button class="btn btn-warning btn-edit" data-id="'+obj.id+'">Editar</button> <button class="btn btn-danger btn-delete" data-id="'+obj.id+'">Excluir</button></td>'+
                                '</tr>';
                    });
                    $('#listaLocal tbody').html(row);
                }
            </script>
        </div>
@stop
