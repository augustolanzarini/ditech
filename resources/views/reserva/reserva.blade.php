<div class="modal fade" id="reserva" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Reservas - {{$sala->nome}}</h4>
            </div>
            
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12 col-sm-12">
                        <div class="form-group">
                            <form action="newReserva" method="post" id="frmReserva">
                                <div class="col-lg-3 col-sm-3">
                                    <input type="text" name="data_reserva" id="data_reserva" placeholder="Data" class="form-control">
                                </div>
                                <div class="col-lg-3 col-sm-3">
                                    <input type="text" name="hora_reserva" id="hora_reserva" placeholder="Hora" class="form-control">
                                </div>
                                <div class="col-lg-6 col-sm-6">
                                    <input type="submit" class="btn btn-primary" value="Salvar" id="salvar">
                                </div>
                                <input type="hidden" name="id_sala" id="id_sala" value="{{$sala->id}}">
                            </form>
                        </div>
                    </div>
                    <div class="col-lg-12 col-sm-12"><br></div>
                    <div class="col-lg-12 col-sm-12">
                        <div style="overflow: auto; height: 290px;" class="form-group">
                            <table id="listaReservas" class="table table-hover">
                                <thead>
                                    <th>Data</th>
                                    <th>Hora</th>
                                    <th>Usuário</th>
                                    <th></th>
                                </thead>
                                <tbody>
                                    @foreach($reservas as $key => $reserva)
                                    <tr id="reserva{{$reserva->id}}">
                                        <td>{{$reserva->data}}</td>
                                        <td>{{$reserva->hora}}</td>
                                        <td>{{$reserva->name}}</td>
                                        <td class="text-right">
                                            <button class="btn btn-danger btn-delete" data-id="{{$reserva->id}}">Excluir</button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
            </div>            
        </div>
    </div>
</div>
<script type="text/javascript">
    $( function() {
        $('#data_reserva').datepicker({
            dateFormat : "dd/mm/yy",
            minDate: 0,
            dayNames: ['Domingo','Segunda','Terça','Quarta','Quinta','Sexta','Sábado'],
            dayNamesMin: ['D','S','T','Q','Q','S','S','D'],
            dayNamesShort: ['Dom','Seg','Ter','Qua','Qui','Sex','Sáb','Dom'],
            monthNames: ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],
            monthNamesShort: ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'],
            nextText: 'Próximo',
            prevText: 'Anterior'
        });
        $('#data_reserva').mask('00/00/0000');
        $('#hora_reserva').mask('00:00');
        
        $('#frmReserva').on('submit',function(e){
            e.preventDefault();
            if(!validaCamposReserva()){
                return alert('Todos os campos são obrigatórios!');
            }
            var form = $('#frmReserva');
            var formData = form.serialize();
            var url = form.attr('action');

            $.ajax({
               type : 'post' ,
               url  : url,
               data : formData,
               async: true,
               dataType: 'json',
               success:function(data){
                   if(data.toString().indexOf('msg_error->') !== '-1'){
                        $('#btnPadraoConfirmar').css('display','none');
                        $('#modalPadraoTitulo').text('Erro!');  
                        $('#modalPadraoMsg').text(data.toString().split('msg_error->')[1]);  
                        $('#modalPadrao').modal('show');  
                   } else {
                        $('#data_reserva').val('');
                        $('#hora_reserva').val('');
                        addRowReserva(data);
                   }
               }
            });
        });
        
        $('#listaReservas tbody').delegate('.btn-delete','click',function(){
            var id = $(this).data('id'); 
            $('#btnPadraoConfirmar').attr('onClick','excluirReserva('+id+')');
            $('#modalPadraoTitulo').text('Excluir Reserva');  
            $('#modalPadraoMsg').text('Tem certeza que deseja Excluir ?');  
            $('#btnPadraoConfirmar').css('display','');
            $('#modalPadrao').modal('show');  
         });
    });
    
    function validaCamposReserva(){
        var retorno = true;
        if(jQuery('#data_reserva').val() === ''){
            retorno = false;
        }
        if(jQuery('#hora_reserva').val() === ''){
            retorno = false;
        }
        return retorno;
    }
    
    function addRowReserva(data){
        var row = '';
        jQuery.each(data, function(i, obj) {
            
            row += '<tr id="reserva'+obj.id+'">'+
                      '<td>'+obj.data+'</td>'+
                      '<td>'+obj.hora+'</td>'+
                      '<td>'+obj.name+'</td>'+
                      '<td class="text-right"><button class="btn btn-danger btn-delete" data-id="'+obj.id+'">Excluir</button></td>'+
                    '</tr>';
        });
        $('#listaReservas tbody').html(row);
    }
    
     function excluirReserva(id){
         $.ajax({
             type : 'post' ,
             url  : 'deleteReserva',
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
                    $('#modalPadrao').modal('hide');
                    $('#reserva'+id).remove();
                }
             }
          });
     }
</script>