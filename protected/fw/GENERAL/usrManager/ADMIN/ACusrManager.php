<?php

class ACusrManager {

    // Variable containing action/name pairs
    private $tabs = array('dashboard' => 'dashboard',
                               'user' => 'users',
                              'group' => 'groups',
                              'class' => 'classes',
                         'privileges' => 'privileges',
                       'experimental' => 'experimental'
                        );
    private $defaultTab = 'dashboard';

    /* {{{ public set_DISPLAY() */
    /**
     * set_DISPLAY
     *
     * @access public
     * @return void
     */
    function set_DISPLAY()  {

      #  $dispPATH = RES_PATH.'PLUGINS/'.$this->lang.'/GEN_edit.html';
      # C->GET_resPath($modType='',$resName='', $modName='' ,$resFile='', $lang = '')

        $dispPATH = $this->C->Get_resPath('GENERAL','usrManager');
       # echo 'dispPATH '.$dispPATH.'<br>';

        if(file_exists($dispPATH))
        {

            $tabBar = '';
            foreach ($this->tabs as $tabAction => $tabName) {
                $class = $tabName == $this->defaultTab ? 'active' : '' ;
                $tabBar .= "<li class='$class'>
                                <a data-toggle='tab' name='$tabAction'
                                    href='#$tabName'>
                                    ".Toolbox::sentenceCase($tabName)."
                                </a></li>";
            }
            $tabDivs = '';
            foreach ($this->tabs as $tabName) {
                $class = $tabName == $this->defaultTab ? 'active' : '' ;
                $tabDivs .= "<div id='$tabName' class='tab-pane $class'>$tabName</div>";
            }

            $html = '
                <div class="container-fluid">
                    <div class="row-fluid">
                    <div class="span10 offset1">
                    <div class="well">
                    <ul class="nav nav-tabs" id="usr-nav">
                        '.$tabBar.'
                    </ul>
                        <div class="tab-content">
                            '.$tabDivs
                            //.'this is a sentence. really? yes! they should be... sentence case :-)<br/>'
                            //.Toolbox::sentenceCase("this is a sentence. really? yes! they should be... sentence case :-)<br/>")
                            //.Toolbox::camelCase("this is a sentence. really? yes! they should be... camelCase :-)<br/>")
                        .'</div>
                    </div>
                    </div>
                    </div>
                    <div id="selector" class="well well-small"><span>'
                    .$_COOKIE['usrManager/selectedName']
                    .' ('
                    .$_COOKIE['usrManager/selectedType']
                    .')</span>
                    <i class="icon-minus pull-right"></i>
                    </div>
                </div>
                ';
            //Console::log('COOKIE', $_COOKIE);
            # $html .= $this->TESTdisplay;  # provenit din $CTRL_changes->TESTdisplay - instanta al CTRL_SV_CHANGES
        }
            file_put_contents($dispPATH,$html);
    }
    /* }}} */

    public function __construct($C){
        $this->C = &$C;
        $this->DB = &$C->DB;

        $C->TOOLbar->ADDbuttons("<input type='button'  value='Manage users' onclick=\"activatePOPUPedit('usrManager','GENERAL')\" >");
        $this->set_DISPLAY();
    }
}
