$(function() {
  const options = {
    translation: {
      '0': { pattern: /\d/ },
      '1': { pattern: /[1-9]/ },
      '9': { pattern: /\d/, optional: true },
      '#': { pattern: /\d/, recursive: true },
      C: { pattern: /[VvEePpJjGgMmCc]/, fallback: "G" },
      T: { pattern: /[0]/, fallback: "0" }

    },
  };

  $('#dni').mask('C-199999999', options);
  $('#phone').mask('T111 - 0000000', options);
});

$(document).on('click', '#check_trashed', function() {
  $('#check_trashed').val($('#check_trashed').val() == 1 ? 0 : 1);
});
