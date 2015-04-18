<?php

if(isset($_POST['route']) && isset($_POST['action'])) {
    require_once '../../../../../protected/etc/config.php';
    require_once '../../../../../protected/fw/GENERAL/core/classLoader.inc';
    sessionManager::AJAXresumeSession();

    /*{{{ POST switch*/
    switch($_POST['route']) {
    case 'user':
        /* {{{ Users markup */
        echo '
                <script src="fw/GENERAL/usrManager/ADMIN/js/usrManager/usersTable.js" type="text/javascript"></script>
                <table cellpadding="0" cellspacing="0" border="0" class="display" id="usrManager_users">
                    <thead>
                        <tr>
                            <th width="5%" >ID</th>
                            <th width="15%">Username</th>
                            <th width="10%">Class</th>
                            <th width="25%">Full name</th>
                            <th width="35%">Email</th>
                            <th width="5%">Active</th>
                            <th width="5%">Controls</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="5" class="dataTables_empty">Loading data from server</td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
                ';
        /* }}} */
        break;
    case 'group':
        /* {{{ Groups markup */
        echo '
                <script src="fw/GENERAL/usrManager/ADMIN/js/usrManager/groupsTable.js" type="text/javascript"></script>
                <table cellpadding="0" cellspacing="0" border="0" class="display" id="usrManager_groups">
                    <thead>
                        <tr>
                            <th width="20%">ID</th>
                            <th width="25%">Group name</th>
                            <th>Description</th>
                            <th width="10%">Controls</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="2" class="dataTables_empty">Loading data from server</td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
                ';
        /* }}} */
        break;
    case 'class':
        /* {{{ Classes markup */
        echo '
                <script src="fw/GENERAL/usrManager/ADMIN/js/usrManager/classesTable.js" type="text/javascript"></script>
                <table cellpadding="0" cellspacing="0" border="0" class="display" id="usrManager_classes">
                    <thead>
                        <tr>
                            <th width="20%">ID</th>
                            <th width="25%">Class name</th>
                            <th>Groups</th>
                            <th width="10%">Controls</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="2" class="dataTables_empty">Loading data from server</td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
                ';
        /* }}} */
        break;
    case 'dashboard':
        echo 'AJAX-dashboard'."<br/> \r\n";
        echo $_SERVER['REMOTE_ADDR'];
        echo "<br/> \r\n";
        echo $_SERVER['HTTP_USER_AGENT'];
        echo "<br/> \r\n";
        break;
    case 'privileges':
        $privileges = array(
            'system' => array('Add users', 'Edit users', 'Delete users'),
            'site'   => array('Add pages', 'Edit pages', 'Publish/unpublish pages', 'Delete pages'),
            'blog'   => array('Save feedback', 'Edit feedback', 'Approve feedback', 'Delete feedback',
                                'Save articles', 'Edit articles', 'Publish articles', 'Delete articles',
                                'Mute user')
        );
        $toggleValues = array('','on');
        /* {{{ Privileges markup */
        echo '
            <script src="fw/GENERAL/usrManager/ADMIN/js/usrManager/privileges.js" type="text/javascript"></script>
        Privileges<br/>
        <form id="privilegesForm">
            ';

        foreach ($privileges as $section => $values) {
            echo '
                <div class="privileges-section">
                <div style="border-bottom: 1px solid #08c;"><b> ** '.$section.' ** </b><br/></div><br/>
                <table>';
            foreach ($values as $name) {
                $class = array_rand($toggleValues);
                echo '
                <tr> <td>
                    <div class="slider-frame">
                    <span class="slider-button '.$toggleValues[$class].'">
                        <span>'.$class.'</span></span>
                    </div>
                    </td><td>
                    <span class="privileges-checkbox-text">'.$name.'</span>
                    </td> </tr>';
            }
            echo '</table></div>';
        }
        echo '
        <br /> <br /> <br />

        </form>
            ';
        /* }}} */
        //if (authCommon::isAdmin() == TRUE)
            //var_dump($_COOKIE);
        break;
    }
    /*}}}*/
    //var_dump($_COOKIE);
    unset($_POST);

} else
    return 0;
