$(document).ready(function() {
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
    $('.quantity').each(function() {
      id = $(this).attr('id');

      $('#' + id).inputmask('Regex', { regex: '^[1-9][0-9]?$|^100$' });
    });
  });

  rellenaCantidad();

  $(document).on('input', '.quantity', function() {
    id_row = this.id.split('_')[2];
    console.log($('#quantity_' + id_row).text());
    var value = $(this).val();

    console.log('value: ' + value);
    $(this).val(
      Math.max(Math.min(value, parseInt($('#quantity_' + id_row).text())), 0),
    );
  });

  function rellenaCantidad() {
    $('.quantity-stock').each(function() {
      $(this).val($('#quantity_' + this.id.split('_')[2]).text());
    });
  }
});
