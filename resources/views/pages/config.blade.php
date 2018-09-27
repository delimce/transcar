@extends('layouts.app')
@section('content')

    @component("components.pageTitle",['title' => 'Administración - Empresa'])
    @endcomponent
    <div id="config-container">
        <form id="config_form">
                <div class="row">
                        <div class="col-md-11 mx-auto">
                                
                            <div class="form-group required row">
                                <div class="col-sm-6">
                                    <label for="empresa_nombre" class="control-label">nombre Empresa</label>
                                    <input type="text" class="form-control" id="empresa_nombre" name="empresa_nombre"
                                           value="{{$config->empresa_nombre}}"
                                           placeholder="Nombre de la empresa"
                                           autocomplete="my-name-company"
                                           required>
                                </div>
                                <div class="col-sm-6">
                                    <label for="empresa_rif" class="control-label">Rif Empresa</label>
                                    <input type="text" class="form-control" id="empresa_rif" name="empresa_rif"
                                           value="{{$config->empresa_rif}}"
                                           placeholder="Rif de la empresa"
                                           autocomplete="my-rif-company"
                                           required>
                                </div>
                            </div>
                            
                            <div class="form-group required row">
                                        <div class="col-sm-6">
                                            <label for="iva" class="control-label">impuesto</label>
                                        <input type="number" value="{{$config->iva}}" class="form-control" id="iva" name="iva"
                                                   placeholder="impuesto iva"
                                                   autocomplete="impuesto"
                                                   required>
                                        </div>
                                        <div class="col-sm-6">
                                            <label for="cajas" class="control-label">Cajas por paleta</label>
                                            <input type="number" value="{{$config->caja_paleta}}" class="form-control" id="cajas" name="cajas"
                                                   placeholder="cajas por paleta"
                                                   autocomplete="cajas">
                                        </div>
                                    </div>

                                    <div class="form-group required row">
                                            <div class="col-sm-6">
                                                <button id="save_config" class="btn btn-primary" type="submit">
                                                    Guardar
                                                </button>
                                            </div>
                                        </div>
                        </div>
                </div>
        </form>
    </div>
    @endsection

