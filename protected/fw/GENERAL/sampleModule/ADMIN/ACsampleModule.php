<?php

/**
 * Refrences:
 * ---------------------
 *
 * core->Handle_postRequest()
 *  - automatic call for a modName->methName that had been passed throught POST
 *
 * GENERAL / Tools / handlePosts
 *  - handlePosts::Get_postsFlexy($this->expectedPosts);
 *  - getting an object of posts
 *
 * PLUGINS / feedback
 *  - setting feedback messegess ( errors, warning, succes )
 */
class ACsampleModule extends CsampleModule{


    /**
     * Auto called
     *
     * Used for:
     * ----------
     *
     * formatarea datelor de post
     * validarea datelor de post
     *
     * Returns:
     * ----------
     *
     * Metoda trebuie sa returneze true / false
     * => datele sunt valide sau nu
     *
     * ##Scenario:
     *
     * Used by core:
     * -------------------
     *
     *  then try to call first:
     *    $core->$_POST['modName']->{'_hook_'.$_POST['methName']}()
     *
     *  if "_hook_" method returns true
     *  then  data valid & proceed caling the method
     *
     */
    /**
     * Scenario for this example:
     *
     * let's say we have: ( from yaml config or not...)
     * $this->expectedPost = array(
     *                          propName1 = > 'postName1'
     *                          propName2 = > ''
     *                          propName3 = > ''
     *                          propName4 = > 'postName4'
     *                          propName5 = > ''
     * );
     * #1
     * then :
     * Get posts with the help of handlePosts::Get_postsFlexy
     * - nomatter what kind of type of expectedpost array you declare it will
     * take care of it an return an object with all the posts you requested in
     * your array
     *
     * $this->posts
     *          ->propName1 = $_POST['postName1']
     *          ->propName2 = $_POST['propName2']
     *          ->propName3 = $_POST['propName3']
     *          ->propName4 = $_POST['propName4']
     *          ->propName5 = $_POST['postName5']
     *
     * #2
     * perform validations
     *
     * #3
     * add to posts for later use
     *
     */
    function _hook_methName()
    {

       #1
       $this->posts = handlePosts::Get_postsFlexy($this->expectedPosts);
       #2
       $validate = true;
       $validate = $this->posts->first_name ? true :
                   $this->C->feedback->SetGet_badmess(
                       'error',
                       'Postul first_name este gol',
                       'Mesaj de eroare'
                   );
       $this->posts->fullName = $this->posts->first_name
                              . ' '
                              . $this->posts->last_name;
        return $validate;
    }

    /**
     * Perform opperations with your posts
     *
     * return true / false => relocate / do not relocate
     *
     */
    function methName(){

        $posts = &$this->posts;
        //$post->postaName;
    }
}