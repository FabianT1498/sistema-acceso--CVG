
$(function(){
    
    const delay = function (fn, ms) {
        let timer = 0
        return function (...args) {
            clearTimeout(timer)
            timer = setTimeout(fn.bind(this, ...args), ms || 0)
        }
    }

    $(document.body).on("mousemove keypress", delay(function(){
        $('#searchForm').submit();
    }, 4000));

});