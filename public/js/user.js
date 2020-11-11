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

$(document).ready(function(){

  const ajaxParams = {
    url: '',
    type: 'POST',
    dataType: 'json'
  }

  const setInputsValue =  function(keyMap){

      for (const key in keyMap) {
          $(key).val(keyMap[key]);
      } 
  }

  $( "#workerSearch" ).autocomplete({
      source: function( request, response ) {

          const url = '/lista_trabajadores';
          
          ajaxParams.url = url;
          ajaxParams.data = {search: request.term};
          ajaxParams.success = function(data) {
              response( data );
          }

          // Set dni and id input empty during typing
          const selector = '#worker';  
          const keyMap = {};
          keyMap[`${selector}ID`] = '';
          keyMap[`${selector}DNI`] = '';
     
          setInputsValue(keyMap);

          $.ajaxSetup({
              headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              },
          });

          // Fetch data
          $.ajax(ajaxParams);
      },
      select: function (event, ui) {

          const selector = '#worker';
          
          // Set selection
          const keyMap = {};
          keyMap[`${selector}Search`] = ui.item.value
          keyMap[`${selector}ID`] = ui.item.id
          keyMap[`${selector}DNI`] = ui.item.dni
          setInputsValue(keyMap);
          
          return false;
      }
  });
})

$(document).on('click', '#check_trashed', function() {

  $('#check_trashed').val($('#check_trashed').val() == 1 ? 0 : 1);
});
