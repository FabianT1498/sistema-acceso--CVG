$(document).ready(function () {

    const reloadTime = 5000;
    const reloadOpt = localStorage.getItem('reloadPage') ? localStorage.getItem('reloadPage') : '0';
    $('#reloadPageChk').val(reloadOpt);
    $('#reloadPageChk').attr('checked', reloadOpt == 1 ? true: false);
    
    const delay = function (fn, ms) {
        let timer = 0
        return function (...args) {
            clearTimeout(timer);
        	if (args[0]){
            	timer = setTimeout(fn.bind(this, ...args), ms || 0)
            } else {
                timer = 0;       
            }
        }
    }

    const submitForm = function(){
        $('#searchForm').submit();
    }

    const callback = delay(submitForm, reloadTime);

    if (reloadOpt == 1){
        $("body").on("mousemove keypress", callback.bind(this, true));
    }

    $('#reloadPageChk').on('click', function(){
        $(this).val($(this).val() == 1 ? 0 : 1);
        localStorage.setItem('reloadPage', $(this).val());

        if ($(this).val() == 1) {
            $(document.body).on("mousemove keypress", callback.bind(this, true));
        } else {
            $(document.body).off("mousemove keypress");
            callback.call(this, false);
        }
    })
});