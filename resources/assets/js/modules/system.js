// buttons
$("#new-user").click(function (e) {

    $("#users-list-container").hide();
    $("#user-form").show();

});

// Forms
$("#user_form").submit(function (event) {
    const $form = $('#user_form');
    axios.post(api_url + 'api/user', $form.serialize())
        .then(function (response) {
            showSuccess(response.data.message, 2000)
            $('#user-form').modal("hide");
            $("#user_form")[0].reset();
        }).catch(function (error) {
        showAlert(error.response.data.message)
    });
    event.preventDefault();
});
