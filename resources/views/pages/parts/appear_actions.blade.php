<!-- Modal -->
<div id="appear-actions" class="modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Asistencia del día: {{ Carbon\Carbon::parse($date)->format('d/m/Y') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div style="padding: 10px" class="row" id="person-id" data-id="" data-date="{{$date}}">
                    <div class="col-sm-6">
                        <span class="appear-subtitle">Nombre:</span>&nbsp;<span id="asis_nombre"></span><br>
                        <span class="appear-subtitle">Cargo:</span>&nbsp;<span  id="asis_cargo"></span><br>
                    </div>
                    <div class="col-sm-6">
                        <span class="appear-subtitle">Cedula:</span>&nbsp;<span id="asis_cedula"></span><br>
                        <span class="appear-subtitle">Ubicacion:</span>&nbsp;<span id="asis_ubicacion"></span><br>
                    </div>
                    <div class="data-ap-non" class="col-sm-6">
                        <span class="appear-subtitle"><b id="hour-desc"></b>&nbsp;<input id="my_hour" type="time" value=""></span>&nbsp;<br>
                        <span class="appear-subtitle">Nota:&nbsp;<input id="note" style="width: 300px" type="text" value=""></span>&nbsp;
                    </div>
                     {{-- extra --}}
                     <div class="col-sm-6 extra">
                            <span class="appear-subtitle">¿Hizo horas extras?:</span>
                            <span class="appear-subtitle">
                                    &nbsp; <input name="extras" id="extras" value="1" type="checkbox">
                            </span><br>
                      </div>
                        {{-- extra --}}

                       {{-- justify --}}
                     <div class="col-sm-6 justify">
                        <span class="appear-subtitle">
                                &nbsp; <input name="justify" id="justify" value="1" type="checkbox">
                        </span>
                        <span class="appear-subtitle">¿Inasistencia Justificada?:</span>

                     </div>
                         {{-- justify --}}
                </div>
            </div>
            <div class="modal-footer">
                <button id="appear-out" type="button" class="btn badge-dark">Guardar</button>&nbsp;
                <button id="delete-appear" type="button" class="btn btn-danger">Borrar horas registradas</button>&nbsp;
                <button id="appear-in" type="button" class="btn btn-primary">Guardar Asistencia</button>&nbsp;
                <button id="non-appear" type="button" class="btn btn-warning">Registrar Inasistencia</button>
            </div>
        </div>
    </div>
</div>