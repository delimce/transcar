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

    //get hour
    let d = new Date(); // for now
    let h1 = d.getHours(); // => 9
    let m1 = d.getMinutes(); // =>  30
    h1 = (h1 < 10) ? "0" + String(h1) : String(h1);
    m1 = (m1 < 10) ? "0" + String(m1) : String(m1);
    $('#my_hour').val(String(h1) + ':' + String(m1));

    ///tools
    let action_in = $('#in_' + $element.id).data("value");
    let action_out = $('#out_' + $element.id).data("value");

    if ($element.entrada !== '') {
        $("#appear-in").hide();
        $("#non-appear").hide();
        $("#appear-out").show();
    } else {
        $("#appear-in").show();
        $("#non-appear").show();
        $("#appear-out").hide();
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
    let hour = $('#my_hour').val(); //person id
    let data = {
        "person": person,
        "type": 1,
        "in_hour": hour
    }
    axios.put(api_url + 'api/appear/save', data)
        .then(function (response) {
            let info = response.data.info;
            $('#appear-list').bootstrapTable('updateByUniqueId', {
                id: info.detail.person_id, row: {
                    nombre: info.detail.nombre,
                    cedula: info.detail.cedula,
                    cargo: info.detail.cargo,
                    ubicacion: '',
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
    let data = {
        "person": person,
        "out_hour": hour
    }
    axios.put(api_url + 'api/appear/saveOut', data)
        .then(function (response) {
            let info = response.data.info;
            $('#appear-list').bootstrapTable('updateByUniqueId', {
                id: info.person_id, row: {
                    nombre: info.nombre,
                    cedula: info.cedula,
                    cargo: info.cargo,
                    ubicacion: '',
                    fecha: info.fecha,
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


$("#non-appear").on("click", function () {
    let person = $('#person-id').data('id'); //person id
    let data = {
        "person": person,
        "type": 0
    }

    axios.put(api_url + 'api/appear/save', data)
        .then(function (response) {
            let info = response.data.info;
            console.log(info)
            if (info.action === 0) { //non appear
                // $('#appear-list').bootstrapTable('remove', {
                //     field: 'id',
                //     values: [info.detail.person]
                // });

                $('#non-appear-list')
                    .bootstrapTable('insertRow', {
                        index: 1,
                        row: {
                            id: info.detail.non_id,
                            nombre: info.detail.nombre,
                            cedula: info.detail.cedula,
                            cargo: info.detail.cargo,
                            ubicacion: '',
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
            $('#non-appear-list').bootstrapTable('remove', {
                field: 'id',
                values: [non_appear]
            });
        }).catch(function (error) {
        showAlert(error.response.data.message)
    }).finally(function () {
        $('#non-appear-actions').modal('hide')
    });

})
