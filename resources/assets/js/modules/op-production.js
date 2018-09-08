// buttons
$("#to-prod-form").click(function () {
    $('.sub-title').html('Cargar  Producción');
    getDateTimeProd();
    $('#prod_form input[name=prod_id]').remove();
    toggle_prod_list(false);
});

$("#to-prod-list").click(function () {
    toggle_prod_list();
});


//functions
const toggle_prod_list = function (mode = true) {
    if (mode) {
        reloadList('api/prod/all', '#prod-list');
        $("#prod-list-container").show();
        $("#prod-form").hide();
        $("#prod_form")[0].reset();
        $('#delete-prod').hide();
    } else {
        $("#prod-list-container").hide();
        $("#prod-form").show();
    }
}

const getDateTimeProd = function () {

    //get hour
    let d = new Date(); // for now
    //day
    console.log(d.getDate())
    let y = d.getFullYear();
    let mo = d.getMonth()+1;
    let day = d.getDate();
    mo = (mo < 10) ? "0" + String(mo) : String(mo);
    day = (day < 10) ? "0" + String(day) : String(day);

    ///hour
    let h1 = d.getHours(); // => 9
    let m1 = d.getMinutes(); // =>  30
    h1 = (h1 < 10) ? "0" + String(h1) : String(h1);
    m1 = (m1 < 10) ? "0" + String(m1) : String(m1);
    $('#my_hour').val(String(h1) + ':' + String(m1));

    $("#prod_form input[name=fecha]").val(String(y) + '-' + String(mo) + '-' + String(day));
    $("#prod_form input[name=hora]").val(String(h1) + ':' + String(m1));

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