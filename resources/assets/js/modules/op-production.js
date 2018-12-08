// buttons
$("#to-prod-form").click(function () {
    getDateTimeProd();
    $('.sub-title').html('Cargar  Producción');
    $('#prod_form input[name=prod_id]').remove();
    toggle_prod_list(false);
});

$("#to-prod-list").click(function () {
    toggle_prod_list();
});


//functions
const toggle_prod_list = function (mode = true) {
    if (mode) {
        let main_date = $("#prod_date").val();
        reloadList('api/prod/all/' + main_date, '#prod-list');
        $("#prod-list-container").show();
        $("#prod-form").hide();
        $("#prod_form")[0].reset();
        $('.selectpickerTable').selectpicker('refresh');
        $('.selectpickerLine').empty();
        $('.selectpickerLine').selectpicker('refresh');
    } else {
        $("#prod-list-container").hide();
        $("#prod-form").show();
    }
}

const getDateTimeProd = function () {

    function format(x) {
        //if the day/month is smaller then 10 add a 0 in front of it (9->09)
        return (x < 10) ? '0' + x : x;
    }

    //get date
    let d = new Date(); // for now
    let y = d.getFullYear();
    let mo = d.getMonth() + 1;
    let day = d.getDate();

    ///hour
    let h1 = d.getHours(); // => 9
    let m1 = d.getMinutes(); // =>  30

    //main prod form date
    let main_date = $("#prod_date").val();
    $("#prod_form input[name=fecha]").val(main_date);
    $("#prod_form input[name=hora]").val(String(format(h1)) + ':' + String(format(m1)));

}

// Forms
$("#prod_form").submit(function (event) {
    const $form = $('#prod_form');
    axios.post(api_url + 'api/prod', $form.serialize())
        .then(function (response) {
            showSuccess(response.data.message, 2000)
            toggle_prod_list();
        }).catch(function (error) {
            showAlert(error.response.data.message)
        });
    event.preventDefault();
});

$('#prod-list').on('click-cell.bs.table', function (field, value, row, $element) {
    let main_date = $("#prod_date").val();
    $.confirm({
        title: 'Producción de ' + $element.cajas + ' cajas, a la hora:' + $element.hora,
        content: 'Desea eliminar este registro?',
        buttons: {
            confirm: function () {
                axios.delete(api_url + 'api/prod/' + $element.id)
                    .then(function (response) {
                        reloadList('api/prod/all/' + main_date, '#prod-list');
                    }).catch(function (error) {
                        showAlert(error.response.data.message)
                    })
            },
            cancel: function () {

            }
        }
    });

});
