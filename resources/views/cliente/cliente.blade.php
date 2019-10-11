@extends('layouts.app')

@section('content')

        <div class="container">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Clientes
                </div>
            
                <div class="panel-body">   
                    @include('cliente.newCliente')
                    <table id="listaCliente" class="table table-hover">
                        <caption><button type="button" class="btn btn-success" id="add">Novo</button></caption>
                        <thead>
                            <th>Nome</th>
                            <th></th>
                        </thead>
                        <tbody>
                            @foreach($clientes as $key => $cliente)
                            <tr id="cliente{{$cliente->id}}">
                                <td>{{$cliente->nome}}</td>
                                <td class="text-right">
                                    <button class="btn btn-info btn-reserva" data-id="{{$cliente->id}}">Reservas</button>
                                    <button class="btn btn-warning btn-edit" data-id="{{$cliente->id}}">Editar</button>
                                    <button class="btn btn-danger btn-delete" data-id="{{$cliente->id}}">Excluir</button>
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
                    $('#frmCliente').trigger('reset');
                    $('#id').val('');
                    $('#cliente').modal('show');          
                });
                
                $('#frmCliente').on('submit',function(e){
                    e.preventDefault();
                    if(!validaCampos()){
                        return alert('Todos os campos são obrigatórios!');
                    }
                    var form = $('#frmCliente');
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
                            $('#frmCliente').trigger('reset');
                            $('#id').val('');
                            if(id != ''){
                                $('#cliente').modal('hide'); 
                            } else {
                                $('#nome').focus();
                            }
                            addRow(data);
                       }
                    });
                });
                
                $('#listaCliente tbody').delegate('.btn-edit','click',function(){
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
                           $('#cliente').modal('show'); 
                       }
                    });
                });
                
                $('#listaCliente tbody').delegate('.btn-reserva','click',function(){
                   var id = $(this).data('id'); 
                   $.ajax({
                       type : 'post' ,
                       url  : 'getReservas',
                       data : {'id_cliente':id},
                       async: true,
                       dataType: 'html',
                       success:function(data){                            
                            $('#reservaAux').html(data);
                            $('#reserva').modal('show');
                       }
                    });
                });
                
                $('#listaCliente tbody').delegate('.btn-delete','click',function(){
                   var id = $(this).data('id'); 
                   $('#btnPadraoConfirmar').attr('onClick','excluirCliente('+id+')');
                   $('#modalPadraoTitulo').text('Excluir Cliente');  
                   $('#modalPadraoMsg').text('Tem certeza que deseja Excluir ?');  
                   $('#btnPadraoConfirmar').css('display','');
                   $('#modalPadrao').modal('show');  
                });
                
                function excluirCliente(id){
                    $.ajax({
                        type : 'post' ,
                        url  : 'deleteCliente',
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
                                $('#cliente'+id).remove();
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
                        row += '<tr id="cliente'+obj.id+'">'+
                                  '<td>'+obj.nome+'</td>'+
                                  '<td class="text-right"><button class="btn btn-info btn-reserva" data-id="'+obj.id+'">Reservas</button> <button class="btn btn-warning btn-edit" data-id="'+obj.id+'">Editar</button> <button class="btn btn-danger btn-delete" data-id="'+obj.id+'">Excluir</button></td>'+
                                '</tr>';
                    });
                    $('#listaCliente tbody').html(row);
                }
            </script>
        </div>
@stop
