<?php
class ACsingle extends Csingle
{
    var $post;

    function savePage()
    {
       /*if($this->contentRights) {
           $this->C->feedBack->Set_mess(
               'error',
               'Permission error',
               'You dont have permissions to edit this page'
           );
           return false;
       }*/

       $this->post->resName  = $_POST['BLOCK_id'];
       $this->post->pathName = $this->C->Module_Get_pathRes($this,
                               $this->post->resName);
       $this->post->desc = $_POST['desc'];

       //var_dump($this->post);

       Toolbox::Fs_writeTo($this->post->pathName, $this->post->desc);
       //file_put_contents($this->post->pathName, $this->post->desc);

       //return false;
       return true;
    }

    public function _init_()
    {
        parent::_init_();

        if (isset($_REQUEST['save_single'])) {
            $this->savePage();
        }
    }

}
