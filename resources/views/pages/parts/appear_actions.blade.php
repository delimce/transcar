<!-- Modal -->
<div id="appear-actions" class="modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Operaciones</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div style="padding: 10px" class="row" id="person-id" data-id="">
                    <div class="col-sm-6">
                        <span class="appear-subtitle">Nombre:</span>&nbsp;<span id="asis_nombre"></span><br>
                        <span class="appear-subtitle">Cargo:</span>&nbsp;<span  id="asis_cargo"></span><br>
                    </div>
                    <div class="col-sm-6">
                        <span class="appear-subtitle">Cedula:</span>&nbsp;<span id="asis_cedula"></span><br>
                        <span class="appear-subtitle">Ubicacion:</span>&nbsp;<span id="asis_ubicacion"></span><br>
                    </div>
                    <div class="data-ap-non" class="col-sm-6">
                        <span class="appear-subtitle">Hora:&nbsp;<input id="my_hour" type="time" value=""></span>&nbsp;<br>
                        <span class="appear-subtitle">Nota:&nbsp;<input id="note" style="width: 300px" type="text" value=""></span>&nbsp;
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button id="appear-out" type="button" class="btn badge-dark">Registrar Salida</button>&nbsp;
                <button id="delete-appear" type="button" class="btn btn-danger">Borrar horas registradas</button>&nbsp;
                <button id="appear-in" type="button" class="btn btn-primary">Registrar Entrada</button>&nbsp;
                <button id="non-appear" type="button" class="btn btn-warning">Registrar Inasistencia</button>
            </div>
        </div>
    </div>
</div>