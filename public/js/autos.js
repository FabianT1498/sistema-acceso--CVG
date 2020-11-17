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

    const arrColors = ['azul', 'verde', 'rojo', 'blanco', 'negro', 'gris', 'amarillo', 'plateado', 'morado'];

    $.each(arrColors, function(index, color) { 
        $('#autoColor').append(`<option value="${color}"> ${color}</option>`)
    });

    $('#autoEnrrolment').mask('2222222', options);  
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

    // Function To List out Auto Models in Second Select tags
    const setSelectOptions = function (selectID, data) {

        $(selectID).empty(); //To reset models
        $(selectID).append("<option hidden disabled selected value> -- selecciona un modelo -- </option>");

        $.each(data, function(index, autoModel) { 
            $(selectID).append(`<option value="${autoModel.id}"> ${autoModel.value}</option>`)
        });
    }

    $('#autoBrandSelect').change(function(){
        const select = $("#autoBrandSelect option:selected").val();
        const url = '/autos_modelos';
        
        ajaxParams.url = url;
        ajaxParams.data = {auto_brand_id: select};

        ajaxParams.success = function(data){
            setSelectOptions('#autoModelSelect', data);
        }

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
        });

        // Fetch data
        $.ajax(ajaxParams);
    }); 

    $( "#visitorSearch" ).autocomplete({
        source: function( request, response ) {

            const url = '/lista_visitantes';
            
            ajaxParams.url = url;
            ajaxParams.data = {search: request.term, route: 'autos'};
            ajaxParams.success = function(data) {
                response( data );
            }

            // Set dni and id input empty during typing
            const selector = '#visitor';  
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

            const selector = '#visitor';
            
            // Set selection
            const keyMap = {};
            keyMap[`${selector}Search`] = ui.item.value
            keyMap[`${selector}ID`] = ui.item.id
            keyMap[`${selector}DNI`] = ui.item.dni
            setInputsValue(keyMap);
            
            return false;
        }
    });

    $(document).on('click', '#check_trashed', function() {
        $('#check_trashed').val(parseInt($('#check_trashed').val()) === 1 ? 0 : 1);
        $('#searchForm').submit();

    });

    $(document).on('click', '#checkAutoBrand', function() {

        if ($('checkAutoModel').val() === "1"){
            return false;
        }

        const status = parseInt($('#checkAutoBrand').val()) === 1 ? 0 : 1;

        $('#checkAutoBrand').val(status);

        // Disabling either model and brand auto select.
        $("#autoBrandSelect").prop('disabled', status ? true : false);
        $("#autoModelSelect").prop('disabled', status ? true : false);

        // Set inputs like not required
        $("#autoBrandSelect").prop('required', status ? false : true);
        $("#autoModelSelect").prop('required', status ? false : true);
    
        // Set default value for selects
        $("#autoBrandSelect").val("");
        $("#autoModelSelect").val("");

        $('#checkAutoModel').prop('disabled', status ? true : false );

        const autoBrandGroup =  $("#autoBrandGroup");
        const autoModelGroup = $("#autoModelGroup");

        autoBrandGroup.toggleClass('d-none');
        autoModelGroup.toggleClass('d-none');

        if (status){
            // Add inputs into DOM
            autoBrandGroup.append(
                `
                    <label for="autoBrandInput">Nombre de la marca &nbsp; <sup class="text-danger">*</sup></label>
                    <input 
                        id="autoBrandInput"
                        name="auto_brand_input" 
                        type="text" 
                        class="form-control" 
                        placeholder="Ingrese el nombre de la marca del automovil" 
                        required>
                `
            );

            autoModelGroup.append(
                `
                    <label for="autoModelInput">Nombre del modelo &nbsp; <sup class="text-danger">*</sup></label>
                    <input 
                        id="autoModelInput"
                        name="auto_model_input" 
                        type="text" 
                        class="form-control" 
                        placeholder="Ingrese el nombre del modelo del automovil" 
                        required>
                `
            );
        } else {
            $('#autoBrandGroup > *').each(function(i, obj) {
                obj.remove();
            });

            $('#autoModelGroup > *').each(function(i, obj) {
                obj.remove();
            });
        }   
    });

    $(document).on('click', '#checkAutoModel', function() {
    
        if ($('#checkAutoBrand').val() == 1){
            return false;
        }

        const status = parseInt($('#checkAutoModel').val()) === 1 ? 0 : 1

        $('#checkAutoModel').val(status);

        // Disabling either model and brand auto select.
        $("#autoModelSelect").prop('disabled', status ? true : false);

        // Set inputs like not required
        $("#autoModelSelect").prop('required', status ? false : true);
    
        // Set default value for selects
        $("#autoModelSelect").val("");

        $('#checkAutoBrand').prop('disabled', status ? true : false );

        const autoModelGroup = $("#autoModelGroup");

        autoModelGroup.toggleClass('d-none');

        if (status){
          
            autoModelGroup.append(
                `
                    <label for="autoModelInput">Nombre del modelo &nbsp; <sup class="text-danger">*</sup></label>
                    <input 
                        id="autoModelInput"
                        name="auto_model_input" 
                        type="text" 
                        class="form-control" 
                        placeholder="Ingrese el nombre del modelo de automovil" 
                        required>
                `
            );
        } else {
    
            $('#autoModelGroup > *').each(function(i, obj) {
                obj.remove();
            });
        }

        return true;
    });

});
  
  