$(document).ready(function () {
    
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
    });
    
    function fetchVisitsByConfirm(){
        $.ajax({
            url: '/visitas-por-confirmar',
            type: 'post',
            data: {},
            dataType: 'json',
            success: function(data){
                $('#visitByConfirmBadge').html(data[0].visitsByConfirm);
                $('#visitByConfirm').attr('title', `${data[0].visitsByConfirm} visitas por confirmar`);
            },
            complete:function(data){
                setTimeout(fetchVisitsByConfirm, 7000);
            }
        });
    }
       
    setTimeout(fetchVisitsByConfirm, 0);

})
