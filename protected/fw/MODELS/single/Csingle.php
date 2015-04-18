<?php
/**
 * PHP Version 5.3+
 *
 * @category 
 * @package 
 * @author Ioana Cristea <ioana@serenitymedia.ro>
 * @copyright 2010 Serenity Media
 * @license http://www.gnu.org/licenses/agpl-3.0.txt AGPLv3
 * @link http://serenitymedia.ro
 */

class Csingle
{
    //??? dont know
    var $test = 1;
    /**
     * @var array ("nodeName" => array(
     *              [ hadlers=>'handler1, handlers2,....' ],
     *              template => 'template_name',
     *              template_file => 'templateFile_name'
     *             ), ...)
     */
    var $requests = array();
    var $template;
    // templateFile to include
    var $template_file = '';
    // data provided to the template
    var $data;
    //obiectul handler - care va contine si va prelua datele pt  un anumit template
    var $template_context;

    public function set_tmplFile($template){
        //echo "Something";
        $this->template_file = $template;
        return $this;
    }
    public function set_data($json){
        $jsonDec = json_decode($json);
        $this->data = $jsonDec;
        //var_dump($this);
        return $this->C;
    }
    public function set_tmplContext($contextName){
        $this->template_context = $this->$contextName;
    }

    protected function set_handlers(){
        if(isset($this->requests[$this->nodeResFile])
            && $this->requests[$this->nodeResFile]
        ){
            //echo "Am un request pt pagina  ".$this->nodeResFile."<br>";

            $handler = $this->requests[$this->nodeResFile];
            $this->template_file = $handler['template_file'] ?: $this->template_file;
            $this->template      = $handler['template'] ?: $this->template;

            foreach( $handler['handlers'] AS $handlerName) {
              //  echo "Am un handler  $handlerName <br>";

                $this->$handlerName =  $this->C->Module_Build_objProp($this, $handlerName);
            }
        }
    }
    public function _init_(){
        $this->set_handlers();
        //echo "<br>Instantiat modul spongeb<br>";
    }
}