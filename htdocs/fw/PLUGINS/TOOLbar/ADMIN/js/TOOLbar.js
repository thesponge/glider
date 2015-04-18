var LG='en';
var activePOPup = 0;
$(document).ready(function(){

    LG = $('input[name=lang]').val();

    currentPOPUP          = $('input[name=statusPOPUP]').val();
    currentPOPUP_modeType = $('input[name=statusPOPUP_modeType]').val();
    if(currentPOPUP)
    {
        activatePOPUPedit(currentPOPUP,currentPOPUP_modeType );
        $('input[name=statusPOPUP]').val('');
    }


});

function activatePOPUPedit(modName, modeType)
{
    if(activePOPup == 0)
    {
         $('#admin_POPUP').show();
        // aa..dar poate nu e PLUGINS NEAPARAT...??
        // deci trebuie sa existe un html luat din RES / modeType / LG / modName.html
         $.get('/RES/'+modeType+'/'+modName+'/'+LG+'_'+modName+'.html',function(data)
         {
            $('#admin_POPUP > #admin_POPUP_content').append(data);
            ctrl_INIadmin(modName);

            $('#admin_POPUP > #admin_POPUP_content form').append
            (
                "<input type='hidden' name='currentPOPUP' value='"+modName+"' />"+
                "<input type='hidden' name='currentPOPUP_modeType' value='"+modeType+"' />"
            );
         });
         activePOPup = 1;
    }
    else {
        CLOSE_admin_POPUP();
    }

}

function CLOSE_admin_POPUP()
{
    $('#admin_POPUP > #admin_POPUP_content').empty();
    $('#admin_POPUP').hide();
    activePOPup = 0;

}

function ctrl_INIadmin(modName)
{
    if(modName=='GEN_edit')INIadmin_GEN_edit();
}
