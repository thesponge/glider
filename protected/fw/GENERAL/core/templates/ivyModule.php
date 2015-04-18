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

class ivyModule extends ivyModule_req
{
    var $modName;
    var $modType;
    var $modDir;

    // from yml settings
    var $template;
    var $template_file;
    var $template_context;
    var $objREQ;
    var $objProps_links;
    var $assetsInc;
    //var $assetsInc_[template_file];
    //var $assetsExtern_[template_file];
}