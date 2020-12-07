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

    const delay = function (fn, ms) {
        let timer = 0
        return function (...args) {
            clearTimeout(timer)
            timer = setTimeout(fn.bind(this, ...args), ms || 0)
        }
    }

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
            <h3 class="h3 mb-md-5 text-center title-subline">Datos del auto</h3>
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
                <div id="autoResult" class="col-md-2 pt-md-3"></div>  
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

    

        $( "#department" ).autocomplete({
            delay: 500,
            source: function( request, response ) {
            
                ajaxParams.url = '/departamentos';
                ajaxParams.data = {
                    search: request.term.toUpperCase(),
                    building: $('#building').val().toUpperCase()
                };
                ajaxParams.beforeSend = function () {return;}
                ajaxParams.success = function(data) {
                    console.log(data);
                    response(data);
                }
    
                $('#buildingID').val('-1');
          
                // Fetch data
                $.ajax(ajaxParams);

                return false;
            },
            select: function (event, ui) {
                
                $('#building').val(ui.item.building);
                $('#buildingID').val(ui.item.building_id);
                
                $('#department').val(ui.item.department);
                $('#departmentID').val(ui.item.department_id);
                
                return false;
            }
        });
     

        $( "#building" ).autocomplete({
            delay: 500,
            source: function( request, response ) {
            
                ajaxParams.url = '/edificios';
                ajaxParams.data = {search: request.term.toUpperCase()};
                ajaxParams.beforeSend = function () {return;}
                ajaxParams.success = function(data) {
                    console.log(data);
                    response(data);
                }
    
                $('#buildingID').val('-1');
          
                // Fetch data
                $.ajax(ajaxParams);

                return false;
            },
            select: function (event, ui) {
                
                $('#building').val(ui.item.value);
                $('#buildingID').val(ui.item.id);
            
                return false;
            }
        });
        
    // DATE RANGE PICKER LOCALES
    const locale = {
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
    
    $('#attendingDate').daterangepicker({
        singleDatePicker: true,
        minYear: moment().year(),
        maxYear: parseInt(moment().format('YYYY'), 10) + 1,
        minDate: moment().format('DD-MM-YYYY'),
        drops: 'up',
        locale: locale
    });

    
    $('#startDate, #finishDate').daterangepicker({
        singleDatePicker: true,
        drops: 'down',
        showDropdowns: true,
        locale: locale,
        autoUpdateInput:false,
        autoApply: true
    }).on('apply.daterangepicker', function(ev, picker) {

        $(this).val(picker.endDate.format('DD-MM-YYYY'));
        const id = $(this).attr('id');
        const selectedTime = picker.endDate.format('DD-MM-YYYY');

        if (id === 'startDate') {
            const beforeTime = moment(selectedTime, 'DD-MM-YYYY');
            const afterTime = moment($('#finishDate').val(), 'DD-MM-YYYY');
            if (!beforeTime.isBefore(afterTime)) {
                $('#finishDate').val(selectedTime);
            }
        } else {
            const beforeTime = moment($('#startDate').val(), 'DD-MM-YYYY');
            const afterTime = moment(selectedTime, 'DD-MM-YYYY');

            if (afterTime.isBefore(beforeTime)) {
                $('#startDate').val(selectedTime);
            }
        }
    });
  ;

    $('#searchBtn').click(function(){

        $('#searchForm').submit();
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

    $(document).on('change', '#statusSelect', function () {
        $('#searchForm').submit();
    });

});

