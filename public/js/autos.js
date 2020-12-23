$(function() {
    
    $('#autoEnrrolment').mask('2222222', {
        translation: {'2': { pattern: /[a-z]|[A-Z]|[0-9]/ }}
    }); 

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
    });
});

$(document).ready(function() {

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

    const isAutoUpdate = function(){
        return $('#formAutoUpdate').length ? true : false;
    }

    $( document ).on( "keyup", "#autoEnrrolment", delay(function (e) {

        console.log(isAutoUpdate());

        if (isAutoUpdate() || this.value.length < 7) {
            return;
        }

        ajaxParams.url = '/auto';
        ajaxParams.data = { enrrolment: this.value.toUpperCase() };

        const loader = $('#autoLoader > div').first();
        const autoModel = $("#autoModel");
        const autoBrand = $("#autoBrand");
        const autoColor = $("#autoColor");
        const autoIDInput = $('#autoID');
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
                autoBrand.prop('disabled', false);
                autoColor.prop('disabled', false);
            } else {
                resultMsg.html(`<p class="text-uppercase mt-md-4">Auto registrado</p>`);
                loader.addClass('success');
                autoModel.prop('disabled', true);
                autoBrand.prop('disabled', true);
                autoColor.prop('disabled', true);
                autoModel.val(data[0].model);
                autoBrand.val(data[0].brand);
                autoColor.val(data[0].color);
                autoIDInput.val(data[0].auto_id);
                autoModelIDInput.val(data[0].auto_model_id);

                console.log(autoIDInput);
            }
        }

        autoIDInput.val('-1');

        // Fetch data
        $.ajax(ajaxParams);
    }, 500))
    

    $( document ).on( "focus", "#autoModel", function(e) {
        
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

    $( document ).on( "focus", "#autoBrand", function(e) {
        
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

    $(document).on('click', '#check_trashed', function() {
        $('#check_trashed').val(parseInt($('#check_trashed').val()) === 1 ? 0 : 1);
        $('#searchForm').submit();

    });

});
  
  