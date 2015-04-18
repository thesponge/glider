$('#usr-nav>li>a').live('click', function() {
    var id = $(this).attr('href').replace('#','');
    //alert(id);
    $('#'+id).load('fw/GENERAL/usrManager/ADMIN/usrmgrWebController.php',
               {
                   action: id,
                    route: $(this).attr('name')
               },
              function(){
                  eval(id+'Table()');
              }
              );
});

function usrButton(icon, href, id, title, style){
    var button = '';
    if(typeof style != "undefined")
        style = "btn-" + style;
    else
        style = "";
    //button = '<a name="' + href + '" class="btn-user btn-' + icon
                //+ '" id="' + id + '"'
                //+ ' title="' + title + '"></a>';
    button = '<a name="' + href + '" class="btn btn-mini ' + style
                + '" id="' + id + '"'
                + ' title="' + title + '"><i class="icon-' + icon + '"></i></a>';
    return button;
}

$('.dataTable tr').live({
    click: function(){
        sname = $(this).children().eq(1).html();
        stype = $(this).parents('.tab-pane').attr('id');
        $('#selector span').text(sname + ' (' + stype + ')');
        $(this).siblings('tr').removeClass('selected');
        $(this).addClass('selected');
        //alert(uname);
        //$.removeCookie('selected');
        $.cookie('usrManager/selectedName', sname, { path: '/' });
        $.cookie('usrManager/selectedType', stype, { path: '/' });
    }
});


//$('.dataTable').event.selection({
    //type:  "selectionTrigger",


$('#selector i').live(
    'click', (function() {
        $.removeCookie('usrManager/selectedName');
        $.removeCookie('usrManager/selectedType');
        $('.dataTable tr').removeClass('selected');
        $(this).siblings('span').html('');
}));
