$(function() {
  const options = {
    translation: {
      '0': { pattern: /\d/ },
      '1': { pattern: /[1-9]/ },
      '9': { pattern: /\d/, optional: true },
      '#': { pattern: /\d/, recursive: true },
      C: { pattern: /[VvEe]/, fallback: 'V' },
    },
  };

  $('#dni').mask('C-19999999', options);
});

$(document).on('click', '#check_trashed', function() {
  $('#check_trashed').val($('#check_trashed').val() == 1 ? 0 : 1);
});
