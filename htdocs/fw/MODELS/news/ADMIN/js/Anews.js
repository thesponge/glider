ivyMods.set_iEdit.news = function()
{
    iEdit.add_bttsConf(
        {

            prevNews:{
                modName: 'news',
                edit:    {atrValue: 'edit news'},
                addBt:   { methName:'add_news', atrValue: 'add news'},
                saveBt:  { methName:'updatePrev_news', atrValue: 'save'},
                deleteBt:{ methName:'delete_news', atrValue: 'delete'}
            },
            news:{
                modName: 'news',
                saveBt:  { methName:'update_news', atrValue: 'save'}

            }
        });
}