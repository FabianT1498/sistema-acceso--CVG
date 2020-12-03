$(function() {
  const options = {
    translation: {
      '0': { pattern: /\d/ },
      '1': { pattern: /[1-9]/ },
      '2': { pattern: /[a-z]|[A-Z]|[0-9]/ },
      '9': { pattern: /\d/, optional: true },
      '#': { pattern: /\d/, recursive: true },
      C: { pattern: /[VE|ve]/, fallback: 'V' },
      
    },
  };

  $('#visitorDNI').mask('C-99999999', options);
  $('#visitorPhoneNumber').mask('0000-0000000', options);
});

$(document).ready(function() {

  $.count = 0;

  $("#visitorDNI").keyup(delay(function (e) {

    if ((this.value.length < 7 || (e.which < 48 || e.which > 57)) && e.which !== 8) {
        return;
    }



    ajaxParams.url = '/visitante';
    ajaxParams.data = { dni: this.value.toUpperCase() };

    const loader = $('#visitorLoader > div').first();
    const resultMsg = $('#visitorResult');

    ajaxParams.beforeSend = function () {
        loader.removeClass(['d-none', 'success', 'not-found']);
    }

    ajaxParams.success = function (data) {
        if (data.length === 0) {
            const html = `
                <p class="text-info d-inline">Este visitante no ha sido registrado, presione el boton para registrarlo</p>
                <button id="addVisitor" class="btn btn-primary btn-circle btn-md ml-md-2" type="button"><i class="nav-icon icon fa fa-plus"></i></button>
            `;
            loader.addClass('not-found');
            resultMsg.html(html);

            $('#addVisitor').on('click', function(){
                loadVisitorInputs();
                $('#visitorPhoneNumber').mask('0000-0000000', {translation:{'0': { pattern: /\d/ }}});
            });

        } else {
            loader.addClass('success');
            resultMsg.html(`<p class="text-uppercase mt-md-2">${data[0].value}</p>`);
            $('#visitorID').val(data[0].id);
            $('#visitorData').html('');
        }
    }

    $('#visitorID').val('-1');

    // Fetch data
    $.ajax(ajaxParams);
  }, 500));

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
  
  
  $(document).on('change', 'input[type="file"]', function(e) {

    var fileName = e.target.files[0].name;
    $("#file").val(fileName);

    var reader = new FileReader();
    
    reader.onload = function(e) {

      // get loaded data and render thumbnail.
      document.getElementById("preview").src = e.target.result;
    };

    // read the image file as a data URL.
    reader.readAsDataURL(e.target.files[0]);
  });

  /* $('input[type="file"]').change(function(e) {
    var fileName = e.target.files[0].name;
    $("#file").val(fileName);

    var reader = new FileReader();
    
    reader.onload = function(e) {

      // get loaded data and render thumbnail.
      document.getElementById("preview").src = e.target.result;
    };

    // read the image file as a data URL.
    reader.readAsDataURL(this.files[0]);
  }); */

});

$(document).on('click', '#check_trashed', function() {
  $('#check_trashed').val($('#check_trashed').val() == 1 ? 0 : 1);
  $('#searchForm').submit();
});



