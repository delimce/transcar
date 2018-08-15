/**
 * login method
 */

$("#main_form").submit(function (event) {

    axios.post(api_url + 'api/doLogin', {
        user: $("#inputUser").val(),
        password: $("#inputPassword").val()
    }).then(function (response) {
        console.log(response);
        redirect(api_url + 'home', false)
    }).catch(function (error) {
        showAlert(error.response.data.message)
    });
    event.preventDefault();
});

window.onload = function () {
    $("#wrapper").toggleClass("toggled");
}

$("#menu-toggle").click(function (e) {
    e.preventDefault();
    $("#wrapper").toggleClass("toggled");
});


$('#form_pass').submit(function (event) {
    // Get the form instance
    const $form = $('#form_pass');
    axios.put(api_url + 'api/user/password', $form.serialize())
        .then(function (response) {
            showSuccess(response.data.message, 2000)
        }).catch(function (error) {
        showAlert(error.response.data.message);
    });
    event.preventDefault();
})