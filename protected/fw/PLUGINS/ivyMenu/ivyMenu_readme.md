
//======================================
//           [Usage]
//======================================

template Usage
    - core->Render_ModulefromRes($core->ivyMenu,'resName');
            'resName' = orice meniu are propriul lui res

    * daca se doreste schimbarea meniului
     $core->ivyMenu->current_idMenu = id-ul meniului

.yml - configuration

    - urmareste sample

public
    - creaza templateul dorit


CivyMenu
    - pentru a crea propriul template de meniu creaza o metoda numita
    iterateMenu_[templateMethod] ($items, $idMenu){}







//=====================================================
//           [Documentation] -  [ working with ]
//======================================================

»» DB tables

    CREATE TABLE IF NOT EXISTS `ITEMS` (
      `id` int(3) NOT NULL AUTO_INCREMENT,
      `type` char(40) NOT NULL,
      `name_ro` text NOT NULL,
      `name_en` text NOT NULL,
      `SEO` text,
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

    CREATE TABLE IF NOT EXISTS `TREE` (
      `Pid` int(3) NOT NULL,
      `Cid` int(3) NOT NULL,
      `poz` int(2) NOT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=latin1;

    CREATE TABLE IF NOT EXISTS `MENUS` (
      `id` int(3) NOT NULL,
      `idM` int(2) NOT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=latin1;



»»» core properties


» table MENUS

id   = ITEMS.id
idM  = id-ul meniului

» table ITEMS

id
type
name_ro
name_en
SEO

» table TREE
Pid = ITEMS.id    # parentID
Cid = ITEMS.id    # childrenID
poz               # pozitie in cadrul tree-ului  

» $c->menus = array( $idM => 'menuName')

» $C->tree[id] = (item - Object)

     $id;              # id curent
     $idParent;            # id parinte;
     $children;        # array( poz=>id_child )


     $name_ro;          # corespondent din BD (titlu[LG])
     $name_en;
     $name;             # name of the current language    RULE:  name    = name[LG]
     $resFile;            # numele fisierului din RES ;     RULE:  resFile  = str_replace(' ', '_' , name)

     $type;             #  tipul modelului / modulului  determina ->   mod.-ul  instantiat
                        #                                         -> js/ csss
    
