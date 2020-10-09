<div class="modal" id="registerTask" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Cadastrar Tarefa</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formRegisterTask" action="<?= url("/task/nova") ?>" method="post">
                    <input type="hidden" name="action" value="create">
                    <div class="form-group">
                        <label for="name">Nome da tarefa</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Ex.: Natação">
                    </div>
                    <div class="form-group">
                        <label for="datetime">Data e hora da tarefa</label>
                        <input type="datetime-local" class="form-control" id="datetime" name="datetime">
                    </div>
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="completed" name="completed" value="completed">
                        <label class="form-check-label" for="completed">Tarefa Feita</label>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary" form="formRegisterTask">Salvar</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>