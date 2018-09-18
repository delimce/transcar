// buttons
$("#to-table-form").click(function () {
    $('.sub-title').html('Crear Mesa');
    $('#table_form input[name=table_id]').remove();
    toggle_table_list(false);
});

$("#to-table-list").click(function () {
    toggle_table_list();
});

$("#to-line-form").click(function () {
    $('.sub-title').html('Crear Línea');
    $('#line_form input[name=line_id]').remove();
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
        reloadList('api/line/all', '#line-list');
        $("#line-list-container").show();
        $("#line-form").hide();
        $("#line_form")[0].reset();
        $('#delete-line').hide();
    } else {
        $("#line-list-container").hide();
        $("#line-form").show();
        ///loading area list to select
    }
}

///reload select list
const reloadTableSelectBox = function () {
    axios.get(api_url + "api/query/table/all")
        .then(function (response) {
            let options = '<option value="">Seleccione</option>';
            let data = response.data.list;
            let len = data.length;
            for (let i = 0; i < len; i++) {
                options += '<option value=' + data[i].id + '>' + data[i].titulo + '</option>';
            }
            $('.selectpickerTable').empty();
            $('.selectpickerTable').append(options);
            $('.selectpickerTable').selectpicker('refresh');
            $('.selectpickerLine').selectpicker('refresh');
        }).catch(function (error) {
        showAlert(error.response.data.message)
    });
}

$('#mesa').on('changed.bs.select', function (e, clickedIndex, isSelected, previousValue) {
    // do something...
    let mesa = $(this).val()
    axios.get(api_url + "api/query/line/all/"+mesa)
        .then(function (response) {
            let options = '<option value="">Seleccione</option>';
            let data = response.data.list;
            let len = data.length;
            for (let i = 0; i < len; i++) {
                options += '<option value=' + data[i].id + '>' + data[i].titulo + '</option>';
            }
            $('.selectpickerLine').empty();
            $('.selectpickerLine').append(options);
            $('.selectpickerLine').selectpicker('refresh');
        }).catch(function (error) {
        showAlert(error.response.data.message)
    });

});

// Forms
$("#table_form").submit(function (event) {
    const $form = $('#table_form');
    axios.post(api_url + 'api/table', $form.serialize())
        .then(function (response) {
            showSuccess(response.data.message, 2000);
            reloadTableSelectBox();
            toggle_table_list();
        }).catch(function (error) {
        showAlert(error.response.data.message)
    });
    event.preventDefault();
});


$("#line_form").submit(function (event) {
    const $form = $('#line_form');
    axios.post(api_url + 'api/line', $form.serialize())
        .then(function (response) {
            showSuccess(response.data.message, 2000)
            toggle_line_list();
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

$('#line-list').on('click-cell.bs.table', function (field, value, row, $element) {
    toggle_line_list(false);
    $('#delete-line').show();
    $('#line_form').data('record', $element.id); //element id
    $('.sub-title').html('Editar Línea');
    axios.get(api_url + 'api/line/' + $element.id)
        .then(function (response) {
            const datai = response.data.line;
            $("#line_form input[name=titulo]").val(datai.titulo);
            $("#line_form input[name=descripcion]").val(datai.descripcion);
            $("#line_form select[name=mesa]").val(datai.mesa_id);
            $("#line_form select[name=mesa]").change();

            ///append id to form
            $('<input>').attr({
                type: 'hidden',
                value: datai.id,
                id: 'line_id',
                name: 'line_id'
            }).appendTo('#line_form');

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

$('#delete-line').confirm({
    title: 'Borrar Línea',
    content: 'Esta seguro que desea borrar esta Línea?',
    buttons: {
        confirm: function () {
            let line_id = $("#line_form").data("record");
            axios.delete(api_url + 'api/line/' + line_id)
                .then(function (response) {
                    showSuccess(response.data.message, 2000)
                    toggle_line_list();
                }).catch(function (error) {
                showAlert(error.response.data.message)
            });
        },
        cancel: function () {
        }
    }
});