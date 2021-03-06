<!-- Modal -->
<div id="non-appear-actions" class="modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Asistencia del día: {{ Carbon\Carbon::parse($date)->format('d/m/Y') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div style="padding: 10px" class="row" id="non-appear-id" data-id="" data-date="{{$date}}">
                    <div class="col-sm-6">
                        <span class="appear-subtitle">Nombre:</span>&nbsp;<span id="ina_nombre"></span><br>
                        <span class="appear-subtitle">Cargo:</span>&nbsp;<span  id="ina_cargo"></span><br>
                        <span class="appear-subtitle">Justificada:</span>&nbsp;<span  id="ina_justify"></span><br>
                        <span class="appear-subtitle">Nota:</span>&nbsp;<span id="ina_nota"></span>
                    </div>
                    <div class="col-sm-6">
                        <span class="appear-subtitle">Cédula:</span>&nbsp;<span id="ina_cedula"></span><br>
                        <span class="appear-subtitle">Ubicación:</span>&nbsp;<span id="ina_ubicacion"></span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button id="delete-non-appear" type="button" class="btn btn-danger">Borrar Inasistencia</button>
            </div>
        </div>
    </div>
</div>