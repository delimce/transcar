/**
 * redirect to view
 * @param url
 * @param back
 */
const redirect = function (url, back = true) {
    if (back)
        window.location = url
    else location.replace(url)
};

/**
 * notify methods alert
 * @param message
 */
const showAlert = function (message) {
    $.notify({
        // options
        icon: 'fas fa-exclamation-circle',
        title: 'Error:',
        message: message
    }, {
        // settings
        type: 'danger',
        spacing: 10,
        delay: 2000,
        placement: {
            from: "top",
            align: "right"
        },
    });
}

const showInfo = function (message) {
    $.notify({
        // options
        icon: 'fas fa-question-circle',
        title: 'Información:',
        message: message
    }, {
        // settings
        type: 'info',
        spacing: 10,
        delay: 3500,
        placement: {
            from: "bottom",
            align: "right"
        },
    });
}

const showSuccess = function (message, time = false) {
    $.notify({
        // options
        icon: 'fas fa-check-circle',
        message: message
    }, {
        // settings
        type: 'success',
        spacing: 10,
        delay: (!time) ? 1500 : time,
        placement: {
            from: "top",
            align: "center"
        },
    });
}

const reloadList = function (url,list_id) {
    axios.get(api_url + url)
        .then(function (response) {
            $(list_id).bootstrapTable('removeAll').bootstrapTable('load', {
                data: response.data.list
            });
        }).catch(function (error) {
        showAlert(error.response.data.message)
    });
}