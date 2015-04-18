<?php
/**
 * setarile default pentru un anumit modul
 * reflecta setarile din core.yml si Acore.yml
 * sau dintr-un eventual tabel
 *
 * useCase: $core[$modName]->modType etc...
 *
 * $modType - GENERAL / PLUGINS / MODELS / LOCALS
 * $admin   - 0/1 (nu) are clasa de admin
 * $default - 0/1 (nu) este instantziat default
 * $defaultAdmin -0/1 (nu) este instantziat default in modul admin
 */

class ivyModule_req
{
    // core pointers
    var $C;       // pointer to core
    var $DB;      // data base pointer
    var $lang;    // current language
    var $admin;   // status admin
    var $mgrName; // general module manager Name;
    var $mgrType; // general module manager type

    //node pointers
    var $tree;
    var $idNode;
    var $idTree;

    var $nodeLevel;
    var $nodeResFile;

}