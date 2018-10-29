// buttons
$("#to-bonus-form").click(function () {
    $('.sub-title').html('Nuevo Bono');
    $("#bonus_form [name=tipo]").removeAttr('selected');
    $('.selectpickerBene').empty();
    $('#bonus_form input[name=bonus_id]').remove();
    toggle_bonus_list(false);
});

$("#to-bonus-list").click(function () {
    toggle_bonus_list();
});

//functions
const toggle_bonus_list = function (mode = true) {
    if (mode) {
        reloadList('api/bonus/all', '#bonus-list');
        $("#bonus-list-container").show();
        $("#bonus-form").hide();
        $("#bonus_form")[0].reset();
        $('#bonus-table').hide();
    } else {
        $("#bonus-list-container").hide();
        $("#bonus-form").show();
        $('.selectpickerType').selectpicker('refresh');
        $('.selectpickerBene').selectpicker('refresh');

    }
}

$('#tipo').on('changed.bs.select', function (e, clickedIndex, isSelected, previousValue) {
    // do something...
    let value = String($(this).val());
    setbeneficiario(value);

});


//behavior
$('#bonus-list').on('click-cell.bs.table', function (field, value, row, $element) {
    toggle_bonus_list(false);
    $('#delete-bonus').show();
    $('#bonus_form').data('record', $element.id); //element id
    $('.sub-title').html('Editar Bono');
    axios.get(api_url + 'api/bonus/' + $element.id)
        .then(function (response) {
            const datai = response.data.bonus;
            $("#bonus_form input[name=titulo]").val(datai.titulo);
            $("#bonus_form input[name=fecha]").val(datai.fecha);
            $("#bonus_form input[name=monto]").val(datai.monto);

            $("#bonus_form [name=tipo]").removeAttr('selected');
            $("#bonus_form select[name=tipo]").val(datai.tipo);
            $('.selectpickerType').selectpicker('refresh');
            setbeneficiario(datai.tipo, datai.beneficiario)
            $('#beneficiario').on('refreshed.bs.select', function () {
                $('.selectpickerBene').selectpicker('val',datai.beneficiario);
            });

            ///append id to form
            $('<input>').attr({
                type: 'hidden',
                value: datai.id,
                id: 'bonus_id',
                name: 'bonus_id'
            }).appendTo('#bonus_form');

        }).catch(function (error) {
        showAlert(error.response.data.message)
    });
});

// Forms
$("#bonus_form").submit(function (event) {
    const $form = $('#bonus_form');
    axios.post(api_url + 'api/bonus', $form.serialize())
        .then(function (response) {
            showSuccess(response.data.message, 2000)
            toggle_bonus_list();
        }).catch(function (error) {
        showAlert(error.response.data.message)
    });
    event.preventDefault();
});

$('#delete-bonus').confirm({
    title: 'Borrar Bono',
    content: 'Esta seguro que desea borrar este Bono?',
    buttons: {
        confirm: function () {
            let bonus_id = $("#bonus_form").data("record");
            axios.delete(api_url + 'api/bonus/' + bonus_id)
                .then(function (response) {
                    showSuccess(response.data.message, 2000)
                    toggle_bonus_list();
                }).catch(function (error) {
                showAlert(error.response.data.message)
            });
        },
        cancel: function () {
        }
    }
});

let setbeneficiario = function (tipo, beneId = false) {
    let url = ''
    if (tipo === 'empleado') {
        url = 'api/query/person/all';
    } else if (tipo === 'cargo') {
        url = 'api/role/all';
    } else {
        url = 'api/area/all';
    }
    axios.get(api_url + url)
        .then(function (response) {
            let options = '';
            let data = response.data.list;
            let len = data.length;
            for (let i = 0; i < len; i++) {
                let selected = (beneId === data[i].id) ? ' selected' : '';
                options += '<option value=' + data[i].id + selected + '>' + data[i].nombre + '</option>';
            }
            $('.selectpickerBene').empty();
            $('.selectpickerBene').append(options);
            $('.selectpickerBene').selectpicker('refresh');
        }).catch(function (error) {
        showAlert(error.response.data.message)
    });
}