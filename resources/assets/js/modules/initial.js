/**
 * login method
 */

$("#main_form").submit(function (event) {

    axios.post(api_url+'api/doLogin', {
        email: $("#email").val(),
        password: $("#password").val()
    }).then(function (response) {
        console.log(response);
        redirect(api_url+'home', false)
    }).catch(function (error) {
        console.log(error)
        alert(error.response.data.message)
    });
    event.preventDefault();
});