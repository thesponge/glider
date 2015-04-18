function usersTable() {
    var oTable = $('#usrManager_users').dataTable( {
       "aoColumns": [
          { "sClass":"uid"},
          { "sClass":"uname"},
          { "sClass":"uclass"},
          { "sClass":"full_name"},
          { "sClass":"email"},
          { "sClass":"active"}
        ],
        "bProcessing": true,
        "bServerSide": true,
        "bDestroy": true,
        "sAjaxSource": "fw/GENERAL/usrManager/ADMIN/server_processing.php?fn=usersVars",
        "fnDrawCallback": function () {
            //$("table#usrManager_users tbody>tr").append("<td>Delete/Edit</td>");
            $("table#usrManager_users tbody>tr").map(function(){
                var uid = $(this).attr('id');
                name = $(this).children().eq(1).html();
                sname = $.cookie('usrManager/selectedName');

                var controls = usrButton('edit','usr:edit'   ,'edit-'+uid   , 'Edit user');
                controls += usrButton('off','usr:off','off-'+uid, 'Disable user', 'pink');
                controls += usrButton('remove', 'usr:remove' ,'remove-'+uid , 'Delete user', 'danger');
                $(this).append(controls);

                if(name == sname) {
                    $(this).toggleClass('selected');
                }
            });
            $('#example tbody td').editable( 'fw/GENERAL/usrManager/ADMIN/editable_ajax.php', {
                        "callback": function( sValue, y ) {
                            var aPos = oTable.fnGetPosition( this );
                            oTable.fnUpdate( sValue, aPos[0], aPos[1] );
                        },
                        "submitdata": function ( value, settings ) {
                            return {
                                "uid": this.parentNode.getAttribute('id'),
                                //"row_id": oTable.fnGetPosition( this )[0],
                                //"column": oTable.fnGetPosition( this )[2]
                                "column": this.getAttribute('class')
                            };
                        },
                "height": "14px"
            } );
        }
    } );
    //oTable.fnDestroy();
};
