// buttons
$("#to-area-form").click(function () {
    $('.sub-title').html('Crear Area');
    toggle_area_list(false);
});

$("#to-area-list").click(function () {
    toggle_area_list();
});

//functions
const toggle_area_list = function (mode = true) {
    if (mode) {
        reloadList('api/area/all','#area-list');
        $("#area-list-container").show();
        $("#area-form").hide();
        $("#area_form")[0].reset();
        $('#delete-area').hide();
    } else {
        $("#area-list-container").hide();
        $("#area-form").show();
    }
}

// Forms
$("#area_form").submit(function (event) {
    const $form = $('#area_form');
    axios.post(api_url + 'api/area', $form.serialize())
        .then(function (response) {
            showSuccess(response.data.message, 2000)
            toggle_area_list();
        }).catch(function (error) {
        showAlert(error.response.data.message)
    });
    event.preventDefault();
});

//behavior
$('#area-list').on('click-cell.bs.table', function (field, value, row, $element) {
    toggle_area_list(false);
    $('#delete-area').show();
    $('#area_form').data('record',$element.id); //element id

    $('.sub-title').html('Editar Area');
    axios.get(api_url + 'api/area/' + $element.id)
        .then(function (response) {
            const datai = response.data.area;
            $("#area_form input[name=titulo]").val(datai.titulo);
            $("#area_form input[name=descripcion]").val(datai.descripcion);
          
            ///append id to form
            $('<input>').attr({
                type: 'hidden',
                value: datai.id,
                id:'area_id',
                name: 'area_id'
            }).appendTo('#area_form');

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
            axios.delete(api_url + 'api/area/'+area_id)
                .then(function (response) {
                    showSuccess(response.data.message, 2000)
                    toggle_area_list();
                }).catch(function (error) {
                showAlert(error.response.data.message)
            });
        },
        cancel:  function () {
        }
    }
});