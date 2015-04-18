function classesTable() {
    var oTable = $('#usrManager_classes').dataTable( {
       "aoColumns": [
          { "sClass":"cid"},
          { "sClass":"name"},
          { "sClass":"groups"}
        ],
        "bProcessing": true,
        "bServerSide": true,
        "bDestroy": true,
        "sAjaxSource": "fw/GENERAL/usrManager/ADMIN/server_processing.php?fn=classesVars",
        "fnDrawCallback": function () {
            $("table#usrManager_classes tbody>tr").map(function(){
                var cid = $(this).attr('id');
                var controls = usrButton('edit','class:edit'   ,'edit-'+cid   , 'Edit class');
                controls += usrButton('gears','class:privileges','disable-'+cid, 'Members');
                controls += usrButton('delete', 'class:delete' ,'delete-'+cid , 'Delete class');
                $(this).append(controls);
            });
            $('#example tbody td').editable( 'fw/GENERAL/usrManager/ADMIN/editable_ajax.php', {
                        "callback": function( sValue, y ) {
                            var aPos = oTable.fnGetPosition( this );
                            oTable.fnUpdate( sValue, aPos[0], aPos[1] );
                        },
                        "submitdata": function ( value, settings ) {
                            return {
                                "cid": this.parentNode.getAttribute('id'),
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
