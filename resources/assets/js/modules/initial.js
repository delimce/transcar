/**
 * login method
 */

$("#main_form").submit(function (event) {

    axios.post(api_url+'api/doLogin', {
        user: $("#inputUser").val(),
        password: $("#inputPassword").val()
    }).then(function (response) {
        console.log(response);
        redirect(api_url+'home', false)
    }).catch(function (error) {
        showAlert(error.response.data.message)
    });
    event.preventDefault();
});

$("#menu-toggle").click(function(e) {
    e.preventDefault();
    $("#wrapper").toggleClass("toggled");
});