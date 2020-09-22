$(document).ready(function() {
  if ($('#create').val() === $('#vista').val()) {
    $('#description').trigger('focus');
  }
  cargarRegistrosImprimir();
});

/*NUEVO*/
$('#chk_all').on('change', function(event) {
  $checked = this.checked ? 'checked' : '';
  var $checks = $('.chk_item');
  $.each($checks, function(indice, check) {
    $(check).prop('checked', $checked);
  });
});

/* EDITAR */
$('.btn-editar').on('click', function(event) {
  var id = this.id.split('_')[1];
  $('#areaNote_' + id).toggle(300);
  $('#note_' + id).trigger('focus');
});

$('.btn-actualizar').on('click', function(event) {
  var id = this.id.split('_')[1];
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('[name="csrf-token"]').attr('content'),
    },
  });
  var strUrl = $('#strUrl').val();
  $.ajax({
    url: strUrl,
    type: 'POST',
    data: {
      id: id,
      quantity_inventory: $('#quantity_stock_' + id).val(),
      note: $('#note_' + id).val(),
    },
    success: function(respuesta) {
      $('#message-success-' + id).text($('#reg_updated').val());
    },
  });
});

$('.btn-asignar-stock').on('click', function(event) {
  var id = this.id.split('_')[1];
  $('#quantity_stock_' + id).val($('#quantity_' + id).text());
  $('#quantity_stock_' + id).trigger('focus');
});

$('.checkImprimir').on('change', function(event) {
  cargarRegistrosImprimir();
});

function cargarRegistrosImprimir() {
  var checks = $('.checkImprimir');
  var arrChecks = [];
  $.each(checks, function(indice, check) {
    if (this.checked) {
      var id = check.id.split('-')[1];
      arrChecks.push(parseInt(id));
    }
  });
  $('#chkRegistrosImprimir').val(JSON.stringify(arrChecks));
}

$(document).on('keyup', '.quantity-inventory', function() {
  var quantity_inventories = $('.quantity-inventory');
  var sum = 0;
  $.each(quantity_inventories, function(indice, quantity_inventory) {
    sum += Number($('#' + quantity_inventory.id).val());
  });
  $('#total_inventory').text(sum);
});

$(document).on('change', '#location', function() {
  $('.tr-table').remove();
  var location_id = $('#location').val();
  printItems(location_id);
});

function printItems(location) {
  $.ajax({
    type: 'GET',
    url: baseUrl + '/inventory-items',
    data: { location_id: location },

    beforeSend: function() {
      console.log('Soy el beforeSend');
    },
    success: function(res) {
      let data = res.data;
      data.forEach(item => {
        console.log(item.group);
        html = '<tr class = "tr-table">';
        html +=
          '<td class="center"><input type="checkbox" value="' +
          item.id +
          '" id="check_' +
          item.id +
          '"  name="check[]" class="chk_item"> </td>';
        html +=
          '<td> <label for="check_' +
          item.id +
          '" style="cursor: pointer;" for="">' +
          ' - (' +
          item.description +
          ') </label></td> </tr>';
        //+ item.group + ' - '+ item.sub_group + ' - ' + item.type + ' - ' + item.presentation
        $('tbody').append(html);
      });
    },
    error: function(res) {
      console.log('Soy el error');
    },
    complete: function() {
      console.log('Soy el complete');
    },
  });
}
$(document).on('click', '#check_trashed', function() {
  $('#check_trashed').val($('#check_trashed').val() == 1 ? 0 : 1);
});
