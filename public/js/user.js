$(function() {
  const options = {
    translation: {
      '0': { pattern: /\d/ },
      '1': { pattern: /[1-9]/ },
      '9': { pattern: /\d/, optional: true },
      '#': { pattern: /\d/, recursive: true },
      C: { pattern: /[VE|ve]/, fallback: 'V' },
    },
  };

  $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
  });

  $( '#userBtnSubmit' ).on('click', function(e){

    const errors = [];

    $('#errors').html('');
    
    if ( $('#workerID').val() === "-1" ){
        errors.push('<li><i class="fa-li fa fa-check-square"></i>La cedula suministrada no le corresponde a ningun trabajador</li>');
    }

    if ( $('#password').val().length < 5){
      errors.push('<li><i class="fa-li fa fa-check-square"></i>La contraseña debe tener al menos 5 caracteres</li>');
    }
  
    if (errors.length > 0){
        e.preventDefault();
        errors.forEach(error => {$('#errors').append(error)});
        $('#errorModal').modal("show");
    }
  });


  $('#workerDNI').mask('C-19999999', options);
});

$(document).ready(function(){

  const ajaxParams = {
    url: '',
    type: 'POST',
    dataType: 'json'
  }

  const delay = function (fn, ms) {
    let timer = 0
    return function (...args) {
        clearTimeout(timer)
        timer = setTimeout(fn.bind(this, ...args), ms || 0)
    }
  }

  $("#username").keyup(delay(function (e) {

    const resultMsg = $('#usernameResult');

    ajaxParams.url = '/username';
    ajaxParams.data = { username: this.value };

    const loader = $('#usernameLoader > div').first();

    ajaxParams.beforeSend = function () {
        loader.removeClass(['d-none', 'success', 'not-found']);
    }

    ajaxParams.success = function (data) {
        if (data.length === 0) {
            const html = `
                <p class="mt-md-2">Nombre de usuario disponible</p>
            `;
            loader.addClass('success');
            resultMsg.html(html);
        } else {
            loader.addClass('not-found');
            resultMsg.html(`<p class="text-danger mt-md-2">Nombre de usuario no disponible</p>`);
        }
    }

    // Fetch data
    $.ajax(ajaxParams);
  }, 500));

  $("#workerDNI").keyup(delay(function (e) {

    if ((this.value.length < 7 || (e.which < 48 || e.which > 57)) && e.which !== 8) {
        return;
    }

    ajaxParams.url = '/trabajador';
    ajaxParams.data = { dni: this.value.toUpperCase() };

    const loader = $('#workerLoader > div').first();
    const resultMsg = $('#workerResult');

    ajaxParams.beforeSend = function () {
        loader.removeClass(['d-none', 'success', 'not-found']);
    }

    ajaxParams.success = function (data) {
        if (data.length === 0) {
            const html = `
                <p class="text-danger mt-md-2">Este trabajador no existe</p>
            `;
            loader.addClass('not-found');
            resultMsg.html(html);
        } else {
            loader.addClass('success');
            $('#workerID').val(data[0].id);
            resultMsg.html(`<p class="text-uppercase mt-md-2">${data[0].value}</p>`);
        }
    }

    $('#workerID').val('-1');
  
    // Fetch data
    $.ajax(ajaxParams);
  }, 500));
})

$(document).on('click', '#check_trashed', function() {

  $('#check_trashed').val($('#check_trashed').val() == 1 ? 0 : 1);
  $('#searchForm').submit();
});
