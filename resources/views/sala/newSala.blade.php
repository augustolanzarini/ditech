<!-- Modal -->
<div class="modal fade" id="sala" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Cadastro de Sala</h4>
            </div>
            <form action="newSala" method="post" id="frmSala">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12 col-sm-12">
                            <div class="form-group">
                                <input type="text" name="nome" id="nome" placeholder="Nome" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="id" id="id">
                <div class="modal-footer">
                    <input type="submit" class="btn btn-primary" value="Salvar" id="salvar">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                </div>
            </form>
        </div>
    </div>
</div>