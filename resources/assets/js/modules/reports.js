$(".det-appear").on("click", function() {
  let id = $(this).data("id");
  let date = $(this).data("date");
  let table = $(this).data("table");
  let data = {
    "person": id,
    "date":   date,
    "table":  table,
  };
  axios.put(api_url + 'api/query/appear/detail', data)
  .then(function(response) {
    let info = response.data.info;
    $('#appear-detail').modal('show');
    $("#appear-det2").html('');

    $.each( info, function( key, value ) {
      let appear='';
      let person = value.person;
      let table = (value.table != null)?value.table.titulo:"";
      let line = (value.line != null)?value.line.titulo:"";

      appear+='<div style="padding: 10px" class="row" id="person-id" data-id="">';
      appear+='<div class="col-sm-6">';
      appear+='<span class="appear-subtitle">Nombre:</span>&nbsp;'+String(person.nombre + ' ' + person.apellido).substr(0, 27)+'<br>';
      appear+='  <span class="appear-subtitle">Telefono:</span>&nbsp;'+person.telefono+'<br>';
      appear+='<span class="appear-subtitle">Fecha:</span>&nbsp;'+value.fecha+'<br>';
      appear+='<span class="appear-subtitle">Mesa:</span>&nbsp;'+table+'<br>';
      appear+=' <span class="appear-subtitle">hora inicio:</span>&nbsp;'+value.hora_entrada+'<br>';
      appear+=' <span class="appear-subtitle">Notas:</span>&nbsp;'+value.comentario+'<br>';
      appear+='</div>';

      appear+='<div class="col-sm-6">';
      appear+='<span class="appear-subtitle">Cedula:</span>&nbsp;'+person.cedula+'<br>';
      appear+='<span class="appear-subtitle">Email:</span>&nbsp;'+person.email+'<br>';
      appear+='<span class="appear-subtitle">Turno:</span>&nbsp;'+value.turno+'<br>';
      appear+='<span class="appear-subtitle">Linea:</span>&nbsp;'+line+'<br>';
      appear+='<span class="appear-subtitle">hora Fin:</span>&nbsp;'+value.hora_salida+'<br>';
      appear+='';
      appear+='</div>';

      appear+='</div>';
      appear+='<hr>';

      $("#appear-det2").append(appear);
    });

  }).catch(function(error) {
    return false;
  });

});

$('#month').on('changed.bs.select', function(e, clickedIndex, isSelected, previousValue) {
  // do something...
  let month = $(this).val();
  axios.get(api_url + "api/query/daysOfMonth/" + month)
  .then(function(response) {
    $('#last-day').html(response.data.days);
  }).catch(function(error) {
    showAlert(error.response.data.message);
  });

});

$('#report-det').on('click', function() {
  let data = {
    "month":    $('#month').val(),
    "quincena": $("input[name='quincena']:checked").val()
  };
  axios.post(api_url + "api/reports/nomina", data)
  .then(function(response) {
    $("#nomina-det").html(response.data);
  }).catch(function(error) {
    showAlert(error.response.data.message);
  });

});

$('#report-txt').on('click', function() {
  let data = {
    "month":    $('#month').val(),
    "quincena": $("input[name='quincena']:checked").val()
  };
  axios({
          url:          api_url + "api/reports/file",
          method:       'POST',
          data:         data,
          responseType: 'blob', // important
        }).then((response) => {
    const url = window.URL.createObjectURL(new Blob([response.data]));
    const link = document.createElement('a');
    link.href = url;
    link.setAttribute('download', 'file.txt');
    document.body.appendChild(link);
    link.click();
  }).catch(function(error) {
    showAlert(error.response.data.message);
  });

});

$('#log-list').on('click-cell.bs.table', function(field, value, row, $element) {
  let logId = $element.id;
  axios.get(api_url + "api/reports/logs/detail/" + logId)
  .then(function(response) {
    let info = response.data.detail;
    console.log(info);
    $("#log-detail-name").html(info.usuario);
    $("#log-detail-ip").html(info.ip);
    $("#log-detail-date").html(info.fecha.date);
    $("#log-detail-type").html(info.tipo);
    $("#log-detail-client").html(info.cliente);
    $("#log-detail-detail").html(info.detalle);
    $("#log-detail").modal('show');

  }).catch(function(error) {
    showAlert(error.response.data.message);
  });
});

