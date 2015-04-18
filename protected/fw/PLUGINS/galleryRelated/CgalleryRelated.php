<?php
class CgalleryRelated
{
    function Get_baseQuery($idExt)
    {
        $query_alterPics = "
            SELECT
                 picUrl,
                 picTitle,
                 idPic
            FROM relatedPics
            WHERE
              idExt = {$idExt}
            ORDER BY idPic DESC
               ";

        return $query_alterPics;
    }
    function Set_relatedPics($idExt)
    {
        $query = $this->Get_baseQuery($idExt);
        $this->alterPics = $this->C->Db_Get_rows($query);
        $this->firstPic  = (object) $this->alterPics[0];

    }
}