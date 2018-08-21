// buttons
$("#to-user-form").click(function () {
    $('.sub-title').html('Crear Usuario');
    toggle_user_list(false);
});

$("#to-user-list").click(function () {
    toggle_user_list();
});

//functions
const toggle_user_list = function (mode = true) {
    if (mode) {
        reloadUserList();
        $("#users-list-container").show();
        $("#user-form").hide();
        $("#user_form")[0].reset();
        $('#delete').hide();
    } else {
        $("#users-list-container").hide();
        $("#user-form").show();
    }
}

const reloadUserList = function () {
    axios.get(api_url + 'api/user/all')
        .then(function (response) {
            $('#users-list').bootstrapTable('removeAll').bootstrapTable('load', {
                data: response.data.list
            });
        }).catch(function (error) {
        showAlert(error.response.data.message)
    });
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