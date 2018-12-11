// buttons
$("#to-person-form").click(function() {
  $('.sub-title').html('Nuevo Empleado');
  $('#person_form input[name=person_id]').remove();
  $('#reason').hide();
  $("#person_form select[name=area]").removeAttr('selected');
  $("#person_form select[name=cargo]").removeAttr('selected');
  $('.selectpickerRole').empty();
  $('.selectpickerRole').selectpicker('refresh');

  $("#person_form select[name=banco]").removeAttr('selected');
  $("#person_form select[name=tipo_doc]").removeAttr('selected');
  toggle_person_list(false);
});

$("#to-person-list").click(function() {
  toggle_person_list();
});

//functions
const toggle_person_list = function(mode = true) {
  if (mode) {
    reloadList('api/person/all', '#person-list');
    $("#person-list-container").show();
    $("#person-form").hide();
    $("#person_form")[0].reset();
    $('#person-table').hide();
    $("#role-location").hide();

    //area & role
    $("#person_form select[name=area]").val("");
    $('.selectpickerArea').selectpicker('refresh');
    $("#person_form select[name=cargo]").val("");
    $('.selectpickerRole').selectpicker('refresh');

    //table & line
    $("#person_form select[name=mesa]").val("");
    $('.selectpickerTable').selectpicker('refresh');
    $("#person_form select[name=linea]").val("");
    $('.selectpickerLine').selectpicker('refresh');
  } else {
    $("#person-list-container").hide();
    $("#person-form").show();
  }
};

///reload select list
const reloadRoleSelectBox = function(areaId = false, roleId = false) {
  axios.get(api_url + "api/query/role/all/" + areaId)
  .then(function(response) {
    let options = '';
    let data = response.data.list;
    let len = data.length;
    for (let i = 0; i < len; i++) {
      let selected = (roleId === data[i].id) ? ' selected' : '';
      options += '<option value=' + data[i].id + selected + '>' + data[i].nombre + '</option>';
    }
    $('.selectpickerRole').empty();
    $('.selectpickerRole').append(options);
    $('.selectpickerRole').selectpicker('refresh');
  }).catch(function(error) {
    showAlert(error.response.data.message);
  });
};

// Forms
$("#person_form").submit(function(event) {
  const $form = $('#person_form');
  axios.post(api_url + 'api/person', $form.serialize())
  .then(function(response) {
    showSuccess(response.data.message, 2000);
    toggle_person_list();
  }).catch(function(error) {
    showAlert(error.response.data.message);
  });
  event.preventDefault();
});

//behavior
$('#person-list').on('click-cell.bs.table', function(field, value, row, $element) {
  toggle_person_list(false);
  $('#delete-person').show();
  $('#person_form').data('record', $element.id); //element id
  $('.sub-title').html('Editar Empleado');
  axios.get(api_url + 'api/person/' + $element.id)
  .then(function(response) {
    const datai = response.data.person;
    console.log(datai);
    $("#person_form input[name=nombre]").val(datai.nombre);
    $("#person_form input[name=apellido]").val(datai.apellido);
    $("#person_form input[name=cedula]").val(datai.cedula);
    $("#person_form input[name=codigo]").val(datai.codigo);
    $("#person_form input[name=fecha_nac]").val(datai.fecha_nac);
    $("#person_form input[name=fecha_ingreso]").val(datai.fecha_ingreso);
    $("#person_form input[name=email]").val(datai.email);
    $("#person_form input[name=telefono]").val(datai.telefono);
    $("#person_form input[name=reason]").val(datai.razon_inactivo);
    $("#person_form select[name=sexo]").val(datai.sexo);

    ///layoff form
    $("#layoff_form input[name=nombre]").val(datai.nombre + ' ' + datai.apellido);

    $("#person_form select[name=area]").removeAttr('selected');
    $("#person_form select[name=area]").val(datai.area_id);
    $('.selectpickerArea').selectpicker('refresh');

    ////set role
    reloadRoleSelectBox(datai.area_id, datai.cargo_id);
    $('#cargo').on('refreshed.bs.select', function() {
      $('.selectpickerRole').selectpicker('val', datai.cargo_id);
    });

    $("#person_form input[name=titular]").val(datai.titular);
    $("#person_form input[name=account]").val(datai.cuenta_bancaria);

    try {
      let typeDoc = String(datai.titular_doc).substring(0, 1);
      let doc = String(datai.titular_doc).substring(1, datai.titular_doc.length);
      $("#person_form input[name=titular_doc]").val(doc);
      $("#person_form select[name=tipo_doc]").val(typeDoc);
      $('.selectpickerDoc').selectpicker('refresh');
    } catch (e) {
      console.log(e);
    }


    $("#person_form select[name=banco]").val(datai.banco_id);
    $('.selectpickerBank').selectpicker('refresh');

    if (datai.activo == 1) {
      $("#person_form #activo").prop('checked', true);
      $('#reason').hide();
    } else {
      $("#person_form #activo").prop('checked', false);
      $('#reason').show();
    }

    ///table and line
    console.log(datai.mesa_id);
    if (datai.mesa_id !== 0) {
      $("#role-location").show();
      $("#person_form select[name=mesa]").val(datai.mesa_id);
      $('.selectpickerTable').selectpicker('refresh');
      if (datai.linea_id !== 0) {
        ///set linea
        getLineByTable(datai.mesa_id, datai.linea_id);
        $('#linea').on('refreshed.bs.select', function() {
          $('.selectpickerLine').selectpicker('val', datai.linea_id);
        });
      }
    } else {
      $("#role-location").hide();
    }

    ///append id to form
    $('<input>').attr({
                        type:  'hidden',
                        value: datai.id,
                        id:    'person_id',
                        name:  'person_id'
                      }).appendTo('#person_form');

  }).catch(function(error) {
    showAlert(error.response.data.message);
  });
});

$('#area').on('changed.bs.select', function(e, clickedIndex, isSelected, previousValue) {
  // do something...
  let area = $(this).val();
  axios.get(api_url + "api/query/role/all/" + area)
  .then(function(response) {
    let options = '';
    let data = response.data.list;
    let len = data.length;
    for (let i = 0; i < len; i++) {
      options += '<option value=' + data[i].id + '>' + data[i].nombre + '</option>';
    }
    $('.selectpickerRole').empty();
    $('.selectpickerRole').append(options);
    $('.selectpickerRole').selectpicker('refresh');
    reloadPersonList('area', area);
  }).catch(function(error) {
    showAlert(error.response.data.message);
  });

});

$('#cargo').on('changed.bs.select', function(e, clickedIndex, isSelected, previousValue) {
  // do something...
  let cargo = $(this).val();
  axios.get(api_url + "api/role/" + cargo)
  .then(function(response) {
    if (response.data.role.produccion_tipo !== "total" && response.data.role.produccion_tipo !== "") {
      $("#role-location").show();
    } else {
      $("#role-location").hide();
    }
  }).catch(function(error) {
    showAlert(error.response.data.message);
  });

});

$("#activo").change(function() {
  if (!this.checked) {
    //Do stuff
    $('#reason').show();
    //  $('#reason').val('');
  } else {
    $('#reason').hide();
  }
});

$('#delete-person').on('click', function() {
  $("#layoff-actions").modal("show");
});


$("#layoff_form").submit(function(event) {
  event.preventDefault();
  let person_id = $("#person_form").data("record");
  $('<input>').attr({
                      type:  'hidden',
                      value: person_id,
                      id:    'person_id',
                      name:  'person_id'
                    }).appendTo('#layoff_form');
  let data_form = $('#layoff_form').serialize();
  axios.put(api_url + 'api/layoff', data_form)
  .then(function(response) {
    $("#layoff-actions").modal("hide");
    showSuccess(response.data.message, 2000);
    toggle_person_list();
  }).catch(function(error) {
    console.log(error);
    showAlert(error.response.data.message);
  });
});


$('#layoff-list').on('click-cell.bs.table', function(field, value, row, $element) {
  let layoffId = $element.id;
  axios.get(api_url + "api/layoff/" + layoffId)
  .then(function(response) {

    let info = response.data.info;
    $("#layoff-name").html(info.nombre);
    $("#layoff-role").html(info.cargo);
    $("#layoff-subject").html(info.motivo);
    $("#layoff-date").html(info.fecha);
    $("#restore-ly").data("id", info.id);
    $("#layoff-reverse").modal('show');

  }).catch(function(error) {
    showAlert(error.response.data.message);
  });
});


$("#restore-person").on("click", function() {
  let id = $("#restore-ly").data("id");
  let data = {
    "layoff_id": id
  };
  axios.put(api_url + "api/layoff/restore/", data)
  .then(function(response) {
    showSuccess(response.data.message, 2000);
    $("#layoff-reverse").modal('hide');
    reloadList('api/layoff/all', '#layoff-list');
  }).catch(function(error) {
    showAlert(error.response.data.message);
  });
});
