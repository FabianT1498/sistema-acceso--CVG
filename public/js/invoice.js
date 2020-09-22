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

  $('.money').mask('000.000.000.000.000,00', { reverse: true });
  $('.quantity').mask('19999999999', options);
});

$(document).ready(function() {
  $(document).on('keyup', '.quantity', function() {
    updating_money(this.id.split('_')[1], 'none');
  });

  function updating_money(id, type) {
    const options = { style: 'currency', currency: 'USD' };
    const numberFormat = new Intl.NumberFormat('de-DE', options);
    var totalLocal = 0.0;
    var totalForeign = 0.0;
    if (type == 'local') {
      var unit_cost_foreign_money =
        Number(
            reverseFormatNumber($('#unit_cost_local_money_' + id).val(), 'de'),
        ) / Number(reverseFormatNumber($('#currency_value').val(), 'de'));
      $('#unit_cost_foreign_money_' + id).val(
        numberFormat.format(unit_cost_foreign_money).replace('$', ''),
      );
      //console.log(reverseFormatNumber($("#unit_cost_local_money_" + id).val(),'de'));
    } else {
      var unit_cost_local_money =
        Number(
          reverseFormatNumber($('#unit_cost_foreign_money_' + id).val(), 'de'),
        ) * Number(reverseFormatNumber($('#currency_value').val(), 'de'));
      $('#unit_cost_local_money_' + id).val(
        numberFormat.format(unit_cost_local_money).replace('$', ''),
      );
    }
    var total_cost_local_money =
      Number($('#quantity_' + id).val()) *
      Number(
        reverseFormatNumber($('#unit_cost_local_money_' + id).val(), 'de'),
      );
    $('#total_cost_local_money_' + id).text(
      numberFormat.format(total_cost_local_money).replace('$', ''),
    );
    var total_cost_foreign_money =
      Number(
        reverseFormatNumber($('#unit_cost_foreign_money_' + id).val(), 'de'),
      ) * Number($('#quantity_' + id).val());
    $('#total_cost_foreign_money_' + id).text(
      numberFormat.format(total_cost_foreign_money).replace('$', ''),
    );

    $('.total-local').each(function(index) {
      totalLocal += ($(this).text().length > 0) ? parseFloat(reverseFormatNumber($(this).text(), 'de')) : 0;
      console.log("totallocal: " + totalLocal);
    });

    $('.total-foreign').each(function(index) {
      totalForeign += ($(this).text().length > 0) ? parseFloat(reverseFormatNumber($(this).text(), 'de')) : 0;
      console.log("Total foreing: " + totalForeign);
    });

    $('#total_local_money').text(
      'Bs ' + numberFormat.format(totalLocal).replace('$', ''),
    );
    $('#total_foreign_moeny').text(
      '$ ' + numberFormat.format(totalForeign).replace('$', ''),
    );
  }
  function reverseFormatNumber(val, locale) {
    var group = new Intl.NumberFormat(locale).format(1111).replace(/1/g, '');
    var decimal = new Intl.NumberFormat(locale).format(1.1).replace(/1/g, '');
    var reversedVal = val.replace(new RegExp('\\' + group, 'g'), '');
    reversedVal = reversedVal.replace(new RegExp('\\' + decimal, 'g'), '.');
    return Number.isNaN(reversedVal) ? 0 : reversedVal;
  }

  $(document).on('keyup', '.money', function() {
    if (this.id != 'currency_value') {
      //alert(cadena.indexOf("local"));
      if (
        $(this).attr('class').indexOf('local') > -1) {
        updating_money(this.id.split('_')[4], 'local');
      } else {
        updating_money(this.id.split('_')[4], 'foreign');
      }
      //this.id.split("_")[4]
      //si es el local que se cambia

      //if($(this).attr('class').indexOf("local-money") > -1)
      //	alert($(this).attr('class'));

      //	}
      //      else
      //    {
    } else {
      $('.total-local').each(function(index) {
        updating_money(this.id.split('_')[4]);
      });
    }
  });

  $.count = 1;
  function dynamic_field(number) {
    html = '<tr>';
    html +=
      '<td width="12%" class="center"><select id="item_' +
      number +
      '" class="browser-default" name="item[]" required ></td>';
    html +=
      '<td width="5%" class="center"><input type="text" placeholder="Ingrese la cantidad" id="quantity_' +
      number +
      '" name="quantity[]" class="form-control quantity" required/></td>';
    html +=
      '<td width="20%" class="center"><input type="text" placeholder="Ingrese el costo unitario" id="unit_cost_local_money_' +
      number +
      '" name="unit_cost_local_money[]" class="form-control money local-money" required/></td>';
    html +=
      '<td width="20%" class="center"><input type="text" placeholder="Ingrese el costo unitario" id="unit_cost_foreign_money_' +
      number +
      '" name="unit_cost_foreign_money[]" class="form-control money foreign-money" required/></td>';
    html +=
      '<td width="20%" class="center" ><p class="total-local" id="total_cost_local_money_' +
      number +
      '"></p></td>';
    html +=
      '<td width="20%" class="center"><p class="total-foreign" id="total_cost_foreign_money_' +
      number +
      '"></p></td>';

    if (number > 1) {
      html +=
        '<td><button type="button" name="remove" id="" class="btn btn-danger red remove">X</button></td></tr>';
      $('tbody').append(html);
      $('#send').attr('disabled', false);
    }
  }

  $(document).on('click', '#add', function() {
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

      $('.money').mask('000.000.000.000.000,00', { reverse: true });
      $('.quantity').mask('19999999999', options);
    });
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
    var url = baseUrl + '/compras-items';
    $.get(
      url,
      {},
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
        $('.datepicker').datepicker();
        $('.datepicker').datepicker({
          minDate: new Date(),
          format: 'yyyy-mm-dd',
        });
      },
      'json',
    );
  }
});

$(document).on('click', '#check_trashed', function() {
  $('#check_trashed').val($('#check_trashed').val() == 1 ? 0 : 1);
});
