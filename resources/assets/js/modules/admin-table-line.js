// buttons
$("#to-table-form").click(function () {
    $('.sub-title').html('Crear Mesa');
    toggle_table_list(false);
});

$("#to-table-list").click(function () {
    toggle_table_list();
});

$("#to-line-form").click(function () {
    $('.sub-title').html('Crear Línea');
    toggle_line_list(false);
});

$("#to-line-list").click(function () {
    toggle_line_list();
});

//functions
const toggle_table_list = function (mode = true) {
    if (mode) {
        reloadList('api/table/all', '#table-list');
        $("#table-list-container").show();
        $("#table-form").hide();
        $("#table_form")[0].reset();
        $('#delete-table').hide();
    } else {
        $("#table-list-container").hide();
        $("#table-form").show();
    }
}

const toggle_line_list = function (mode = true) {
    if (mode) {
        reloadList('api/line/all', '#role-list');
        $("#line-list-container").show();
        $("#line-form").hide();
        $("#line_form")[0].reset();
        $('#delete-line').hide();
    } else {
        $("#line-list-container").hide();
        $("#line-form").show();
        ///loading area list to select
        // reloadAreaSelectBox()
    }
}

// Forms
$("#table_form").submit(function (event) {
    const $form = $('#table_form');
    axios.post(api_url + 'api/table', $form.serialize())
        .then(function (response) {
            showSuccess(response.data.message, 2000)
            toggle_table_list();
        }).catch(function (error) {
        showAlert(error.response.data.message)
    });
    event.preventDefault();
});

//behavior
$('#table-list').on('click-cell.bs.table', function (field, value, row, $element) {
    toggle_table_list(false);
    $('#delete-table').show();
    $('#table_form').data('record', $element.id); //element id
    // reloadAreaSelectBox()
    $('.sub-title').html('Editar Mesa');
    axios.get(api_url + 'api/table/' + $element.id)
        .then(function (response) {
            const datai = response.data.table;
            $("#table_form input[name=titulo]").val(datai.titulo);
            $("#table_form input[name=ubicacion]").val(datai.ubicacion);

            ///append id to form
            $('<input>').attr({
                type: 'hidden',
                value: datai.id,
                id: 'table_id',
                name: 'table_id'
            }).appendTo('#table_form');

        }).catch(function (error) {
        showAlert(error.response.data.message)
    });
});

$('#delete-table').confirm({
    title: 'Borrar Mesa',
    content: 'Esta seguro que desea borrar esta Mesa?',
    buttons: {
        confirm: function () {
            let role_id = $("#table_form").data("record");
            axios.delete(api_url + 'api/table/' + role_id)
                .then(function (response) {
                    showSuccess(response.data.message, 2000)
                    toggle_table_list();
                }).catch(function (error) {
                showAlert(error.response.data.message)
            });
        },
        cancel: function () {
        }
    }
});