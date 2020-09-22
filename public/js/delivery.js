$(document).ready(function() {
  $(document).on('keyup', '.quantity', function() {
    updating_money(this.id.split('_')[1], 'none');
  });

  function updating_money(id, type) {
    var totalLocal = 0.0;
    var totalForeign = 0.0;
    var total_cost_local_money =
      Number($('#quantity_' + id).val()) *
      Number($('#unit_cost_local_money_' + id).val());

    $('#total_cost_local_money_' + id).text(total_cost_local_money);
    if (type == 'local') {
      var unit_cost_foreign_money =
        Number($('#unit_cost_local_money_' + id).val()) /
        Number($('#currency_value').val());
      $('#unit_cost_foreign_money_' + id).val(unit_cost_foreign_money);
    } else {
      var unit_cost_local_money =
        Number($('#unit_cost_foreign_money_' + id).val()) *
        Number($('#currency_value').val());
      $('#unit_cost_local_money_' + id).val(unit_cost_local_money);
    }
    var total_cost_foreign_money =
      Number($('#unit_cost_foreign_money_' + id).val()) *
      Number($('#quantity_' + id).val());
    $('#total_cost_foreign_money_' + id).text(total_cost_foreign_money);

    $('.total-local').each(function(index) {
      totalLocal += Number($(this).text());
      console.log(totalLocal);
    });

    $('.total-foreign').each(function(index) {
      totalForeign += Number($(this).text());
      console.log(totalForeign);
    });

    $('#total_local_money').text('Bs ' + totalLocal);
    $('#total_foreign_moeny').text('$ ' + totalForeign);
  }

  $(document).on('keyup', '.money', function() {
    if (this.id != 'currency_value') {
      //alert(cadena.indexOf("local"));
      if (
        $(this)
          .attr('class')
          .indexOf('local') > -1
      ) {
        updating_money(this.id.split('_')[4], 'local');
      } else {
        updating_money(this.id.split('_')[4], 'foreign');
      }
    } else {
      $('.total-local').each(function(index) {
        updating_money(this.id.split('_')[4]);
      });
    }
  });

  $(document).on('change', '.item-select', function() {
    id_row = this.id.split('_')[1];
    $('#stock_' + id_row).val('');
    $('#quantity_' + id_row).val('');

    if (
      $(this)
        .children('option:selected')
        .val()
    )
      actualizaStock(
        $(this)
          .children('option:selected')
          .val(),
        this.id.split('_')[1],
      );
  });

  $(document).on('input', '.quantity-stock', function() {
    id_row = this.id.split('_')[1];

    var value = $(this).val();

    if (value !== '' && value.indexOf('.') === -1) {
      $(this).val(Math.max(Math.min(value, $('#stock_' + id_row).val()), 1));
    }
  });

  function actualizaStock(id_item, id_row) {
    var url = baseUrl + '/item-stock/' + id_item + '/';
    var location = $('#location').val();

    $.ajax({
      type: 'GET',
      url: url,
      data: { location_id: location },

      beforeSend: function() {
        console.log('Soy el beforeSend');
      },
      success: function(res) {
        $('#stock_' + id_row).val(res.data.sum);
        console.log(res);
      },
      error: function(res) {
        console.log('Soy el error');
      },
      complete: function() {
        console.log('Soy el complete');
      },
    });
  }

  $.count = 1;
  function dynamic_field(number) {
    html = '<tr class = "tr-table">';
    html +=
      '<td width="50%"><select id="item_' +
      number +
      '" class="browser-default item-select" name="item[]" required ></td>';
    html +=
      '<td width="15%"><input id="stock_' +
      number +
      '" class="browser-default item-select" disabled> </td>';
    html +=
      '<td width="35%"><input type="text" placeholder="Ingrese la cantidad" id="quantity_' +
      number +
      '" name="quantity[]" class="form-control quantity quantity-stock" required/></td>';
    if (number > 1) {
      html +=
        '<td><button type="button" name="remove" id="" class="btn btn-danger red remove">X</button></td></tr>';
      $('tbody').append(html);
      $('#send').attr('disabled', false);
    }
  }

  $(document).on('change', '#location', function() {
    $.count = 1;

    $('.tr-table').remove();
  });

  $(document).on('click', '#add', function() {
    if ($('#location').val() == '') {
      alert('Por favor, seleccione un almacen.');
    } else {
      $.count++;
      dynamic_field($.count);
      loadItems($.count);
      $(function() {
        const options = {
          translation: {
            '0': { pattern: /\d/ },
            '1': { pattern: /[1-9]/ },
            '9': { pattern: /\d/, optional: true },
            '#': { pattern: /\d/, recursive: true },
            C: { pattern: /[VE]/, fallback: 'V' },
          },
        };

        $('.money').mask('###.00', { reverse: true });
        $('.quantity').mask('19999999999', options);
      });
    }
  });

  $(document).on('click', '.remove', function() {
    $.count--;
    $(this)
      .closest('tr')
      .remove();
    if ($.count <= 1) {
      $('#send').attr('disabled', true);
    }
  });

  function loadItems(number) {
    var url = baseUrl + '/entregas-items';
    var location = $('#location').val();
    $.get(
      url,
      {
        location: location,
      },
      function(lstItems) {
        var option = "<option value=''>Seleccione... </option>";
        $('#item_' + number).append(option);
        $.each(lstItems, function(indice, item) {
          var option =
            "<option value='" +
            item.id +
            "'>" +
            item.description +
            ' (' +
            item.id +
            ')</option>';
          $('#item_' + number).append(option);
        });
      },
      'json',
    );
  }

  cargarRegistrosImprimir();
});

$(document).on('change', '#chkAll', function(event) {
  let checks = $('.chkImp');
  let checkAll = this.checked;
  $.each(checks, function(indice, check) {
    $(check).prop('checked', checkAll);
  });
  cargarRegistrosImprimir();
});

$('.chkImp').on('change', function(event) {
  var check = this.checked;
  if(!check){
    $("#chkAll").prop("checked", false);
  }
  cargarRegistrosImprimir();
});

function cargarRegistrosImprimir() {
  var checks = $('.chkImp');
  var arrChecks = [];
  $.each(checks, function(indice, check) {
    if (check.checked) {
      var id = $(check).data('reg');
      arrChecks.push(parseInt(id));
    }
  });
  $('#chkRegistrosImprimir').val(JSON.stringify(arrChecks));
}

$(document).on('change', '#select_group', function(event) {
  $('#group_id').val(
    $(this)
      .find('option:selected')
      .val(),
  );
});

$(document).on('click', '#check_trashed', function() {
  $('#check_trashed').val($('#check_trashed').val() == 1 ? 0 : 1);
});
