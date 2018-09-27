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
            $('#asis_nombre').html(person.nombre+' '+person.apellido);
            $('#asis_cedula').html(person.cedula)
            $('#asis_tlf').html(person.telefono)
            $('#asis_email').html(person.email)
            $('#asis_fecha').html(info.fecha)
            $('#asis_turno').html(info.turno)

            if(info.table!=null)
            $('#asis_mesa').html(info.table.titulo)
            if(info.line!=null)
            $('#asis_linea').html(info.line.titulo)
            $('#asis_llegada').html(info.hora_entrada)
            if(info.hora_salida!=null)
            $('#asis_salida').html(info.hora_salida)
            $('#asis_nota').html(info.comentario)
            console.log(info)

        }).catch(function (error) {
        return false;
    });

})