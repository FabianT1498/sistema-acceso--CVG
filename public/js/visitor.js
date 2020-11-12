$(function() {
  const options = {
    translation: {
      '0': { pattern: /\d/ },
      '1': { pattern: /[1-9]/ },
      '2': { pattern: /[a-z]|[A-Z]|[0-9]/ },
      '9': { pattern: /\d/, optional: true },
      '#': { pattern: /\d/, recursive: true },
      C: { pattern: /[VE]/, fallback: 'V' },
    },
  };

  $('#dni').mask('C-19999999', options);
  $('.enrrolment').mask('2222222', options);  
});

$(document).ready(function() {

  $.count = 0;

  function dynamic_field(number) {
    html = `
      <tr>
        <td>            
          <select id="auto_model_${number}" name="auto_model[]" class="form-control auto_model">
          </select>
        </td>
        <td><input id="color_${number}" name="color[]"  type="text" class="form-control color" required ></td>
        <td><input style="text-transform: uppercase" id="enrrolment_${number}" name="enrrolment[]" type="text" class="form-control enrrolment" required ></td>    
        <td><button type="button" name="remove" id="" class="btn btn-danger red remove">X</button></td></tr>
      </tr>
    `

    $('tbody').append(html);
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
          '2': { pattern: /[a-z]|[A-Z]|[0-9]/ },
          '9': { pattern: /\d/, optional: true },
          '#': { pattern: /\d/, recursive: true },
          C: { pattern: /[VE]/, fallback: 'V' },
        },
      };

      $('.enrrolment').mask('2222222', options);
    });
  });

  $(document).on("click", ".browse", function() {
    var file = $(this).parents().find(".file");
    file.trigger("click");
  });

  $('input[type="file"]').change(function(e) {
    var fileName = e.target.files[0].name;
    $("#file").val(fileName);

    var reader = new FileReader();
    
    reader.onload = function(e) {

      // get loaded data and render thumbnail.
      document.getElementById("preview").src = e.target.result;
    };

    // read the image file as a data URL.
    reader.readAsDataURL(this.files[0]);
  });

});

$(document).on('click', '#check_trashed', function() {
  $('#check_trashed').val($('#check_trashed').val() == 1 ? 0 : 1);
});



