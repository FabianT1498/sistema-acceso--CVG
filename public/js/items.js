$(document).ready(function() {
  $('#group').change(function(event) {
    $('#sub_group').empty();
    var option = '<option>SubGrupo...</option>';
    $('#sub_group').append(option);
    loadSubGroups($('#group').val());
    changeDescription('group');
  });

  $('#sub_group').change(function(event) {
    $('#type').empty();
    var option = '<option>Tipo...</option>';
    $('#type').append(option);
    loadTypes($('#sub_group').val());
    changeDescription('sub_group');
  });

  $('#type').change(function(event) {
    changeDescription('type');
  });
  $('#presentation').change(function(event) {
    changeDescription('presentation');
  });
});

function changeDescription(type) {
  var description = '';
  if (type == 'group') {
    description = $('#group option:selected').text() + ' - ';
  } else if (type == 'sub_group') {
    description =
      $('#description')
        .val()
        .split(' - ')[0] +
      ' - ' +
      $('#sub_group option:selected').text() +
      ' - ';
  } else if (type == 'type') {
    description =
      $('#description')
        .val()
        .split(' - ')[0] +
      ' - ' +
      $('#description')
        .val()
        .split(' - ')[1] +
      ' - ' +
      $('#type option:selected').text() +
      ' - ';
  } else if (type == 'presentation') {
    description =
      $('#description')
        .val()
        .split(' - ')[0] +
      ' - ' +
      $('#description')
        .val()
        .split(' - ')[1] +
      ' - ' +
      $('#description')
        .val()
        .split(' - ')[2] +
      ' - ' +
      $('#presentation option:selected').text() +
      ':';
  }

  $('#description').val(description);
}

function loadSubGroups(group_id) {
  var url = baseUrl + '/grupo/' + group_id + '/cargarSubGrupos/';
  $.get(
    url,
    {},
    function(subGrupos) {
      $.each(subGrupos, function(indice, sub_grupo) {
        var option =
          "<option  value='" +
          sub_grupo.id +
          "' name='" +
          sub_grupo.id +
          "'>" +
          sub_grupo.name +
          '</option>';
        $('#sub_group').append(option);
      });
    },
    'json',
  );
}

function loadTypes(sub_grupo_id) {
  var url = baseUrl + '/sub_grupo/' + sub_grupo_id + '/cargarTipos/';
  $.get(
    url,
    {},
    function(tipos) {
      $.each(tipos, function(indice, tipo) {
        var option =
          "<option  value='" +
          tipo.id +
          "' name='" +
          tipo.id +
          "'>" +
          tipo.name +
          '</option>';
        $('#type').append(option);
      });
    },
    'json',
  );
}

$(document).on('click', '#check_trashed', function() {
  $('#check_trashed').val($('#check_trashed').val() == 1 ? 0 : 1);
});
