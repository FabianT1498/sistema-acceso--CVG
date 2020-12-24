$(document).ready(function () {
    const reloadTime = 5000;
    const reloadOpt = localStorage.getItem('reloadPage') ? localStorage.getItem('reloadPage') : '0';
    $('#reloadPageChk').val(reloadOpt);
    $('#reloadPageChk').attr('checked', reloadOpt == 1 ? true: false);
});