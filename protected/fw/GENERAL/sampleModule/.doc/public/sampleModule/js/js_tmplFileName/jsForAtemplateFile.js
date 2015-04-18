/* Extinderea obiectului principal pentru modul ex:*/

if( typeof ivyMods.sampleMod!= 'undefinden'  ) {

    $.extend(ivyMods.sampleMod, {

        Ainit: function(){
            console.log('ivyModes.sampleMod a fost declarat inaintew');

        }
    });
}


$(document).ready(function(){
    ivyMods.sampleMod.Ainit();
});


ivyMods.set_iEdit.sampleMod = function(){

    iEdit.add_bttsConf(
        {
            'ENTname':
            {
                modName: 'sampleModule'
                ,edit: {}
                ,deleteBt: {status:false, methName: 'sampleModule->deleteMethName'}
                ,addBt: {
                    atrValue: 'un nume',
                    style : 'oric',
                    class : '',
                    status: '',
                    methName: '',
                    async : new fmw.asyncConf({
                                    modName: 'modName',
                                    methName: 'methName',
                                    parsePOSTfile : 'filePath.php' ,
                                    callBack_fn : (typeof fnName != 'undefined'  ? fnName : ''),
                                    restoreCore : 0
                                })
                    }
                ,saveBt:  {methName: 'sampleModule->updateMethName'}
                  // butoane extra pentru toolbarul elementului editabil
                ,extraButtons:
                {
                      manageGroup: {
                          callBack: "ivyMods.team.showManageGroups();",
                          attrValue: 'manage Groups',
                          attrName: 'manage Groups',
                          attrType: 'submit/ button',
                          class: ''
                      },
                      showAllmembers:{
                          callBack: "ivyMods.team.showAllMembers();",
                          atrValue: 'show all Members',
                          class: ''
                      }
                }
                , extraHtml : ['htmlConetnt ',
                              "<span>" +
                                  "<input type='hidden' name='action_modName' value='user' >" +
                                  "<input type='hidden' name='action_methName' value='deactivateProfile [, other methods]' >" +
                                  "<input type='submit' name='deactivate' value='deactivate' >" +
                              "</span>",
                              '']


            },
            allENTSName : {
                extraButtons: {}

            },
            'SINGname':
            {
                // pentru mai multe despre setarea butoanelor in EDITmode see EDITmode.js -> var bttConf
            }
        });
};