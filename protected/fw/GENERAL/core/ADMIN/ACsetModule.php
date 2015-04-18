<?php
/**
 *  Overwriteuri la functile din CinitModule pentru varianta de admin
 */
class ACsetModule extends CLcore
{
    public function Set_incFiles($modName,$modType,$extension,$folder='',$template='',$adminFolder='')
    {
        parent::Set_incFiles($modName,$modType,$extension,$folder,$template,'');
        if(isset($this->adminMods[$modName])) {
            parent::Set_incFiles($modName,$modType,$extension,$folder,$template,'ADMIN/');
        }
    }

    public function Module_Fs_configYamlProps(&$mod, $adminPrefix='', $template='')
    {
        parent::Module_Fs_configYamlProps($mod, '', $template);
        parent::Module_Fs_configYamlProps($mod, 'A', $template);
        /*if(isset($this->adminMods[$mod->modName])) {
        }*/
    }

    public function Module_Build($caller, $modName, $modType, $adminFolder='ADMIN/', $adminPrefix='AC')
    {
        if (isset($this->adminMods[$modName]) ) {
           $obj =  parent::Module_Build($caller, $modName,$modType,$adminFolder,$adminPrefix);

        } else {
          $obj =   parent::Module_Build($caller, $modName,$modType);
        }
        return $obj;
    }

    public function Module_Build_objProp($mod, $objName, $adminFolder='ADMIN/', $adminPrefix='A')
    {
        error_log("[ivy]");
        error_log("[ivy] ACsetModule");

        $obj = parent::Module_Build_objProp($mod, $objName, $adminFolder, $adminPrefix);
        // daca nu a reusit sa instantieze un admin type atunci
        //incearca cu varianta simpla
        if(!is_object($obj)) {
            //error_log("[ivy] ACsetModule - Module_Build_objProp ".
            // "Modulul nu are o parte de admin => incercam o intantiere simpla");
           $obj = parent::Module_Build_objProp($mod, $objName);
        }

        if(is_object($obj)) {
            error_log("[ivy] ACsetModule - Module_Build_objProp ".
             "Modulul a fost instantiat cu succes {$objName}");

        } else {
            error_log("[ivy] ACsetModule - Module_Build_objProp ".
                         "Modulul NU a fost instantiat cu succes {$objName}");

        }
        return $obj;
    }

}