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

const findOnTableByNames = function(inputId,tableId,index) {
    // Declare variables 
  let input, filter, table, tr, td, i, txtValue;
  input = document.getElementById(inputId);
  filter = input.value.toUpperCase();
  table = document.getElementById(tableId);
  tr = table.getElementsByTagName("tr");

  // Loop through all table rows, and hide those who don't match the search query
  for (i = 0; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[index];
    if (td) {
      txtValue = td.textContent || td.innerText;
      if (txtValue.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      } else {
        tr[i].style.display = "none";
      }
    } 
  }
}