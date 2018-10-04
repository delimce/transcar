// buttons
$("#to-user-form").click(function () {
    $('.sub-title').html('Crear Usuario');
    $('#user_form input[name=user_id]').remove();
    toggle_user_list(false);
});

$("#to-user-list").click(function () {
    toggle_user_list();
});

//functions
const toggle_user_list = function (mode = true) {
    if (mode) {
        reloadList('api/user/all','#users-list');
        $("#users-list-container").show();
        $("#user-form").hide();
        $("#user_form")[0].reset();
        $('#delete').hide();
    } else {
        $("#users-list-container").hide();
        $("#user-form").show();
    }
}

// Forms
$("#user_form").submit(function (event) {
    const $form = $('#user_form');
    axios.post(api_url + 'api/user', $form.serialize())
        .then(function (response) {
            showSuccess(response.data.message, 2000)
            toggle_user_list();
        }).catch(function (error) {
        showAlert(error.response.data.message)
    });
    event.preventDefault();
});

$("#config_form").submit(function (event) {
    const $form = $('#config_form');
    axios.put(api_url + 'api/config', $form.serialize())
        .then(function (response) {
            showSuccess(response.data.message, 2000)
        }).catch(function (error) {
            console.log(error)
        showAlert(error.response.data.message)
    });
    event.preventDefault();
});

//behavior
$('#users-list').on('click-cell.bs.table', function (field, value, row, $element) {
    toggle_user_list(false);
    $('#delete').show();
    $('#user_form').data('record',$element.id); //element id

    $('.sub-title').html('Editar Usuario');
    axios.get(api_url + 'api/user/' + $element.id)
        .then(function (response) {
            const datau = response.data.user;
            $("#user_form input[name=nombre]").val(datau.nombre);
            $("#user_form input[name=apellido]").val(datau.apellido);
            $("#user_form input[name=usuario]").val(datau.usuario);
            $("#user_form input[name=email]").val(datau.email);
            $("#user_form input[name=password]").val('nop4sswordchang3d');
            $("#user_form input[name=password2]").val('nop4sswordchang3d');
            $("#user_form select[name=profile]").val(datau.perfil_id);
            $("#user_form select[name=profile]").change();
            if (datau.activo == 1) {
                $("#user_form #activo").prop('checked', true);
            } else {
                $("#user_form #activo").prop('checked', false);
            }

            ///append id to form
            $('<input>').attr({
                type: 'hidden',
                value: datau.id,
                id:'user_id',
                name: 'user_id'
            }).appendTo('#user_form');

        }).catch(function (error) {
        showAlert(error.response.data.message)
    });
});

$('#delete').confirm({
    title: 'Borrar Usuario',
    content: 'Esta seguro que desea borrar este usuario?',
    buttons: {
        confirm: function () {
          let user_id = $("#user_form").data("record");
            axios.delete(api_url + 'api/user/'+user_id)
                .then(function (response) {
                    showSuccess(response.data.message, 2000)
                    toggle_user_list();
                }).catch(function (error) {
                showAlert(error.response.data.message)
            });
        },
        cancel:  function () {
        }
    }
});

///reload select list
const reloadBankSelectBox = function (bankId=false) {
    axios.get(api_url + "api/query/bank/all")
        .then(function (response) {
            let options = '<option value="">Seleccione</option>';
            let data = response.data.list;
            let len = data.length;
            for (let i = 0; i < len; i++) {
                let selected = (bankId === data[i].id)?' selected':'';
                options += '<option value=' + data[i].id + selected+'>' + data[i].nombre + '</option>';
            }
            $('.selectpickerBank').empty();
            $('.selectpickerBank').append(options);
            $('.selectpickerBank').selectpicker('refresh');
        }).catch(function (error) {
        showAlert(error.response.data.message)
    });
}
