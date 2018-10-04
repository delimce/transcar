$(".det-appear").on("click", function () {
    let id = $(this).data("id");
    let date = $(this).data("date");
    let data = {
        "person": id,
        "date": date,
    }
    axios.put(api_url + 'api/query/appear/detail', data)
        .then(function (response) {
            let info = response.data.info;
            $('#appear-detail').modal('show');
            let person = info.person;
            $('#asis_linea').html('')
            $('#asis_salida').html('')
            $('#asis_nombre').html(person.nombre + ' ' + person.apellido);
            $('#asis_cedula').html(person.cedula)
            $('#asis_tlf').html(person.telefono)
            $('#asis_email').html(person.email)
            $('#asis_fecha').html(info.fecha)
            $('#asis_turno').html(info.turno)

            if (info.table != null)
                $('#asis_mesa').html(info.table.titulo)
            if (info.line != null)
                $('#asis_linea').html(info.line.titulo)
            $('#asis_llegada').html(info.hora_entrada)
            if (info.hora_salida != null)
                $('#asis_salida').html(info.hora_salida)
            $('#asis_nota').html(info.comentario)
            console.log(info)

        }).catch(function (error) {
            return false;
        });

})

$('#month').on('changed.bs.select', function (e, clickedIndex, isSelected, previousValue) {
    // do something...
    let month = $(this).val()
    axios.get(api_url + "api/query/daysOfMonth/" + month)
        .then(function (response) {
            $('#last-day').html(response.data.days)
        }).catch(function (error) {
            showAlert(error.response.data.message)
        });

});

$('#report-det').on('click', function () {
    let data = {
        "month": $('#month').val(),
        "quincena": $("input[name='quincena']:checked").val()
    }
    axios.post(api_url + "api/reports/nomina", data)
        .then(function (response) {
            $("#nomina-det").html(response.data)
        }).catch(function (error) {
            showAlert(error.response.data.message)
        });

})

$('#report-txt').on('click', function () {
    let data = {
        "month": $('#month').val(),
        "quincena": $("input[name='quincena']:checked").val()
    }

    axios({
        url: api_url + "api/reports/file",
        method: 'POST',
        data: data,
        responseType: 'blob', // important
    }).then((response) => {
        const url = window.URL.createObjectURL(new Blob([response.data]));
        const link = document.createElement('a');
        link.href = url;
        link.setAttribute('download', 'file.txt');
        document.body.appendChild(link);
        link.click();
    }).catch(function (error) {
        showAlert(error.response.data.message)
    });

})

