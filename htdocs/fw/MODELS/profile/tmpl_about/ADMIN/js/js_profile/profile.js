ivyMods.set_iEdit.sampleMod = function(){


    /**
     * if ADMIN =>
     * ivyMods.profileConf = {
                             editStatusAdmin : "true/ false",
                             activeStatus: "1 / 0"
                        };

     * @type {Array}
     */
    var extraHtml = [];

    var extraSaveMeth = '';

    /**
     * see if user is admin or not
     * Daca userul este admin atunci adauga butoanele de activate / deactivate
     * si setarile userului
     */
    if(typeof ivyMods.profileConf != 'undefined'
        && ivyMods.profileConf.editStatusAdmin
    ){
       extraSaveMeth = ', saveProfileAdmin';

        var ativeStatus = ivyMods.profileConf.activeStatus == '1'
                        ? 'deactivate'
                        : 'activate';
        var ativeStatusVal = ivyMods.profileConf.activeStatus == '1'
                            ? 0
                            : 1;
        // add extra buttons
        extraHtml.push(
            "<span>" +
                "<input type='hidden' name='action_methName' value='change_activeStatus' >" +
                "<input type='hidden' name='activeStatus' value='"+ativeStatusVal+"' >" +
                "<input type='submit' name='activate' value='"+ativeStatus+"' >" +
             "</span>");
        extraHtml.push(
            "<span>" +
                "<input type='button' name='adminSetting' value='Admin Settings' " +
                " onclick='fmw.toggle(\"form[id^=EDITform] .admin-extras\"); return false;' >" +
             "</span>");
        extraHtml.push(
            "<span>" +
                "<input type='hidden' name='action_methName' value='deleteUser'>" +
                "<input type='submit' name='deleteUser' value='delete user' />" +
            "</span>"
        );
    }

    iEdit.add_bttsConf(
    {
        'sgProfile':{
            modName: 'profile',
            edit: {attrValue: 'edit profile'},
            saveBt: {methName: 'saveProfile'+ extraSaveMeth, attrValue: 'save profile'}
            /*,extraButtons:{
                extraBt : { attrValue : 'delete profile',attrName: 'deleteProfile'
                    , attrType:  'submit', methName: 'deleteProfile'}
            }*/
            ,extraHtml: extraHtml

        }
    });
};