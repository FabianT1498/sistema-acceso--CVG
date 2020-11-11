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
});

$(document).ready(function() {

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

    $( "#visitorSearch, #workerSearch" ).autocomplete({
        source: function( request, response ) {

            const url = $( "#visitorSearch" ).is( ":focus" ) 
                ? '/lista_visitantes'
                : '/lista_trabajadores';
            
            ajaxParams.url = url;
            ajaxParams.data = {search: request.term};
            ajaxParams.success = function(data) {
                response( data );
            }

            // Set dni and id input empty during typing
            const selector = $( "#visitorSearch" ).is( ":focus" ) ? '#visitor' : '#worker';  
            const keyMap = {};
            keyMap[`${selector}ID`] = '';
            keyMap[`${selector}DNI`] = '';
            keyMap[`${selector}DNI`] = '';
            setInputsValue(keyMap);

            if (selector === '#visitor'){
                $( "#autoSelect" ).html('<option value="-1">Ninguno</option>');
            }

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
            });

            // Fetch data
            $.ajax(ajaxParams);
        },
        select: function (event, ui) {

            const selector = $( "#visitorSearch" ).is( ":focus" ) ? '#visitor' : '#worker';
            
            // Set selection
            const keyMap = {};
            keyMap[`${selector}Search`] = ui.item.value
            keyMap[`${selector}ID`] = ui.item.id
            keyMap[`${selector}DNI`] = ui.item.dni
            setInputsValue(keyMap);
            
            // Get the visitor's autos
            if (selector === '#visitor'){

                ajaxParams.url = '/autos_visitante';
                ajaxParams.data = {visitorID: ui.item.id };
                ajaxParams.success = function(data){
                    $.each(data, function(index, auto) {
                        const option = `
                            <option value="${auto.id}">
                              ${auto.auto_model}&nbsp;-&nbsp;${auto.enrrolment}
                            </option>
                        `;
                    
                        $('#autoSelect').append(option);
                    });
                }
    
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                });
    
                // Fetch data
                $.ajax(ajaxParams);
            }

            return false;
        }
    });

    $('#attendingDate').daterangepicker({
        singleDatePicker: true,
        showDropdowns: true,
        minYear: moment().year(),
        maxYear: parseInt(moment().format('YYYY'),10),
        minDate: moment().format('YYYY-MM-DD HH:mm'),
        timePicker: true,
        timePicker24Hour: true,
        drops: 'up',
        locale: {
            format: 'YYYY-MM-DD HH:mm'
        }
      }, function(start, end, label) {
        console.log($('#attendingDate').val())
    });

    $(document).on('click', '#check_trashed', function() {
        $('#check_trashed').val($('#check_trashed').val() == 1 ? 0 : 1);
    });

});
  
  