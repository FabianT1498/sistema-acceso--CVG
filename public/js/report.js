$(function () {
    
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

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
    });

    $( '#visitBtnSubmit' ).on('click', function(e){

        const errors = [];

        $('#errors').html('');
        
        if ( $('#workerID').val() === "-1" ){
            errors.push('<li><i class="fa-li fa fa-check-square"></i>La cedula suministrada no le corresponde a ningun trabajador</li>');
        }
        if($('#visitorID').val() === "-1" && $('#visitorData').html() === '') {
            errors.push('<li><i class="fa-li fa fa-check-square"></i>Presione el boton para agregar el visitante y suministre sus datos</li>');
        }

        if (errors.length > 0){
            e.preventDefault();
            errors.forEach(error => {$('#errors').append(error)});
            $('#errorModal').modal("show");
        }
    });

    $('#workerDNI').mask('C-99999999', options);
});

$(document).ready(function () {

    let errors = false;

    const ajaxParams = {
        url: '',
        type: 'POST',
        dataType: 'json'
    }

    const loadVisitorInputs = function(){
        const html = `
            <div class="card-body">
                <h3 class="h3 mb-md-5 text-center title-subline">Datos del visitante</h3>
                <div class="form-row mb-md-4">
                    <div class="form-group col-md-4">
                        <label for="visitorFirstname">Nombre(s):&nbsp;<sup class="text-danger">*</sup></label>
                        <input 
                            type="text" 
                            class="form-control" 
                            id="visitorFirstname" 
                            name="visitor_firstname" 
                            placeholder="Nombre del visitante"
                            required
                        >                   
                    </div>
                    <div class="form-group col-md-4">
                        <label for="visitorLastname">Apellido(s):&nbsp;<sup class="text-danger">*</sup></label>
                        <input 
                        type="text" 
                        class="form-control" 
                        id="visitorLastname" 
                        name="visitor_lastname" 
                        placeholder="Apellido del visitante"
                        required
                        >                   
                    </div>
                    <div class="form-group col-md-4">
                        <label for="visitorPhoneNumber">Telefono:&nbsp;<sup class="text-danger">*</sup></label>
                        <input 
                        type="text" 
                        class="form-control" 
                        id="visitorPhoneNumber" 
                        name="visitor_phone_number" 
                        placeholder="Telefono del visitante"
                        required
                        >
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="file">Foto del visitante &nbsp;<sup class="text-danger">*</sup></label>
                        <input type="file" name="image" class="file" accept="image/*">
                        <div class="input-group">
                            <input type="text" class="form-control" disabled placeholder="Subir Foto" id="file" required>
                            <div class="input-group-append">
                                <button type="button" class="browse btn btn-primary">Buscar...</button>
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-md-2 ml-md-4">                      
                        <img src="" id="preview" class="img-thumbnail">                    
                    </div>
                </div>
            </div>
        `;

        $('#visitorData').html(html);
    }

    const loadAutoInputs = function () {

        const html = `
            <div class="form-row mb-md-4">
                <div class="form-group col-md-3">
                    <label for="autoEnrrolment">Matricula del auto:&nbsp;<sup class="text-danger">*</sup></label>
                    <input 
                        type="text" 
                        class="form-control" 
                        id="autoEnrrolment" 
                        name="auto_enrrolment" 
                        style="text-transform:uppercase"
                        placeholder="Ingrese Matricula"
                        autocomplete="off"
                        required
                    >                   
                    <input type="hidden" id="autoID" name="auto_id" value="-1" readonly>
                </div>
                <div class="form-group col-md-3">
                    <label for="autoBrand">Marca del auto:&nbsp;<sup class="text-danger">*</sup></label>
                    <input 
                        type="text" 
                        class="form-control" 
                        id="autoBrand" 
                        name="auto_brand" 
                        placeholder="INGRESE LA MARCA"
                        required
                    >
                    <input type="hidden" id="autoBrandID" name="auto_brand_id" value="-1" readonly>                 
                </div>
                <div class="form-group col-md-3">
                    <label for="autoModel">Modelo del auto:&nbsp;<sup class="text-danger">*</sup></label>
                    <input 
                        type="text" 
                        class="form-control" 
                        id="autoModel" 
                        name="auto_model" 
                        placeholder="INGRESE EL MODELO"
                        required
                    >                   
                    <input type="hidden" id="autoModelID" name="auto_model_id" value="-1" readonly>                 
                </div>
                <div id="autoLoader" class="col-md-1 pt-md-3">
                    <div class="mt-md-4 loading d-none"></div> 
                </div>
                <div id="autoResult" class="col-md-2"></div>  
            </div>
                
            <div class="form-row">
                <div class="form-group col-md-3">
                    <label for="autoColor">Color:&nbsp;<sup class="text-danger">*</sup></label>
                    <input 
                        type="text" 
                        class="form-control" 
                        id="autoColor" 
                        name="auto_color" 
                        placeholder="COLOR DEL AUTO"
                        required
                    >                   
                </div> 
            </div>
        `;

        $('#autoData').html(html);
    }

    const delay = function (fn, ms) {
        let timer = 0
        return function (...args) {
            clearTimeout(timer)
            timer = setTimeout(fn.bind(this, ...args), ms || 0)
        }
    }

    


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

    $( "#autoData" ).on( "keyup", "#autoEnrrolment", delay(function (e) {

        if (this.value.length < 7) {
            return;
        }

        ajaxParams.url = '/auto';
        ajaxParams.data = { enrrolment: this.value.toUpperCase() };

        const loader = $('#autoLoader > div').first();
        const autoModel = $("#autoModel");
        const autoColor = $("#autoColor");
        const autoIDInput = $('#AutoID');
        const autoModelIDInput = $('#AutoModelID');

        const resultMsg = $('#autoResult');
     
        ajaxParams.beforeSend = function () {
            loader.removeClass(['d-none', 'success']);
            resultMsg.html('');
        }

        ajaxParams.success = function (data) {
            if (data.length === 0) {
                loader.addClass('d-none');
                autoModel.prop('disabled', false);
                autoColor.prop('disabled', false);
            } else {
                resultMsg.html(`<p class="text-uppercase mt-md-2">Auto registrado</p>`);
                loader.addClass('success');
                autoModel.prop('disabled', true);
                autoColor.prop('disabled', true);
                autoModel.val(data[0].model);
                autoColor.val(data[0].color);
                autoIDInput.val(data[0].auto_id)
                autoModelIDInput.val(data[0].auto_model_id)
            }
        }

        autoIDInput.val('-1');

        // Fetch data
        $.ajax(ajaxParams);
    }, 500))

    $( "#autoData" ).on( "focus", "#autoModel", function(e) {
        
        $( this ).autocomplete({
            delay: 500,
            source: function( request, response ) {
            
                ajaxParams.url = '/autos_modelos';
                ajaxParams.data = {
                    search: request.term.toUpperCase(),
                    auto_brand: $('#autoBrand').val().toUpperCase()
                };
                ajaxParams.beforeSend = function () {return;}
                ajaxParams.success = function(data) {
                    console.log(data);
                    response(data);
                }
    
                $('#autoModelID').val('-1');
          
                // Fetch data
                $.ajax(ajaxParams);

                return false;
            },
            select: function (event, ui) {
                
                $('#autoModel').val(ui.item.auto_model);
                $('#autoModelID').val(ui.item.auto_model_id);
                
                $('#autoBrand').val(ui.item.auto_brand);
                $('#autoBrandID').val(ui.item.auto_brand_id);
                
                return false;
            }
        });
      });

    $( "#autoData" ).on( "focus", "#autoBrand", function(e) {
        
        $( this ).autocomplete({
            delay: 500,
            source: function( request, response ) {
            
                ajaxParams.url = '/autos_marcas';
                ajaxParams.data = {search: request.term.toUpperCase()};
                ajaxParams.beforeSend = function () {return;}
                ajaxParams.success = function(data) {
                    console.log(data);
                    response(data);
                }
    
                $('#autoBrandID').val('-1');
          
                // Fetch data
                $.ajax(ajaxParams);

                return false;
            },
            select: function (event, ui) {
                
                $('#autoBrand').val(ui.item.value);
                $('#autoBrandID').val(ui.item.id);
            
                return false;
            }
        });
      });

    $('#attendingDate').daterangepicker({
        singleDatePicker: true,
        minYear: moment().year(),
        maxYear: parseInt(moment().format('YYYY'), 10) + 1,
        minDate: moment().format('DD-MM-YYYY'),
        drops: 'up',
        locale: {
            "format": "DD-MM-YYYY",
            "separator": "-",
            "applyLabel": "Aplicar",
            "cancelLabel": "Cancelar",
            "daysOfWeek": [
                "Dom",
                "Lu",
                "Ma",
                "Mi",
                "Ju",
                "Vi",
                "Sa"
            ],
            "monthNames": [
                "Enero",
                "Febrero",
                "Marzo",
                "Abril",
                "Mayo",
                "Junio",
                "Julio",
                "Agosto",
                "Septiembre",
                "Octubre",
                "Noviembre",
                "Diciembre"
            ],
            "firstDay": 1
        }
    });

    $('input.time-picker').daterangepicker({
        timePicker: true,
        timePicker24Hour: true,
        singleDatePicker: true,
        drops: 'up',
        locale: {
            format: 'HH:mm'
        }
    }, function (start, end, label) {

        const id = $(this.element).attr('id');

        if (id === 'entry-time') {
            const beforeTime = moment(end.format('HH:mm'), 'HH:mm');
            const afterTime = moment($('#departure-time').val(), 'HH:mm');
            if (!beforeTime.isBefore(afterTime)) {
                $('#departure-time').data('daterangepicker').setStartDate(beforeTime);
            }
        } else {
            const beforeTime = moment($('#entry-time').val(), 'HH:mm');
            const afterTime = moment(end.format('HH:mm'), 'HH:mm');

            if (afterTime.isBefore(beforeTime)) {
                $('#entry-time').data('daterangepicker').setStartDate(afterTime);
            }
        }

    }).on('show.daterangepicker', function (ev, picker) {
        picker.container.find(".calendar-table").hide();
    });

    $(document).on('click', 'input[name="auto_option"]', function () {
        const id = $(this).attr('id');

        if (id === 'thereIsAutoOpt') {
            loadAutoInputs();
            $('#autoEnrrolment').mask('2222222', {
                translation: {'2': { pattern: /[a-z]|[A-Z]|[0-9]/ }}
            }); 
        } else {
            $('#autoData').html('');
        }
    });

    $(document).on('click', '#check_trashed', function () {
        $('#check_trashed').val($('#check_trashed').val() == 1 ? 0 : 1);
        $('#searchForm').submit();
    });

});

