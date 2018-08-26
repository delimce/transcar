// buttons
$("#to-person-form").click(function () {
    $('.sub-title').html('Nuevo Empleado');
    toggle_person_list(false);
});

$("#to-person-list").click(function () {
    toggle_person_list();
});

//functions
const toggle_person_list = function (mode = true) {
    if (mode) {
        reloadList('api/person/all', '#person-list');
        $("#person-list-container").show();
        $("#person-form").hide();
        $("#person_form")[0].reset();
        $('#person-table').hide();
    } else {
        $("#person-list-container").hide();
        $("#person-form").show();
    }
}

///reload select list
const reloadRoleSelectBox = function () {
    axios.get(api_url + "api/role/all")
        .then(function (response) {
            let options = '';
            let data = response.data.list;
            let len = data.length;
            for (let i = 0; i < len; i++) {
                options += '<option value=' + data[i].id + '>' + data[i].nombre + '</option>';
            }
            $('.selectpickerRole').empty();
            $('.selectpickerRole').append(options);
            $('.selectpickerRole').selectpicker('refresh');
        }).catch(function (error) {
        showAlert(error.response.data.message)
    });
}

// Forms
$("#person_form").submit(function (event) {
    const $form = $('#person_form');
    axios.post(api_url + 'api/person', $form.serialize())
        .then(function (response) {
            showSuccess(response.data.message, 2000)
            toggle_person_list();
        }).catch(function (error) {
        showAlert(error.response.data.message)
    });
    event.preventDefault();
});

//behavior
$('#person-list').on('click-cell.bs.table', function (field, value, row, $element) {
    toggle_person_list(false);
    $('#delete-person').show();
    $('#person_form').data('record', $element.id); //element id
    $('.sub-title').html('Editar Empleado');
    axios.get(api_url + 'api/person/' + $element.id)
        .then(function (response) {
            const datai = response.data.person;
            $("#person_form input[name=nombre]").val(datai.nombre);
            $("#person_form input[name=apellido]").val(datai.apellido);
            $("#person_form input[name=cedula]").val(datai.cedula);
            $("#person_form input[name=fecha_nac]").val(datai.fecha_nac);
            $("#person_form input[name=fecha_ingreso]").val(datai.fecha_ingreso);
            $("#person_form input[name=email]").val(datai.email);
            $("#person_form input[name=telefono]").val(datai.telefono);
            $("#person_form select[name=area]").val(datai.area_id);
            $("#person_form select[name=area]").change();
            $("#person_form select[name=cargo]").val(datai.cargo_id);
            $("#person_form select[name=cargo]").change();
            $("#person_form input[name=titular]").val(datai.titular);
            $("#person_form input[name=account]").val(datai.cuenta_bancaria);

            ///append id to form
            $('<input>').attr({
                type: 'hidden',
                value: datai.id,
                id: 'person_id',
                name: 'person_id'
            }).appendTo('#person_form');

        }).catch(function (error) {
        showAlert(error.response.data.message)
    });
});

$('#area').on('changed.bs.select', function (e, clickedIndex, isSelected, previousValue) {
    // do something...
    axios.get(api_url + "api/role/all/"+$(this).val())
        .then(function (response) {
            let options = '';
            let data = response.data.list;
            let len = data.length;
            for (let i = 0; i < len; i++) {
                options += '<option value=' + data[i].id + '>' + data[i].nombre + '</option>';
            }
            $('.selectpickerRole').empty();
            $('.selectpickerRole').append(options);
            $('.selectpickerRole').selectpicker('refresh');
        }).catch(function (error) {
        showAlert(error.response.data.message)
    });

});

$('#delete-person').confirm({
    title: 'Borrar Empleado',
    content: 'Esta seguro que desea borrar este Empleado?',
    buttons: {
        confirm: function () {
            let person_id = $("#person_form").data("record");
            axios.delete(api_url + 'api/person/' + person_id)
                .then(function (response) {
                    showSuccess(response.data.message, 2000)
                    toggle_person_list();
                }).catch(function (error) {
                showAlert(error.response.data.message)
            });
        },
        cancel: function () {
        }
    }
});