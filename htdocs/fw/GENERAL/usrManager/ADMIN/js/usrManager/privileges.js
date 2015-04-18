//$('.slider-button').toggle(function() {
    //$(this).toggleClass('on').html('');
    ////$(this).next(".slider-value").val(true);
//}, function() {
    //$(this).toggleClass('on').html('');
    ////$(this).next(".slider-value").val(false);
//});

$('.slider-button').click(function() {
    var v = $(this).next('span');
    $(this).toggleClass('on');
    if ($(this).hasClass('on')) {
        v.html('0');
    } else {
        v.html('1');
    }
});
