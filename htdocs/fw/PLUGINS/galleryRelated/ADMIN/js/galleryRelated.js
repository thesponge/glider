ivyMods.set_iEdit.galleryRelated = function(){


    // =================[ portfolio category ]====================================
    iEdit.add_bttsConf(
        {
            'container-img':
            {
                moduleName: 'galleryRelated',
                deleteBt: {status:false, methName: 'delete_portofCat'},
                addBt:  {status:false, methName: 'add_portofCat', atrValue: 'add Category'},
                saveBt:  {methName: 'update_portofCat'},
                edit: {atrValue: 'edit Category'}
            },
            'projectPrev':
            {
                moduleName: 'galleryRelated',
                deleteBt: { methName: 'delete_portofProjectPrev'},
                addBt:  { methName: 'add_portofProjectPrev', atrValue: 'add Project'},
                saveBt:  {methName: 'update_portofProjectPrev'},
                edit: {atrValue: 'edit Project'}
            },
            'project-details':
            {
                moduleName: 'galleryRelated',
                edit: {atrValue:'edit Project'},
                saveBt:  {methName: 'update_portofProject', atrValue:'save Project'}
            },
            'altPic':
            {
               moduleName: 'galleryRelated',
               addBt:  { methName: 'add_pic_portofProject', atrValue: 'add Pics'},
               deleteBt: { methName: 'delete_pic_portofProject'},
               saveBt:  {methName: 'update_pic_portofProject'}
            }
        }
    );

   // =================[ portfolio project ]====================================

   /* iEdit.add_bttConf(

    );*/


}