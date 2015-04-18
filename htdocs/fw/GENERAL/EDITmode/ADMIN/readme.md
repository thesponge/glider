#Template implementation
------------------------------

```html
< * class='allENTS [otherClasses] [entSName]' id = '[entSName]_[LG]' >

      <class='ENT [otherClasses] [entName]' id = '[entName]_[id]_[LG]' >
</*>

<div class='addTOOLSbtn'>
      <input type='button' class='ATmpl' value='more settings' onclick='fmw.toggle(\"form[id^=EDITform] .admin-extras\"); return false;' />
</div>

 < * class='SING [otherClasses] [singName]' id = '[singName]_[id]_[LG]' >
 </*>
```

addTOOLSbtn -  adaugarea de butoane noi

deci un element editabil are din templateul creat urmatoarele proprietati:

+ el.otherClasses
+ el.name
+ el.id
+ el.lang



#Config EDITmode
 -------------------------------

 Pe baza impementarii in template se pot declara cofiguri pentru EDITmode
 configurile ar trebui scrise in public / moduleName / js / *.js


```javaScript
 ivyMods.set_iEdit.sampleMod = function(){

     iEdit.add_bttsConf(
         {
             'ENTname':
             {
                 modName: 'sampleModule'
                 ,edit: {}
                 ,saveBt:  {methName: 'sampleModule->updateMethName'}
                 ,deleteBt: {status:false, methName: 'sampleModule->deleteMethName'}
                 ,addBt: {
                     atrValue: 'un nume',
                     style : 'oric',
                     class : '',
                     status: '',
                     methName: '',
                     async : new fmw.asyncConf({})
                     }

                   // butoane extra pentru toolbarul elementului editabil
                 ,extraButtons:
                 {
                       manageGroup: {
                           callBack: "ivyMods.team.showManageGroups();",
                           attrValue: 'manage Groups',
                           attrName: 'manage Groups',
                           attrType: 'submit/ button',
                           class: ''
                       },
                       buttonName:{}
                 }
                 , extraHtml : ['htmlConetnt ',
                               "<span>" +
                                   "<input type='hidden' name='action_modName' value='user' >" +
                                   "<input type='hidden' name='action_methName' value='deactivateProfile [, other methods]' >" +
                                   "<input type='submit' name='deactivate' value='deactivate' >" +
                               "</span>",
                               '']


             },
             allENTSName : {
                 extraButtons: {}

             },
             'SINGname':
             {
                 // pentru mai multe despre setarea butoanelor in EDITmode see EDITmode.js -> var bttConf
             }
         });
 };
```
**element editabil**

+ elementele editabile au o clasa care incepe cu  ENT sau SING
+ ENT-urile fac parte dintr-un grup de ENT-uri care are o clasa ce incepe cu allENTS
si encapsuleaza toate celelalte elemente ENT
+ SING este un element singular care poate fi editabil
+ pentru orice element editabil pot fi configurate urmatoarele :

    + un module (core->modName) care sa fie managerul pentru inputurile create
    + butoanele default
    + butoanele extra (extraButtons)
    + butoanele extra html (extraHtml)

------------------------

**butoanele default:**

+ addBt
+ saveBt
+ deleteBt
+ edit

**proprietati butoane default:**

+ status : true / false -

    *daca sa fie sau nu afisat in toolbar*
+ methName

    *numele metodei apelate la apasarea acetui buton*
+ atrValue

    *atributul value al butonul*
+ atrName

    *atributul name al butonul*
+ async

    *not yet documented*
+ class

    *not sure if it still works*


**butoane extra (extraButtons)**

+ numele butonului
+ configul acestuia

**butoane extra - config**

butoanele extra pot avea urmatoarele proprietati de config :

+ callBack

    *functie de callback dupa apasarea butonului*
+ attrValue

    *atributul value al butonul*
+ attrName

    *atributul name al butonul*
+ attrType

    *'submit/ button' - tipul de buton *
+ class

    *clasa butonului*



**butoane extra html**

butoane puse efectiv cum sunt scrise (ar trebui totusi encapsulate cu un <span>).
aceste butoane pot avea si alte hiddenturi etc, sunt defapt un block html

din exemplu:

```
"<span>" +
   "<input type='hidden' name='action_modName' value='user' >" +
   "<input type='hidden' name='action_methName' value='deactivateProfile [, other methods]' >" +
   "<input type='submit' name='deactivate' value='deactivate' >" +
"</span>"
```

dupa cum se observa pot exista *action_modname* & *action_methName* care specifica
ce modul va fi managerul si ce metoda a lui



#Convert
------------------------------------------------------


dupa citirea configurilor un element editabil se va transforma astfel

**detaliile - initiale elementului (elD)**

+ elD.otherClasses
+ elD.name
+ elD.id
+ elD.lang

**detaliile - + elementului (elD)**

+ elD.elmContent

    *continutul elementului*


**configurile elementului (BTT)**


```
   "<form action='' method='post' class='"+elD.otherClasses + elD.name +"' id='EDITform_"+elD.id+"' >" +
      "<input type='hidden' name='BLOCK_id' value='"+elD.id+"' />" +
      "<input type='hidden' name='modName' value='"+BTT.modName+"' />" +
      "<input type='hidden' name='methName' value='' />" +

      "<div class='TOOLSem'>" +
           "<div class='TOOLSbtn'>" +
                  //    EXTRAS_TAG +
                  elD.EXTRA_tags +
                  elD.SAVE_tag +
                  elD.DELETE_tag+
                 "<span>" +
                 "   <input type='button'  class='iedit-btt editM-exit' " +
                             "name='EXIT' value='x'" +
                              " onclick=\"iEdit.evCallback.exitEditContent_byName('"+elD.Name+"','"+elD.id+"')\">" +
                 "    <i>Exit</i>" +
                 "</span>" +
           "</div>" +
      "</div>" +
      "<div class='ELMcontent'>" +
          elD.elmContent+
      "</div>"+
   "</form>";
```


#Editable types
--------------------------------------------------

```
    <* class="EDtype otherClasses elmName"> content </*>
```

* Legnda : jqElm - selectorul elmentului editabil*


Orice element are urmatoarele proprietati:

+ jqEDtag        - selectorul elementului editabil
+ INPUTname    - numele inpututului final
+ INPUTclasses - clasele inputului
+ EDtag_width
+ EDtag_height


EDtype poate sa ia urmatoarele valori:

+ EDtxtp
+ EDtxt
+ EDdate
+ EDtxtauto
+ EDtxa
+ EDeditor
+ EDaddEditor
+ EDpic
+ EDaddPic
+ EDsel


###**EDtxt**

```
    <* class="EDtxt otherClasses elmName"> content </*>

    replace with
    <input type='text' name='elmName'  class='otherClasses elmName' value='content' />
```

###**EDdate**

```
    <* class="EDdate otherClasses elmName"> content </*>

    replace with
    <input type='text' name='elmName'  class='otherClasses elmName' value='content' />
```

*callback* - jqEDtag.datepicker();


###**EDsel + *.data-iedit-options***

```
  <* class="EDsel otherClasses elmName"
      data-iedit-options='{{value:'value option1', name: {name option 1}},{},{}}' >
    content
  </*>

  replace with
  <select name='elmName'  class='otherClasses elmName'  />
        <option value='value' [selected]>name</option>
  </select>
```

###**EDeditor +.data-iedit-CKEtoolbar**

```
    <* class= 'EDeditor name'  data-iedit-CKEtoolbar = 'numele toolbarului ales'></*>

    replace with
    "<textarea   name='elmName'  class='otherClasses elmName'  id='editor_elmName_LG' >
        content
    </textarea>"
```

*callback* - va aplica CKEditor pe textarea cu 2 vaiante de toolbar

+ 'defaultSmall'  daca EDtag_width < 500
+ 'default'       daca EDtag_width > 500


###**EDtxtauto**

```
    <* class="EDtxtauto otherClasses elmName"
       data-iedit-source = '{}'
       data-iedit-select = 'multiple / key'
       data-iedit-path = 'pathName'
       data-iedit-minln = 'min characters'

    >
        content
    </*>

    replace with
      <input type='text' name='elmName'  class='otherClasses elmName' value='content' />
```

***callback***  - va apela pe obiect extensia de jquery.ivyAutocomplete(source, minLength, select)
see GEN.js

***configurari posibile***

>**data-iedit_source** [ {'label': 'calatorii', 'value': '1'},{}] sau ['item1', 'item2']
>
>- sursa directa de date
>
>**data-iedit-path** string *MODELS/blog/script.php*
>
>- script care sa returneze o sursa json_encode(array()) ( este relativ la public )
>- scriptul va primi $_POST['searchTerm']  - termenul cautat. astfel se poate scrie
>un<br> *query = "SELECT x from table WHERE x LIKE '%".$_POST['searchTerm']."%' "*;

>**data-iedit-minln** integer

> - minimul de caractere pe care utilizatorul trebuie sa insereze pentru a incepe
>autocompleteul
>
>**data-iedit-select** string [key / multiple]
>
>- *multiple* utilizatorul poate alege mai multe valori
>( acestea vor fi despartite prin virgula )
>- *key* - se refera la un source cu perechi label / value , submitul se va face pe value.
>este util pentru simularea unui tag ```<select>```
><br> acest tip de selectie va adauga un input hidden care sa retina valorile
>

