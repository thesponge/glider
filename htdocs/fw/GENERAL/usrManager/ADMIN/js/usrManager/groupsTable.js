function groupsTable() {
    var oTable = $('#usrManager_groups').dataTable( {
       "aoColumns": [
          { "sClass":"gid"},
          { "sClass":"name"},
          { "sClass":"description"}
        ],
        "bProcessing": true,
        "bServerSide": true,
        "bDestroy": true,
        "sAjaxSource": "fw/GENERAL/usrManager/ADMIN/server_processing.php?fn=groupsVars",
        "fnDrawCallback": function () {
            $("table#usrManager_groups tbody>tr").map(function(){
                var gid = $(this).attr('id');
                var controls = usrButton('edit','group:edit'   ,'edit-'+gid   , 'Edit group');
                controls += usrButton('gears','group:privileges','disable-'+gid, 'Privileges');
                controls += usrButton('delete', 'group:delete' ,'delete-'+gid , 'Delete group');
                $(this).append(controls);
            });
            $('#example tbody td').editable( 'fw/GENERAL/usrManager/ADMIN/editable_ajax.php', {
                        "callback": function( sValue, y ) {
                            var aPos = oTable.fnGetPosition( this );
                            oTable.fnUpdate( sValue, aPos[0], aPos[1] );
                        },
                        "submitdata": function ( value, settings ) {
                            return {
                                "gid": this.parentNode.getAttribute('id'),
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
