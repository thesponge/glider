<?php
/**
 * CrenderTmpl
 * @desc Class for templating system
 *
 * @package Core
 * @version 1.0
 * @copyright Copyright (c) 2012 Serenity Media
 * @author  Ioana Cristea
 * @license AGPLv3 {@link http://www.gnu.org/licenses/agpl-3.0.txt}
 */
class CrenderTmpl extends item
{

    var $tmplTypes = array('path'=>'Path','str'=>'Str');

    //==========================================[ independente meth.s ]==========

    public function Get_templateFromPath($tmplPath)
    {
        if ( file_exists(FW_PUB_PATH.$tmplPath)) {
            error_log(
                "[ ivy ] "."CrenderTmpl - Get_templateFromPath: "
                . "Tring to get templatePath: ".$tmplPath
            );
           // echo file_get_contents(FW_PUB_PATH.$tmplPath);
            return file_get_contents(FW_PUB_PATH.$tmplPath);
        } else {
             error_log(
                 "[ ivy ] "."CrenderTmpl - Get_templateFromPath: "
                 . "Nu exista template la calea ". FW_PUB_PATH.$tmplPath
             );
             return '';
        }
    }
    public function Get_templateFromStr($tmplStr)
    {
        if ($tmplStr) {
               return $tmplStr;
        } else {
            error_log(
                "[ ivy ] "."CrenderTmpl - Get_template: "
                ." Nu exista string de template"
            );
            return '';
        }
    }
    public function Get_template( $tmpl, $tmplTypeDef ,$tmplType='')
    {
        // practic acele sufixe spun daca exista sau nu o functie de manager
        // pentru acel tip ( cazul nostru Path & Str
        $tmplTypeSufix = isset($this->tmplTypes[$tmplType])
                    ? $this->tmplTypes[$tmplType]
                    : $tmplTypeDef;

        return $this->{'Get_templateFrom'.$tmplTypeSufix}($tmpl);
    }

    //=========================[ render Assoc ]=================================
    public function Render_assocTmplContent(&$aR, $tmplContent, $obj='')
    {
        // use $ao in templates for easy editing end reading
        $ao = (object) $aR;
        // pentrua a se putea face referinta din cadrul templateului la
        //obiectul principal chemat acest fromArr
        $o  = &$obj;
        $co = $obj->template_context ?: '';

        $display = '';
        eval("\$display = \"$tmplContent\";");
        return $display;
    }
    public function Render_assoc( &$aR, $obj='', $tmplType='str', $tmpl)
    {
        if( (!is_array($aR) || !count($aR) > 0) && !is_object($aR)) {
            error_log("Render_assoc: There are no items ");
            return '';
        }

        $content = $this->Get_template($tmpl,'Str', $tmplType);
        if (!$content) {
           error_log("Render_assoc: Templateul nu a putu"
                    ." fi randat , Check logs ");
           return '';
        }

        return $this->Render_assocTmplContent($aR, $content, $obj);

    }

    //=========================[ render Items ]=================================

    public function Render_itemsTmplContent($items, $tmplContent, $obj='')
    {
        $display = '';
        foreach($items AS $item){
            $display .= $this->Render_assoc($item,$obj,'str',$tmplContent);
        }
        return $display;
    }
    /**
     * Parsare array muldimensional
     *
     * @param array $items           - array-ul de forma array(0 =>[title=>'', lead=> '' , pictureUrl=> '' ] )
     * @param string $tmplStr       - template trimis direct ca string in acest template ne vom referii la
     *                                  -  itemul unui array ca ~ao->propName
     *                                  - optional la $o cu ~o (adta daca este trimis prin $mod)
     *                                  - ghilimelele duble se vor scrie cu `
     * @param string $tmplPath      - path-ul la orice alt template care va folosii acest array
     *                                  - in acest template ne putem referii la variabile in modul normal $ si concatenari cu "
     * @param string $obj           - daca se doreste sa se faca referire in interiorul templateului de array la obiectul principal
     * @return string               - returneaza templateul randat
     */
    public function Render_items ($items, $obj='' , $tmplType='str', $tmpl)
    {
        if(!is_array($items) || !count($items) > 0) {
            return "<i>n/a</i>";

        } else {
            $content = $this->Get_template($tmpl,'Str', $tmplType);
            if (!$content) {
                return $this->debugMess("Render_array: Templateul nu a putu fi
                                          randat , Check logs <br>");
            } else {
                if ($tmplType != 'path') {
                    $content = str_replace(array('~', '`'), array('$','"'), $content);
                }
                return $this->Render_itemsTmplContent($items, $content, $obj);

            }
        }

    }

    //=====================[ render Array ]=====================================

    public function Render_arrayTmplContent($items, $tmplContent, $obj='')
    {
        $display = '';
        // pentrua a se putea face referinta din cadrul templateului la obiectul principal chemat acest fromArr
        $o  = &$obj;
        $co = $obj->template_context ?: '';

        foreach($items AS $key => $i)
        {
            $displayItem = '';
            eval("\$displayItem = \"$tmplContent\";");
            $display .=$displayItem;
        }

        return $display;
    }
    /**
     * Parsare array unidensional
     *  - similar cu Render_items
     */
    public function Render_array($items, $obj='', $tmplType='str', $tmpl)
    {
        if(!is_array($items) || !count($items) > 0) {
            return $this->debugMess("Render_array: There are no items <br>");

        } else {

            $content = $this->Get_template($tmpl,'Str', $tmplType);
            if (!$content) {
                return $this->debugMess("Render_array: Templateul nu a putu fi
                                          randat , Check logs <br>");

            } else {
                if ($tmplType != 'path') {
                    $content = str_replace(array('~', '`'), array('$','"'), $content);
                }
                return $this->Render_arrayTmplContent($items, $content, $obj);

            }
        }

    }


    //=====================[ render Object ]====================================

    public function Render_objectTmplContent($obj, $tmplContent)
    {
        // use $o in templates for easy edit end reading
         $o = &$obj;
         $co = $obj->template_context ?: '';

         $display = '';
         eval("\$display = \"$tmplContent\";");
         return $display;
    }
    /**
     * render Template with mod properties
     * @param $obj
     * @param string $tmplStr
     * @param string $tmplPath
     * @return string
     */
    public function Render_object($obj, $tmplType='path', $tmpl)
    {
        // use in template $obj->varName or $o->varName
        if (!is_object($obj)) {
            return $this->debugMess("Render_bject: obiectul nu exista<br>");
        } else {
            $content = $this->Get_template($tmpl,'Path', $tmplType);
             if (!$content) {
                 error_log("$obj->modName Nu exista continut de template pt <b>$tmpl</b> <br>");
                 return "$obj->modName Nu exista continut de template pt <b>$tmpl</b> <br>";

             } else {
                 $display = $this->Render_objectTmplContent($obj, $content);
                 //error_log("$obj->modName Exista continut de template pt <b>$tmpl</b> <br>". $display);
                 return $display;
             }
        }

    }


    //=====================[ render Module ]====================================
    // Get_path methods
    public function Module_Get_pathTmpl(&$mod, $templateDir, $templateFile)
    {
        return $mod->modDirPub
                    . $templateDir.'tmpl/'
                        . $templateFile.'.html';
    }

    public function Render_Module($mod, $tmplFile='')
    {

        /**
         * Se poate seta un template si cere un anumit fisier de tmpl
         * */
        if (is_object($mod)) {


            if (!$tmplFile) {
                $tmplFile = isset($mod->template_file)
                            ? $mod->template_file
                            : $mod->modName ;
            }

            //daca nu exista nici un template name => nu exista nici un tmpl_dir
            $tmpl_dir = isset($mod->template) &&  $mod->template!=''
                        ? "tmpl_{$mod->template}/"
                        : '' ;

            $tmplPath = $this->Module_Get_pathTmpl($mod, $tmpl_dir, $tmplFile);

            $display = $this->Render_object($mod, 'path',$tmplPath);;


        } else {
           $display = 'There is no mod';
        }

        return $display;

    }

    //=====================================[ fromRES / static content ]==========

    /**
     * Incearca sa returneze continutul static al unui modul
     *  -> daca exista fisier ii returneaza continutul
     *  -> daca nu exista fisier testeaza daca mod este obiect
     *          - daca nu este obiect inseamna ca functia a fost apelata recursiv
     *          - si daca are o metoda de setat continut '_setRes_'
     *                  - daca conditiile sunt indeplinite atunci se seteaza variabila resPath
     *                  - se apeleaza metoda standard _setRes_($resPath)
     *                      daca aceasta metoda returneaza true inseamna ca s-a pus continut  la
     * acea locatie si atunci reapelam get_resContent
     * daca nu inseamna ca a fost o problema si trimitem un mesaj de eroare
     *
     *
     *
    */
    public function get_resContent($resPath, $obj='')
    {
         if(is_file($resPath))
         {
             return file_get_contents($resPath);

         } else {

             if(is_object($obj) && method_exists($obj, '_setRes_'))
             {
                $obj->resPath = $resPath;
                $obj->_setRes_($resPath);
                if (is_file($resPath)) {

                    return file_get_contents($resPath);
                } else {
                   error_log( 'CrenderTmpl - get_resContent : Nu exista continut la pagina <b>'.$resPath.'</b>');
                }

             }
             else {
                 return 'Nu exista continut la pagina <b>'.$resPath.'</b>';
             }
         }
    }

    public function Render_ModulefromRes($obj, $resName='')
    {
        $mod_resPath = isset($obj->resPath)
                       ? $obj->resPath
                       : $this->Module_Get_pathRes($obj, $resName);
        return $this->get_resContent($mod_resPath,$obj);
    }

    //===================[ general controler ]===================================

    /**
     * INCEARCA SA SOLUTIONEZE display-ul pentru un obiect
     *  - mod->_render_()
     *  - mod->Render_Module($mod)
     *
     * @param $obj
     * @return string
     */
    public function Handle_Render($obj)
    {
           if(is_object($obj))
           {
               if(method_exists($obj,"_render_")) {
                   // pentru solutzionarea inhouse a displayului
                   return $obj->_render_();

               } else  {
                  return $this-> Render_Module($obj);
               }

           } else {

               return "CrenderTmpl- Handle_Render: Nu exista obiectul";
           }
     }






//==================[ Aliasuri , specific ]=====================================

    //=========================[ render Assoc ]=================================
    public function Render_assocFromPath($items, $obj='', $tmplPath)
    {
        return $this->Render_assoc($items, $obj,'path', $tmplPath);
    }
    public function Render_assocFromStr($items, $obj='', $tmplStr)
    {
        return $this->Render_assoc($items, $obj,'str', $tmplStr);
    }

    //=========================[ render Items ]=================================
    public function Render_itemsFromPath($items, $obj='', $tmplPath)
    {
        return $this->Render_items($items, $obj,'path', $tmplPath);
    }
    public function Render_itemsFromStr($items, $obj='', $tmplStr)
    {
        return $this->Render_items($items, $obj,'str', $tmplStr);
    }

    //=========================[ render Array ]=================================
    public function Render_arrayFromPath($items, $obj='', $tmplPath)
    {
        return $this->Render_array($items, $obj,'path', $tmplPath);
    }
    public function Render_arrayFromStr($items, $obj='', $tmplStr)
    {
        return $this->Render_array($items, $obj,'str', $tmplStr);
    }

    //=========================[ render Object ]================================
    public function Render_objectFromPath($obj, $tmplPath)
    {
       return $this->Render_object($obj,'path', $tmplPath);
    }
    public function Render_objectFromStr($obj, $tmplStr)
    {
        return $this->Render_object($obj,'str', $tmplStr);
    }





//==============[ Drop all rules- wild ones :) ]================================

    //=========================[ render Assoc ]=================================
    public function Rndr_assocFP($items, $obj='', $tmplPath)
    {
        return $this->Render_assoc($items, $obj,'path', $tmplPath);
    }
    public function Rndr_assocFS($items, $obj='', $tmplStr)
    {
        return $this->Render_assoc($items, $obj,'str', $tmplStr);
    }

    //=========================[ render Items ]=================================
    public function Rndr_itemsFP($items, $obj='', $tmplPath)
    {
        return $this->Render_items($items, $obj,'path', $tmplPath);
    }
    public function Rndr_itemsFS($items, $obj='', $tmplStr)
    {
        return $this->Render_items($items, $obj,'str', $tmplStr);
    }

    //=========================[ render Array ]=================================
    public function Rndr_arrayFP($items, $obj='', $tmplPath)
    {
        return $this->Render_array($items, $obj,'path', $tmplPath);
    }
    public function Rndr_arrayFS($items, $obj='', $tmplStr)
    {
        return $this->Render_array($items, $obj,'str', $tmplStr);
    }

    //=========================[ render Object ]================================
    public function Rndr_objectFP($obj, $tmplPath)
    {
        return $this->Render_object($obj,'path', $tmplPath);
    }
    public function Rndr_objectFS($obj, $tmplStr)
    {
        return $this->Render_object($obj,'str', $tmplStr);
    }

}
