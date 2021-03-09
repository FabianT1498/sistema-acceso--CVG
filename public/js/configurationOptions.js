$(document).ready(function () {
    const reloadOpt = localStorage.getItem('reloadPage') ? localStorage.getItem('reloadPage') : '0';
    $('#reloadPageChk').val(reloadOpt);
    $('#reloadPageChk').attr('checked', reloadOpt == 1 ? true: false);

    $('#reloadPageChk').on('click', function(){
        $(this).val($(this).val() == 1 ? 0 : 1);
        localStorage.setItem('reloadPage', $(this).val());
    })
});