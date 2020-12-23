$(document).ready(function () {
    
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

    $('#searchBtn').click(function(){

        $('#searchForm').submit();
    });
});

