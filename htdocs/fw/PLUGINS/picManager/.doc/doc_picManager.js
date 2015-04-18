/**
 * WORKING MODEL
 *  <iframe class="cke_wysiwyg_frame" >
 *      ....
 *      <img src='[url]'>
 *  ------------------------------------------
 *  <div class='uploaded_pics well hidden  container-fluid p10' id='".$this->picManager->DB_extKey_name."_".$this->picManager->DB_extKey_value."'>
 *       ...
 *       <div class='image_carousel'>
 *            <div id='kcfinder_div_selPic' class='carousel-content'>
 *               * <span class='thumbPic-manager' id='pic_{~idPic}'>
                     <img src='{~picUrl}'  />
                 * </span>
 *                ...
 *            </div>
 *            ...
 *           <a class='prev disabled' id='cars_prev' href=''><span>prev</span></a>
             <a class='next' id='cars_next' href=''><span>next</span></a>
 *       </div>
 *
 *       <div class='allENTS pics-Details ' id='pics_details_en'>
 *           * <div class='ENT pic-full' id='pic-full_{~idPic}_en'>
 *                      <input type='hidden' name='idRecord' value='".$this->picManager->DB_extKey_value."'>
                         <div class='big-picture'>
                             <img src='{~picUrl}'  />
                         </div>

                         <small><b>Title </b><span class='EDtxt picTitle'>{~picTitle}</span></small>
                         <br>
                         <small><b>Description </b><span class='EDtxa picDescr'>{~picDescr}</span></small>
                         <br>
                         <p class='muted t10 pic-specs'>
                             <small class='r10' ><b>Author</b> <span class='EDtxt picAuth'>{~picAuth}</span></small>
                             <small class='r10' ><b>Location</b> <span class='EDtxt picLoc'>{~picLoc}</span></small>
                             <small class='r10' ><b>Date</b> <span class='EDdate picDate'>{~picDate}</span></small>
                         </p>
              * </div>
 *        </div>
 *  </div>
 *
 * @param src       - url-ul catre poza
 * @param keepStyle -
 */
var picsNr = 3;
var firstPic_id;        // utile pentru comparatii de sfarsit de galerie
var lastPic_id;        // id= 'pic_[idPic]'

var first_pozPic=0;
var countNew = 0;

var JQcar;          //selectia carouselul JQobj
var JQcar_picDet;   // JQ selection picture details


nextPic()
prevPic()

showPic_details(pic_id)
carouselBinds()
carouselStart(startFrom, showPic)
carouselRestart(startFrom, showPic)

carousel_firstStart()
/*===================================[ Managing pictures ]=================================================*/

carousel_deletePic(Name, id)
carousel_addPic(url)
carousel_savePic(Name, id)
/*===================================[Call popUp]=====================================================================*/

callBack_KCFinder()
openKCFinder_popUp()
/*===================================[ pic details- for recordContent]=================================================================================*/

get_picDetails(src)
add_picDetail(src, keepStyle)

$(document).ready();