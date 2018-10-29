const reloadPersonList = function (entity, value) {
    if ($('#appear-list-container').length) {
        // exists
        let url = 'api/appear/all/' + String(entity) + '/' + String(value);
        reloadList(url, '#appear-list');

    }
}

//behavior
$('#appear-list').on('click-cell.bs.table', function (field, value, row, $element) {
    $('#appear-actions').modal('show');

    $('#asis_nombre').html($element.nombre);
    $('#asis_cedula').html($element.cedula)
    $('#asis_cargo').html($element.cargo);
    $('#asis_ubicacion').html($element.ubicacion);
    $('#person-id').data('id', $element.id); //element id
    $('#note').val('');

    let my_extra = ($element.extra === 'SI') ? true : false;
    $('#extras').prop('checked', my_extra);

    //get hour
    let hour_desc = ($element.entrada) ? 'Hora de salida:' : 'Hora de entrada:';
    let hour_value = ($element.entrada) ? '16:00' : '07:00';
    // let d = new Date(); // for now
    // let h1 = d.getHours(); // => 9
    // let m1 = d.getMinutes(); // =>  30
    // h1 = (h1 < 10) ? "0" + String(h1) : String(h1);
    // m1 = (m1 < 10) ? "0" + String(m1) : String(m1);
    // $('#my_hour').val(String(h1) + ':' + String(m1));
    $('#my_hour').val(hour_value);
    $('#hour-desc').html(hour_desc);

    ///tools
    let action_in = $('#in_' + $element.id).data("value");
    let action_out = $('#out_' + $element.id).data("value");

    if ($element.entrada !== '') {
        $("#appear-in").hide();
        $("#non-appear").hide();
        $("#appear-out").show();
        $("#delete-appear").show();
        $(".extra").show();
    } else {
        $("#appear-in").show();
        $("#non-appear").show();
        $(".extra").hide();
        $("#appear-out").hide();
        $("#delete-appear").hide();
    }

});

$('#non-appear-list').on('click-cell.bs.table', function (field, value, row, $element) {
    $('#non-appear-actions').modal('show');

    $('#ina_nombre').html($element.nombre);
    $('#ina_cedula').html($element.cedula)
    $('#ina_cargo').html($element.cargo);
    $('#ina_ubicacion').html($element.ubicacion);
    $('#non-appear-id').data('id', $element.id); //element id

});

$("#appear-in").on("click", function () {
    let person = $('#person-id').data('id'); //person id
    let date = $('#person-id').data('date'); //date
    let hour = $('#my_hour').val(); //person id
    let note = $('#note').val(); //person id
    let data = {
        "person": person,
        "type": 1,
        "in_hour": hour,
        "date": date,
        "note": note,
    }
    axios.put(api_url + 'api/appear/save', data)
        .then(function (response) {
            let info = response.data.info;
            $('#appear-list').bootstrapTable('updateByUniqueId', {
                id: info.detail.id, row: {
                    nombre: info.detail.nombre,
                    cedula: info.detail.cedula,
                    cargo: info.detail.cargo,
                    ubicacion: info.detail.ubicacion,
                    fecha: info.detail.fecha,
                    entrada: info.detail.entrada,
                    salida: '',
                }
            });
        }).catch(function (error) {
        showAlert(error.response.data.message)
    }).finally(function () {
        $('#appear-actions').modal('hide')
    });

})


$("#appear-out").on("click", function () {
    let person = $('#person-id').data('id'); //person id
    let hour = $('#my_hour').val(); //person id
    let note = $('#note').val(); //person id
    let date = $('#person-id').data('date'); //date
    let extras = $('#extras').prop('checked')?1:0 //extras
    let data = {
        "person": person,
        "out_hour": hour,
        "date": date,
        "note": note,
        "extras": extras,
    }
    axios.put(api_url + 'api/appear/saveOut', data)
        .then(function (response) {
            let info = response.data.info;
            console.log(info)
            $('#appear-list').bootstrapTable('updateByUniqueId', {
                id: info.id, row: {
                    nombre: info.nombre,
                    cedula: info.cedula,
                    cargo: info.cargo,
                    ubicacion: info.ubicacion,
                    fecha: info.fecha,
                    extra: info.extra,
                    entrada: info.entrada,
                    salida: info.salida
                }
            });
        }).catch(function (error) {
        showAlert(error.response.data.message)
    }).finally(function () {
        $('#appear-actions').modal('hide')
    });
})


$("#delete-appear").on("click", function () {
    let person = $('#person-id').data('id'); //person id
    let date = $('#person-id').data('date'); //date
    axios.delete(api_url + 'api/appear/' + person + '/' + date)
        .then(function (response) {
            let info = response.data.info;
            console.log(info);
            $('#appear-list').bootstrapTable('updateByUniqueId', {
                id: info.id, row: {
                    nombre: info.nombre,
                    cedula: info.cedula,
                    cargo: info.cargo,
                    ubicacion: info.ubicacion,
                    fecha: info.fecha,
                    entrada: '',
                    salida: '',
                    extra: ''
                }
            });
        }).catch(function (error) {
        showAlert("error en el servicio")
    }).finally(function () {
        $('#appear-actions').modal('hide')
    });
})


$("#non-appear").on("click", function () {
    let person = $('#person-id').data('id'); //person id
    let date = $('#person-id').data('date'); //date
    let note = $('#note').val(); //person id
    let data = {
        "person": person,
        "type": 0,
        "date": date,
        "note": note,
    }

    axios.put(api_url + 'api/appear/save', data)
        .then(function (response) {
            let info = response.data.info;
            console.log(info)
            if (info.action === 0) { //non appear
                $('#appear-list').bootstrapTable('remove', {
                    field: 'cedula',
                    values: [info.detail.cedula]
                });

                $('#non-appear-list')
                    .bootstrapTable('insertRow', {
                        index: 1,
                        row: {
                            id: info.detail.non_id,
                            nombre: info.detail.nombre,
                            cedula: info.detail.cedula,
                            cargo: info.detail.cargo,
                            ubicacion: info.detail.ubicacion,
                            fecha: info.detail.fecha,
                        }
                    });

            }
        }).catch(function (error) {
        showAlert(error.response.data.message)
    }).finally(function () {
        $('#appear-actions').modal('hide')
    });

})


$("#delete-non-appear").on("click", function () {
    let non_appear = $('#non-appear-id').data('id'); //non appear id
    axios.delete(api_url + 'api/appear/non/' + non_appear)
        .then(function (response) {
            let info = response.data.info;
            $('#non-appear-list').bootstrapTable('remove', {
                field: 'id',
                values: [non_appear]
            });

            $('#appear-list')
                .bootstrapTable('insertRow', {
                    index: 1,
                    row: {
                        id: info.id,
                        nombre: info.nombre,
                        cedula: info.cedula,
                        cargo: info.cargo,
                        ubicacion: info.ubicacion,
                        entrada: '',
                        salida: '',
                        extra: ''
                    }
                });

        }).catch(function (error) {
        showAlert(error.response.data.message)
    }).finally(function () {
        $('#non-appear-actions').modal('hide')
    });

})


$('#appear-batch').confirm({
    title: 'Registrar asistencia masiva',
    content: '¿Esta seguro que desea registrar la asistencia de todo el personal de esta lista?',
    buttons: {
        confirm: function () {
            let date = $("#appear_date").val();
            axios.put(api_url + 'api/appear/saveBatch/' + date)
                .then(function (response) {
                    showSuccess(response.data.message, 2000)
                    ///reload person list on date
                    let url = 'api/appear/filter/' + date;
                    reloadList(url, '#appear-list');

                }).catch(function (error) {
                showAlert(error.response.data.message)
            });
        },
        cancel: function () {
        }
    }
});
