<?php
/**
 * Metode pe partea de admin
 *
 * Use to:
 * - Metode Locale proiectului
 * - metode care suprscriu metode din core
 * - metode experimentale
 *
 * daca se considera ca metodele vor fi general necesare
 * se trec in Cunstable => Ccore sau in clasa din care fac parte
 *
 */

class ACLcore extends ACcore
{
    /**
     * this validation is not a stable one
     *
     * aceasta metoda este foarte specifica
     * pentru referinta la ce anume foloseste see:
     * - handlePosts ( for : $mod->posts)
     * - Cfeedback
     * - AsampleMod.yml ( for: postExpected - the detailed model )
     *
     *
     * Used sofar by:
     * ACblogSite
     *  _hook_saveProfile()
     *
     *
     * @param $mod           - obiectul care a apelat metoda
     * @param $postExpected detailed ones with a fbk property
     * @param $validPosts
     *
     * @return bool
     */

    function emptyValidation($mod, $postExpected, $validPosts)
    {
        $validPostsArr = explode(',',$validPosts);
        $validation = true;
        foreach($validPostsArr AS $postName){

            $validation &= $mod->posts->$postName ? true :
                           $this->feedback->SetGet_badmessFBK(
                               $postExpected[$postName]['fbk']
                           );
        }
        return $validation;
    }


}
