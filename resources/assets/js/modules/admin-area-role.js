// buttons
$("#to-area-form").click(function () {
    $('.sub-title').html('Crear Area');
    $('#area_form input[name=area_id]').remove();
    toggle_area_list(false);
});

$("#to-area-list").click(function () {
    toggle_area_list();
});

$("#to-role-form").click(function () {
    $('.sub-title').html('Crear Cargo');
    $('#role_form input[name=role_id]').remove();
    toggle_role_list(false);
});

$("#to-role-list").click(function () {
    toggle_role_list();
});


//functions
const toggle_area_list = function (mode = true) {
    if (mode) {
        reloadList('api/area/all', '#area-list');
        $("#area-list-container").show();
        $("#area-form").hide();
        $("#area_form")[0].reset();
        $('#delete-area').hide();
    } else {
        $("#area-list-container").hide();
        $("#area-form").show();
    }
}

const toggle_role_list = function (mode = true) {
    if (mode) {
        reloadList('api/role/all', '#role-list');
        $("#role-list-container").show();
        $("#role-form").hide();
        $("#role_form")[0].reset();
        $('#delete-role').hide();
    } else {
        $("#role-list-container").hide();
        $("#role-form").show();
    }
}

///reload select list
const reloadAreaSelectBox = function () {
    axios.get(api_url + "api/area/all")
        .then(function (response) {
            let options = '';
            let data = response.data.list;
            let len = data.length;
            for(let i=0;i<len;i++){
                options += '<option value=' + data[i].id + '>' + data[i].nombre + '</option>';
            }
            $('.selectpickerArea').empty();
            $('.selectpickerArea').append(options);
            $('.selectpickerArea').selectpicker('refresh');
        }).catch(function (error) {
        showAlert(error.response.data.message)
    });
}


// Forms
$("#area_form").submit(function (event) {
    const $form = $('#area_form');
    axios.post(api_url + 'api/area', $form.serialize())
        .then(function (response) {
            showSuccess(response.data.message, 2000)
            reloadAreaSelectBox();
            toggle_area_list();
        }).catch(function (error) {
        showAlert(error.response.data.message)
    });
    event.preventDefault();
});

$("#role_form").submit(function (event) {
    const $form = $('#role_form');
    axios.post(api_url + 'api/role', $form.serialize())
        .then(function (response) {
            showSuccess(response.data.message, 2000)
            toggle_role_list();
        }).catch(function (error) {
            console.log(error)
        showAlert(error.response.data.message)
    });
    event.preventDefault();
});

//behavior
$('#area-list').on('click-cell.bs.table', function (field, value, row, $element) {
    toggle_area_list(false);
    $('#delete-area').show();
    $('#area_form').data('record', $element.id); //element id
    $('.sub-title').html('Editar Area');
    axios.get(api_url + 'api/area/' + $element.id)
        .then(function (response) {
            const datai = response.data.area;
            $("#area_form input[name=nombre]").val(datai.nombre);
            $("#area_form input[name=descripcion]").val(datai.descripcion);

            ///append id to form
            $('<input>').attr({
                type: 'hidden',
                value: datai.id,
                id: 'area_id',
                name: 'area_id'
            }).appendTo('#area_form');

        }).catch(function (error) {
        showAlert(error.response.data.message)
    });
});

$('#role-list').on('click-cell.bs.table', function (field, value, row, $element) {
    toggle_role_list(false);
    $('#delete-role').show();
    $('#role_form').data('record', $element.id); //element id

    $('.sub-title').html('Editar Cargo');
    axios.get(api_url + 'api/role/' + $element.id)
        .then(function (response) {
            const datai = response.data.role;

            $("#role_form input[name=nombre]").val(datai.nombre);
            $("#role_form input[name=descripcion]").val(datai.descripcion);
            $("#role_form input[name=sueldo]").val(datai.sueldo);
            $("#role_form input[name=asistencia]").val(datai.asistencia);
            $("#role_form input[name=produccion]").val(datai.produccion);
            $("#role_form input[name=hora_extra]").val(datai.hora_extra);
            $("#role_form input[name=bono_extra]").val(datai.bono_extra);

            $("#role_form select[name=produccion_tipo]").val(datai.produccion_tipo);
            $("#role_form select[name=produccion_tipo]").change();
            $("#role_form select[name=area]").val(datai.area_id);
            $("#role_form select[name=area]").change();

            ///append id to form
            $('<input>').attr({
                type: 'hidden',
                value: datai.id,
                id: 'role_id',
                name: 'role_id'
            }).appendTo('#role_form');

        }).catch(function (error) {
        showAlert(error.response.data.message)
    });
});

$('#delete-area').confirm({
    title: 'Borrar Area',
    content: 'Esta seguro que desea borrar esta area?',
    buttons: {
        confirm: function () {
            let area_id = $("#area_form").data("record");
            axios.delete(api_url + 'api/area/' + area_id)
                .then(function (response) {
                    showSuccess(response.data.message, 2000)
                    toggle_area_list();
                }).catch(function (error) {
                showAlert(error.response.data.message)
            });
        },
        cancel: function () {
        }
    }
});

$('#delete-role').confirm({
    title: 'Borrar Cargo',
    content: 'Esta seguro que desea borrar este cargo?',
    buttons: {
        confirm: function () {
            let role_id = $("#role_form").data("record");
            axios.delete(api_url + 'api/role/' + role_id)
                .then(function (response) {
                    showSuccess(response.data.message, 2000)
                    toggle_role_list();
                }).catch(function (error) {
                showAlert(error.response.data.message)
            });
        },
        cancel: function () {
        }
    }
});