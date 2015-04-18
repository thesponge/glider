ivyMods.set_iEdit.sampleModule = function(){
	
	iEdit.add_bttsConf({
		elemName_ex: {
	        modName : '',
	        addBt: {
	            atrValue: 'un nume',
	            style : 'oric',
	            class : '',
	            status: '',
	            methName: 'methName [, otherMethod, ...]',
	            async : new fmw.asyncConf({
	                            dataSend: {
		                             modName: 'modName', methName: 'methName [, otherMethod, ...]',
	                                ajaxReqFile : 'filePath.php'
	                            },
	                            callBack : {
	                              fn: (typeof fnName != 'undefined'  ? fnName : '') ,
	                              context : this,
	                              args: []
	                            },
	                            restoreCore : '0 / 1'
	                        })
	            },
	        edit: {
		        attrValue : 'edit article',
              callback: { fn: ivyMods.blog.adminAuthors,
                           context: ivyMods.blog
                           //,args : ''
                          }
	        },

	        deleteBt : {methName:'', status : 1, atrName:"delete_"+Name, atrValue: 'd' },
	        saveBt : {methName:'',status : 1, attrName:"save_"+Name, attrValue: 's' },

            // butoane extra pentru elements
            // atentie allEnts trebuie sa isi declare singura extraButtons
           extraButtons:
           {
                manageGroup: {
                    callBack: "ivyMods.team.showManageGroups();",
                    callbackFull: '',
                    attrValue: 'manage Groups',
                    attrName: 'manage Groups',
                    attrType: 'submit/ button',
                    class: '',
                    methName: ''
                },
                showAllmembers:{
                    callBack: "ivyMods.team.showAllMembers();",
                    atrValue: 'show all Members',
                    class: ''
                }
           },

			 // adaugare de html direct in TOOLS
           extraHtml: [
	           'any_htmlConetnt ',
              "<span>" +
                  "<input type='hidden' name='action_modName' value='user' >" +
                  "<input type='hidden' name='action_methName' value='deactivateProfile [, other methods]' >" +
                  "<input type='submit' name='deactivate' value='deactivate' >" +
              "</span>",
              '']
           }
	});
	
};