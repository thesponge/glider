
var iEdit = function () {

	// deprecated it should be usede fmw.lang
	var LG = fmw.lang;

	/**
	 * Config ex:
	 *
	 *  elemName_ex: {
     *            modName : '',
     *            addBt: {
     *                atrValue: 'un nume',
     *                style : 'oric',
     *                class : '',
     *                status: '',
     *                methName: 'methName [, otherMethod, ...]',
     *                async : new fmw.asyncConf({
     *                                dataSend: {
     *                                    modName: 'modName',
     *                                    methName: 'methName [, otherMethod, ...]',
     *                                    ajaxReqFile : 'filePath.php'
     *                                },
     *                                callBack : {
     *                                  fn: (typeof fnName != 'undefined'  ? fnName : '') ,
      *                                 context : this,
     *                                  args: []
     *                                },
     *                                restoreCore : 0
     *                            })
     *                },
     *            edit: {},
     *             editCall: {
                     fn: ivyMods.blog.adminAuthors,
                     context: ivyMods.blog
                     //,args : ''
                 },
     *            deleteBt : {methName:'', status : 1, atrName:"delete_"+Name, atrValue: 'd' },
                  saveBt : {methName:'',status : 1, atrName:"save_"+Name, atrValue: 's' },

                  // butoane extra pentru elements
                  // atentie allEnts trebuie sa isi declare singura extraButtons
                  extraButtons:
                  {
                      manageGroup: {
                          callBack: "ivyMods.team.showManageGroups();",
                          attrValue: 'manage Groups',
                          attrName: 'manage Groups',
                          attrType: 'submit/ button',
                          class: '',
                          methName: ''
                      },
                      showAllmembers:{
                          callBack: "ivyMods.team.showAllMembers();",
                          atrValue: 'show all Members',
                          class: ''
                      }
                  }

                  // adaugare de html direct in TOOLS
                  extraHtml: ['htmlConetnt ',
                             "<span>" +
                                 "<input type='hidden' name='action_modName' value='user' >" +
                                 "<input type='hidden' name='action_methName' value='deactivateProfile [, other methods]' >" +
                                 "<input type='submit' name='deactivate' value='deactivate' >" +
                             "</span>",
                             ''];
     *       },
	 *
	 *  Utilizarea configului
	 *
	 *  bttConf[elemName].add['name']
	 *
	 *  bttConf[elemName].add['async']
	 *      .  fnload( jQobj, sendData, callBack_fn)
	 *      .  fnpost( sendData, callBack_fn)
	 *      .  allProperties
	 */
	var bttConf = {
		'defaults': function (Name, elD) {
			return {
				modName: '',
				edit: {
					bttName: 'edit',
					attrValue: 'e',
					attrName: 'edit',
					attrType: 'button',
					methName: '',
					style: '',
					info: 'edit',
					callback: {fn: '', context: this, args: []},
					callbackFull: "iEdit.evCallback.editContent('" + elD.id + "','" + elD.Name + "','" + elD.TYPE + "','" + elD.cls + "'); return false;"
				},
				// new
				deleteBt: {
					bttName: 'deleteBt',

					status: 1,
					attrName: "delete_" + Name,
					attrValue: 'd',
					attrType: 'submit',
					callback: {fn: '', context: this, args: []},
					callbackFull: '',

					methName: ''
				},
				saveBt: {
					bttName: 'saveBt',

					status: 1,
					attrName: "save_" + Name,
					attrValue: 's',
					attrType: 'submit',
					info: '',
					callback: {fn: '', context: this, args: []},
					callbackFull: '',

					methName: ''
				},
				addBt: {
					bttName: 'addBt',

					status: 1,
					style: '',
					attrValue: '+',
					attrName: 'addNewENT',
					attrType: 'button',
					info: 'add new',
					callback: {fn: '', context: this, args: []},
					callbackFull: "iEdit.evCallback.addEnt('" + Name + "');",

					methName: ''
				},
				exitadd: {
					bttName: 'exitadd',

					status: 1,
					style: '',
					attrValue: 'x',
					attrName: 'exit',
					attrType: 'button',
					info: 'exit',
					callback: {fn: '', context: this, args: []},
					callbackFull: "iEdit.evCallback.remove_addNew('" + Name + "'); return false;"

				},
				saveadd: {
					bttName: 'saveadd',

					status: 1,
					style: '',
					attrValue: 's',
					attrName: 'saveAdd_' + Name,
					attrType: 'submit',
					info: 'save new item',
					callback: {fn: '', context: this, args: []},
					callbackFull: '',

					methName: ''

				}
				//extraButtons:{},
				//extraHtml:[],
			}
		},
		 default_extraBtt: function (bttName) {
			return {
				bttName: bttName,

				attrValue: bttName,
				attrName: bttName,
				attrType: 'button',
				methName: '',
				// functie declara pe parti => onclick = 'fn.apply(context, args)'
				callback: {fn: '', context: this, args: []},
				// functie declarata in full => onclick = 'calbackFull'
				callbackFull: ''
			};
		}
	};


	// templates
	var templates = {
		get_bindIvyModule: function (BTT) {

			return "<input type='hidden' name='modName' value='" + BTT.modName + "' />" +
				"<input type='hidden' name='methName' value='' />";
		},

		get_ivyMethRef: function (btt) {

			var methRef = btt.methName == '' ? '' :
				"<input type='hidden' name='action_methName' value='" + btt.methName + "'>";
			return methRef;

		},
		get_button: function (btt) {
			/**
			 *  callbackFull : functie declarata in full
			 *  callback: functie declarata pe parti
			 */
			if (!fmw.isset(btt)) {
				console.log('butonul nu exista');
			}
			// console.log('numele butonului ' + btt.attrName);

			var methName = this.get_ivyMethRef(btt);
			var callback;

			if (btt.callbackFull != '') {
				callback = btt.callbackFull;

			}
			/* if(btt.callback.fn != '') {

			 callback += btt.callback.fn+".apply("+
			 btt.callback.context+","+
			 btt.callback.args + ");"
			 }*/

			return "<span>" +
				methName +
				"<input type='" + btt.attrType + "' " +
				" class='iedit-btt' " +
				" name='" + btt.attrName + "' " +
				" value='" + btt.attrValue + "' " +
				" onclick=\"" + callback +
				" \">" +
				(!btt.info ? '' : "<i>" + btt.info + "</i>") +
				"</span>";
		},
		get_extraButtons: function (buttons) {
			var htmlButtons = '';
			for (var key in buttons) {

				var extraBtt = bttConf.default_extraBtt(key);
				$.extend(true, extraBtt, buttons[key]);

				htmlButtons += templates.get_button(extraBtt);

			}
			return htmlButtons;
		},

		get_editTools: function (elD) {
			return  "<div class='TOOLSem iedit-tools' style='display: none;'>" +
				"<div class='TOOLSbtn'>" +
				templates.get_button(elD.BTT.edit);
			/*"   <span>" +
			 "       <input type='button' class='iedit-btt' "
			 + elD.BTT.style
			 + " name='EDIT' "
			 + " value='"+elD.BTT.atrValue+"'"
			 + " onclick=\"iEdit.evCallback.editContent('"+elD.id+"','"+elD.Name+"','"+elD.TYPE+"','"+elD.cls+"'); return false;\">" +
			 "       <i>Edit Content</i>" +
			 "   </span>" +*/
			"</div>" +
			"</div>";
		},

		get_editForm: function (elD) {

			return  "" +
				"<form action='' method='post' class='" + elD.cls + "' id='EDITform_" + elD.id + "' >" +
				"<input type='hidden' name='BLOCK_id' value='" + elD.id + "' />" +
				(!fmw.isset(elD.BTT.modName) ? ''
					: templates.get_bindIvyModule(elD.BTT)) +

				"<div class='TOOLSem iedit-tools-edit'>" +
				"<div class='TOOLSbtn'>" +
				elD.EXTRA_tags +
				(!fmw.isset(elD.BTT.extraButtons) ? ''
					: templates.get_extraButtons(elD.BTT.extraButtons) ) +

				(!elD.BTT.saveBt.status ? ''
					: templates.get_button(elD.BTT.saveBt)) +

				(elD.TYPE != 'ENT' || !elD.BTT.deleteBt.status ? ''
					: templates.get_button(elD.BTT.deleteBt)) +
				"<span>" +
				"    <input type='button'  class='iedit-btt editM-exit' " +
				"name='EXIT' value='x'" +
				" onclick=\"iEdit.evCallback.exitEditContent_byName('" + elD.Name + "','" + elD.id + "')\">" +
				"    <i>Exit</i>" +
				"</span>" +
				"</div>" +
				"</div>" +
				"<div class='ELMcontent'>" +
				elD.elmContent +
				"</div>" +
				"</form>";
		},
		get_addTools: function (elD) {

			var buttons = (!fmw.isset(elD.BTTall.extraButtons) ? ''
				: templates.get_extraButtons(elD.BTTall.extraButtons));
			buttons += !elD.BTTadd.status ? '' :
				templates.get_button(elD.BTTadd);


			var toolsEm = buttons == '' ? '' :
				"<div class='TOOLSem iedit-tools-add'>" +
					"<div class='TOOLSbtn'>" +
					buttons +

					"</div>" +
					"</div>";
			return toolsEm;
		},
		get_addForm: function (elD) {

			return "<form action='' method='post' class='" + elD.FORM_class + "'   id='" + elD.FORM_id + "' style='display: none;'>" +
				elD.html_ctrlAction +
				"<div class='TOOLSem'>" +
				"<div class='TOOLSbtn'>        " +
				templates.get_button(elD.BTT.saveadd) +
				templates.get_button(elD.BTT.exitadd) +
				/*"     <span>" +
				 "<input type='submit' class='iedit-btt'   name='saveAdd_"+elD.nameENT+"' value='s' />" +
				 "<i>save</i>" +
				 "</span>                         " +
				 "     <span>" +
				 "<input type='button' class='iedit-btt'   name='EXIT' value='x' onclick=\"iEdit.evCallback.remove_addNew('"+elD.nameENT+"'); return false;\">" +
				 "<i>Exit</i>" +
				 "</span>       " +*/
				"     </div>          " +
				"</div> " +
				"<div class='ELMcontent'>" +
				elD.FORM_content +
				"</div>" +
				"</form>";
		}
	};

	var sel = {

		editForm: function (elmName, elmIndex, context) {
			return (fmw.isset(context) ? context + ' ' : '') +
				'form[class$=' + elmName + '][id=EDITform_' + elmIndex + '] ';
		},
		addForm: function (elmName, elmIndex, context) {
			return (fmw.isset(context) ? context + ' ' : '') +
				'form[class^=addForm][class$=' + elmName + '] ';
		},
		bttDefault: function (actionName, elmName) {
			return '.TOOLSbtn input[class^=iedit-btt][name=' + actionName + '_' + elmName + '] '
		},
		editableElm: function (elmName, elmIndex) {
			return "*[id^=" + elmName + "_" + elmIndex + "_] ";
		},
		editableTags: "*[class^=ED] ",
		editableElmTags: function (elmName, elmIndex) {

			return this.editableElm(elmName, elmIndex) + sel.editableTags + ' ';
		}

	};
	var reconstruct = {
		saveBt: function (Name, id, postData) {
			var test = '';
			$(sel.editableElmTags(Name, id))
			.map(function () {

				var EDname = $(this).attr('class').split(' ').pop();
				$(this).html(postData[EDname]);

				test += EDname + ' = ' + postData[EDname] + ' \n \n';
			});
			//console.log("iEdit - async_save_reconstruct " + test);
		},
		deleteBt: function (Name, id, postData) {
			$("*[id^=" + Name + "_" + id + "]").remove();
		},
		addBt: function (Name, id, postData, dataReply) {
			/**
			 * DOC:
			 * datReply - shoul by the new id of the item ooor?
			 */
			// goleste formularul de add
			if(typeof dataReply == 'undefined') {
				console.log('reconstruct.addBt : atentie nu s-a trimis nici un id');
				return;
			}

			$(sel.addForm(Name))
				.hide()
				.find('*[class^=ED]').attr('value', '');

			var firstEnt =  $(sel.addForm(Name))
				               .next("*[class^=ENT]");

			var newId = dataReply;
			var content = firstEnt.find('.ELMcontent').html();

			firstEnt
				 .clone()
				 .attr('id', Name+'_'+newId+'_'+LG)
				 .html(content)
				 .insertBefore(firstEnt);

			// adauga toolurile pentru acest ent
			iEdit.init.tools_elm(firstEnt.prev('*[class^=ENT]'));
			this.saveBt(Name, newId, postData);

		}

	};
	//========================================[ PROTECTED FUNCTIONS ]===========

	// helpers, management
	/**
	 * ret : JSON - button config of an editable element
	 *
	 * @param Name      - the name of the editable element
	 * @param defaults  - a JSON object of defaults in case no confing was made
	 * @return {*}      - JSON - button config of an editable element
	 */
	function getBtt(Name, elD) {

		var defaults = bttConf.defaults(Name, elD);
		if (fmw.isset(bttConf[Name])) {
			return  $.extend(true, defaults, bttConf[Name]);
		} else {
			return defaults;
		}
	}

	// ============================================[ elementary ]===============
	// elD's
	// for editContent
	function get_elementEdited(id, Name, TYPE, cls) {

		var elD = {};

		elD.id = id;
		;
		elD.Name = Name;
		elD.TYPE = TYPE;
		elD.cls = cls;


		elD.BLOCK = $(sel.editableElm(Name, id));
		elD.BTT = getBtt(Name, elD);
		// from '.addTOOLSbtn'
		var EXTRA_htmlTags = function () {
			/**
			 ATENTIE!!! POATE AR TREBUI SA GASESC O METODA MAI PUTIN COSTISITOARE
			 *
			 * DESCRIERE -
			 * daca inaintea unui element avem definit un elemnt.addTOOLSbtn
			 * - acesta va contine butoanele EXTRA pentru TOOLSbtn
			 * se adauc butoanele la forma standard pentru TOOLSbtn
			 *
			 * Utilitate:
			 *  - este util sa las butoanele in cadrul templateului pentru
			 *  cazuri in care butoanele sunt conditionate de php prin template
			 *
			 * */
			var tag = '';
			var EXTRA_btns = elD.BLOCK.prevAll('.addTOOLSbtn');
			if (!EXTRA_btns.length)                          //daca nu gaseste butoane extra sa zicem la inceputul lui allEnts
				EXTRA_btns = elD.BLOCK.prev('.addTOOLSbtn');   // incearca sa caute butoane inaintea entului curent

			if (EXTRA_btns.length) {

				EXTRA_btns.find('input').addClass('iedit-btt').wrap("<span>");
				tag = EXTRA_btns.html();
			}

			return tag;


		}();
		var EXTRA_html = function () {

			if (typeof elD.BTT.extraHtml == 'undefined') {
				/*console.log('editContent - EXTRA_html(): ' +
					'NU Avem extraHtml pt' + Name);*/
				return '';
			} else {

				//console.log('editContent - EXTRA_Html():' + ' Avem extraHtml pt ' + Name);

				var html = '';
				for (var key in elD.BTT.extraHtml) {
					/* console.log('editContent - EXTRA_Html():' +
					 ' extraHtml = '+ BTT.extraHtml[key]);*/
					html += elD.BTT.extraHtml[key];
				}
				return html;
			}

		}();

		elD.EXTRA_tags = EXTRA_htmlTags + EXTRA_html;
		elD.elmContent = elD.BLOCK.find('.ELMcontent').html();

		return elD;

	}

	// for addElement
	function get_elementToAdd(firstENT, allEnts) {
		var elD = {};

		if (firstENT.length != 0) {

			elD.classes = firstENT.attr('class');
			elD.TYPEarr = elD.classes.split(' ');
			//ENT || SING - restul claselor fara denumirea de ENT sau SING
			elD.cls = elD.classes.replace('ENT', '');
			//ENTname || SINGname - numele ENT-ului se afla la pus ca ultima clasa a Elementului
			elD.id = 0;
			elD.Name =
			elD.nameENT =
				elD.TYPEarr[ elD.TYPEarr.length - 1];
			elD.FORM_content = firstENT.find('.ELMcontent').html();
			elD.FORM_class = "addForm " + elD.cls;
			elD.FORM_id = "new_" + elD.nameENT + '_' + LG;
			// buttons settings
			elD.BTT = getBtt(elD.nameENT, elD);
			elD.BTTadd = elD.BTT.addBt;


			elD.html_ctrlAction = function () {

				if (elD.BTT.modName != 'undefined') {
					return  "<input type='hidden' name='modName' value='" + elD.BTT.modName + "' />" +
						"<input type='hidden' name='methName' value='" + elD.BTTadd.methName + "' />";

				}
				return '';
			}();

		} else {
			elD.BTT = {status: false};
		}


		var allEntsClss = allEnts.attr('class').split(' ');
		var allEntsName = allEntsClss[allEntsClss.length - 1];
		//console.log('allEntsName = '+ allEntsName);

		elD.BTTall = getBtt(allEntsName, elD);

		return elD;

	}

	// for init:tools
	function get_elementToEdit(elm) {

		/**
		 * UTILIZARE GENERALA EDITmode.js
		 *
		 * < * class='allENTS [otherClasses] [entSName]' id = '[entSName]_[LG]' >
		 *     - add new ent
		 *
		 *     <class='ENT [otherClasses] [entName]' id = '[entName]_[id]_[LG]' >
		 *
		 *  - delete
		 *  - edit
		 *  - save
		 *  - exit edit (cancel)
		 *
		 *  < * class='SING [otherClasses] [singName]' id = '[singName]_[id]_[LG]' >
		 *      - edit
		 *      - save
		 *      - exit edit (cancel)
		 */

		var elD = {};
		var desc = elm.attr('id').split('_');
		var classes = elm.attr('class');
		var TYPEarr = classes.split(' ');

		elD.Name = desc[0];
		elD.id = desc[1];
		elD.TYPE = TYPEarr[0];
		elD.cls = classes.replace(elD.TYPE, '');

		elD.BTT = getBtt(elD.Name, elD);

		return elD;
	}

	// ===========================================[ binds to action ]===========
	// 2
	function async_actionBind(formSel, elD, actionName, actionBtt) {

		if(typeof elD.BTT[actionBtt] == 'undefined'
		  ||  typeof elD.BTT[actionBtt].async != 'object'
	   ) {
			//console.log("async_actionBind : Nu exista async pt "+ actionName);
			return;
		}
		// trebuie sa putem selecta formul aferent in care se afla butonul
		if (typeof sel[formSel] != 'function'){
			console.log("async_actionBind : Nu exista metoda de selectare a formului "
				+ typeof sel[formSel]);

			return;
		}

		// daca toate au mers bine...
		/*console.log("async_actionBind \n" +
			" formSel = "+formSel + "\n" +
			" Name = "+elD.Name + "\n" +
			" actionName = "+actionName + "\n" +
			" actionBtt = "+actionBtt + "\n"
		);*/

		$(sel[formSel](elD.Name, elD.id))
			.find(sel.bttDefault(actionName, elD.Name))
			.attr(
				'onclick',
				"iEdit.evCallback.async_edit('" + elD.Name + "','" + elD.id + "', '" + formSel + "', '" + actionBtt + "' ); return false;");

	}

	// 2
	function ivyMethod_actionBind(editForm, bttName, methName, modName) {
		//console.log('EDITmode - actionBtns_binds: '+modName);

		editForm
			.find('.TOOLSbtn input[name=' + bttName + ']')
			.on('click', function () {
				// alert('bttName = '+bttName+' methName = '+methName);
				editForm
					.find("input[name=methName]")
					.first()
					.attr('value', methName);

				if (typeof modName != 'undefined' && modName == '') {
					editForm
						.find("input[name=modName]")
						.first()
						.attr('value', modName);
				}

			});
	}

	// 1 but is only for editing
	function actionBtns_binds(elD, editForm, tools) {

		//console.log("actionBtns_binds");

		if (typeof elD.BTT.modName != 'undefined') {

			/**
			 * WORK with
			 * "<span>" +
			 "<input type='hidden' name='action_methName' value='deleteProfile'>" +
			 "<input type='submit' name='deleteProfile' value='delete profile' />" +
			 "</span>"

			 * see: templates.get_extraButtons
			 */
			tools
				.find(' input[name=action_methName]')
				.map(function () {
					var bttName = $(this).siblings('input[type=submit]').attr('name');
					var methName = $(this).attr('value');
					// optional change modName
					var modName = $(this).siblings('input[name=action_modName]').attr('value');
					/*console.log('EDITmode - actionBtns_binds: '+
					 ' bttName = ' + bttName +
					 ' methName = ' + methName +
					 ' modName = '+modName);*/
					ivyMethod_actionBind(editForm, bttName, methName, modName);
				});

		}



	}

	// ==========================================[ live edit ]==================
	function transform(BLOCK, formSelector) {

		$('*[classs$=hoverZoomLink]').removeClass('hoverZoomLink');

		//BLOCK.find('*[class^=ED]').map(function () {
		/**
		 * Daca se mai doreste facuta vreo operatie pe dom inaintea editarii
		 * acestuia , ar trebui facuta pe contentul a ce urmeaza sa se editeze
		 * - adica pe selectorul formului si nu pe SING-ul sau ENT-ul in sine
		 */
		$(formSelector).find('*[class^=ED]').map(function () {
			// selELEM =  $(this).attr('class')+' ';
			if ($(this).parents('*[class^=allENTS]').length <= 1) {

				var EDclass = $(this).attr('class');
				//EDclass  = $.trim(EDclass);

				//console.log(EDclass);
				//var desc   = ($(this).attr('class')+' ').split(' ');
				var desc = (EDclass).split(' ');
				var EDtype = desc[0];
				/**
				 * (-2)  1- este ca sa imi ajunga la 0 si inca 1 pentru ca
				 * pune un elem in plus , nu stiu de ce
				 * @type {*}
				 */
				var EDname = desc[desc.length - 1];
				var EDvalue = ( EDtype == 'EDeditor' || EDtype == 'EDpic' )
					? $.trim($(this).html())
					: $.trim($(this).text());


				//console.log('EDtype '+EDtype+'  EDname '+EDname+' value '+EDvalue);
				var EDtag = formSelector + ' *[class^=' + EDtype + '][class$=' + EDname + ']';
				var jqEDtag = $(EDtag);

				// perform the actual transform of the element
				if (jqEDtag.length > 0) {
					replace(EDtype, EDname, EDvalue, formSelector, jqEDtag);
				} else {
					console.log(EDtag);
				}

			}

		});
	}

	function replace(EDtype, EDname, EDvalue, formSelector, jqEDtag) {

		//alert(EDtype+' '+EDname+" "+EDvalue+" "+formSelector);

		// var EDtag        = formSelector + ' *[class^='+EDtype+'][class$='+EDname+']';
		//var INPUTname    = EDname+"_"+LG;
		var INPUTname = EDname;
		var INPUTclass = 'EDITOR ' + EDname;

		var INPUTclasses = jqEDtag.attr('class').replace(EDtype, 'EDITOR');
		var EDtag_height = jqEDtag.height();
		var EDtag_width = jqEDtag.width();

		var jqEDinput = {}; // selectorul inputului cu care s-a facut replaceul

		var EDreplace = {
			EDtxtp: function () {
				return "<input type='text' name='" + INPUTname + "'  class='" + INPUTclasses + "' value='" + EDvalue + "' placeholder='" + EDname + "' />";
			},

			EDtxt: function () {
				return "<input type='text' name='" + INPUTname + "'  class='" + INPUTclasses + "' value=\"" + EDvalue + "\" />";
			},
			EDdate: function () {
				return "<input type='text' name='" + INPUTname + "'  class='" + INPUTclasses + "' value='" + EDvalue + "' />";
			},
			EDtxtauto: function () {

				return "<input type='text' name='" + INPUTname + "'  class='" + INPUTclass + "' value='" + EDvalue + "' />";
			},

			EDtxa: function () {
				return "<textarea   name='" + INPUTname + "'  class='" + INPUTclasses + "' >" + EDvalue + "</textarea>";
			},
			EDeditor: function () {
				return "<textarea   name='" + INPUTname + "'  class='" + INPUTclasses + "'  id='editor_" + EDname + '_' + LG + "' >"
						+ EDvalue
					+ "</textarea>";
			},
			EDaddEditor: function () {
				return "<textarea   name='" + INPUTname + "'  class='" + INPUTclasses + "'  id='editorAdd_" + EDname + '_' + LG + "' >" + EDvalue + "</textarea>";
			},
			EDpic: function () {
				//var  IDpr = $('input[name=IDpr]').val();
				// if(typeof IDpr!='undefined') INPUTname='';
				//(form,url_action,id_element,html_show_loading,html_error_http)
				/* $('form[id^=EDITform]').attr('enctype','multipart/form-data');
				 $('form[id^=EDITform]').attr('encoding','multipart/form-data');
				 return "<div class='"+INPUTclass+"' id='frontpic'>" +
				 EDvalue+
				 "<div  id='formUPL' >"+
				 "<input type='file' id='fileUPL' name='filename_"+INPUTname+"' class='fileinput'  />" +
				 "<input type='hidden' name='id' value='"+IDpr+"'>" +
				 */
				/* "<input type='submit' name='UPLDimg' value='UP'>"+*/
				/*
				 "</div>" +
				 "</div>"
				 ;*/
				var imgSrc = jqEDtag.attr('src');

				/*alert(EDtag + ' '+ $(EDtag+"[src*=placehold.it]").attr('src') );*/
				var hiddenValue = imgSrc.search("placehold") > 0
					? ''
					: imgSrc;

				// alert('hiddenValue is '+hiddenValue+ ' '+imgSrc.search("placehold"));
				//return  "<img class='"+imgClasses+"' src='"+imgSrc+"'>";

				return   "<img class='" + INPUTclasses + "' src='" + imgSrc + "' id='editImg_" + INPUTname + "'>" +
					"<input type='hidden' name='" + INPUTname + "' value='" + hiddenValue + "' />" +
					"<input type='button' name='replaceImg' value='loadImg' " +
					" style='left: 0;position: absolute;'" +
					" onclick='iEdit.evCallback.loadPic(\"editImg_" + INPUTname + "\")'>";


			},
			EDaddPic: function () {
				var imgSrc = jqEDtag.attr('src');

				//return  "<img class='"+imgClasses+"' src='"+imgSrc+"'>";

				return   "<img class='" + INPUTclasses + "' src='" + imgSrc + "' id='editAddImg_" + INPUTname + "'>" +
					"<input type='hidden' name='" + INPUTname + "' value='' />" +
					"<input type='button' name='replaceImg' value='loadImg' " +
					" style='left: 0;position: absolute;'" +
					" onclick='iEdit.evCallback.loadPic(\"editAddImg_" + INPUTname + "\")'>";
			},
			EDsel: function () {

				/**
				 * This type of element requiers the next formula :
				 *
				 * <* class = 'EDsel'
				 *      data-iedit-options='{{value:'value option1', name: {name option 1}},{},{}}' >
				 * </*>
				 * @type {*}
				 */
				var options = jqEDtag.data('ieditOptions');

				var htmlOptions = '';
				for (var key in options) {

					var selected = options[key].name != $.trim(EDvalue) ? '' : 'selected';
					htmlOptions += "<option value='" + options[key].value + "' " + selected + ">" +
						options[key].name +
						"</options>";
				}

				var htmlSelect = "<select name='" + INPUTname + "'  class='" + INPUTclasses + "'>" +
					htmlOptions +
					"</select>";

				//console.log("EDsel " + htmlSelect);
				return htmlSelect;
			}
		};


		var EDcallback = {
			/**
			 * work with elements like:
			 *
			 * <* class= 'EDeditor name'  data-iedit-cketoolbar = 'numele toolbarului ales'></*>
			 * @constructor
			 */
			EDeditor: function () {


				// daca elementul are un toolbar declarat
				var toolbarName = jqEDtag.data('ieditCketoolbar');
				if (typeof  toolbarName == 'undefined' || toolbarName == '') {

					toolbarName = (EDtag_width < 500 ? 'defaultSmall' : 'default' );
				}
				CKEDITOR.replace('editor_' + EDname + '_' + LG,
					{
						toolbar: toolbarName,
						height: EDtag_height + 'px', width: EDtag_width
					}
				);
				//$("textarea[id=editor_"+EDname+'_'+LG+"]").ckeditor();

			},
			EDaddEditor: function () {
				var toolbar_conf = (EDtag_width < 500 ? 'EXTRAsmallTOOL' : 'smallTOOL' );
				CKEDITOR.replace('editorAdd_' + EDname + '_' + LG,
					{
						toolbar: toolbar_conf,
						height: EDtag_height, width: EDtag_width
					});
				//$("textarea[id=editor_"+EDname+'_'+LG+"]").ckeditor();

			},
			EDtxa: function () {
				$(formSelector + ' textarea[name=' + INPUTname + ']')
					.css('height', EDtag_height)
					.css('width', EDtag_width);
			},
			EDdate: function () {
				$(formSelector + ' input[name=' + INPUTname + ']').datepicker({dateFormat: 'yy-mm-dd'});
			},
			EDtxtauto: function () {

				/**
				 * Work with:
				 *<* class="EDtxtauto otherClasses elmName"
				 data-iedit-source = '{}'
				 data-iedit-select = 'multiple / key'
				 data-iedit-path = 'pathName'
				 data-iedit-minln = 'min characters'

				 >
				 content
				 </*>
				 * @type {*}
				 */
				var minLength = jqEDtag.data('ieditMinln');
				var path = jqEDtag.data('ieditPath');
				var source = jqEDtag.data('ieditSource');
				var select = jqEDtag.data('ieditSelect');


				// daca sursa este o cale
				if (typeof path != 'undefined') {
					source = {
						scriptPath: fmw.ajaxProxy,
						sendData: {ajaxReqFile: path}
					};
				}

				// daca am reusit sa determinam o sursa pentru autocomplete
				if (typeof source == 'object') {
					$('input[name=' + INPUTname + ']')
						.ivyAutocomplete(source, minLength, select);
				} else {
					console.log("EDITmode - EDtxta : Nici o sursa nu a fost preluata "
						+ typeof  source
					);
				}


			}
		};

		//alert(typeof EDtxtauto[EDtype]);
		if (eval('typeof ' + EDreplace[EDtype]) == 'function') {

			jqEDtag.replaceWith(EDreplace[EDtype]());
			jqEDinput = $('input[name=' + INPUTname + ']');

			if (eval('typeof ' + EDcallback[EDtype]) == 'function') {
				EDcallback[EDtype]();
			}

		} else {
			alert('EDITmode nu s-a gasit functie pentru EDtype ' + EDtype + '\n EDname = ' + EDname);
		}


	}


	//========================================[ PUBLIC FUNCTIONS ]==============
	return {

		bttConf: bttConf,

		add_bttConf: function (bttName, bttName_conf) {

			/**
			 * ATENTIE  trebuie sa ridic un semn de intrebare? oare aceasta variabila nu ar fii mai bine sa fie publica
			 * */
			/**
			 * ma refer la o variabila locala a obiectului (privata) iEdit
			 * aceasta variabila nu poate fii accesata din afara lui
			 * */

			/*// tests
			 console.log('bttName = ' + bttName +' elemente = '+ bttName_conf.length);
			 for(var optType in bttName_conf) {
			 console.log('optType = '+ optType  + ' cu elemente = '+ bttName_conf[optType].length);
			 }
			 console.log(' ');*/
			bttConf[bttName] = bttName_conf;
		},
		add_bttsConf: function (btts) {

			/**
			 * Deci ma pot referii la cine?*/
			// alert(typeof this.add_bttConf);   // = function
			for (var bttName in btts)  this.add_bttConf(bttName, btts[bttName]);
		},

		init: {
			// we can refer to init as this. because is a named object
			set_iEdit: function () {

				//@todo: and i shouldn need to do that
				LG = $("input[name=lang]").val();  //Need to get the current LG;

				this.modsSet_iEdit();
				//===============[set tools]=============================
				this.start_iEdit();

				$("*[class^=ENT] , *[class^=SING]").live({
					mouseover: function () {
						$(this).not('form').find('.TOOLSem:first').show();
					},
					mouseout: function () {
						$(this).not('form').find('.TOOLSem').hide();
					}
				});

			},
			start_iEdit: function (context) {
				context = (typeof  context == 'undefined') ? '' : context;
				this.tools(context);
				this.tools_addEnt(context);

			},
			modsSet_iEdit: function () {

				/**
				 * daca un anumit modul are de facut setari pentru editMode
				 * poate sa appenduiasca functile care fac setariile la fmw.set_iEdit_localSetting
				 *
				 * */
				if (typeof ivyMods.set_iEdit != 'undefined') {

					var ivyModsFns = ivyMods.set_iEdit;
					for (var fnKey in ivyModsFns) {
						if (typeof ivyModsFns[fnKey] == 'function') {

							ivyModsFns[fnKey].call();
						}
					}
				}
			},
			tools_elm : function(jqElm){
				var elD = get_elementToEdit(jqElm); //from element Details
				var tools = templates.get_editTools(elD);
				// pentru a putea recupera continutul
				jqElm.wrapInner("<div class='ELMcontent' />");
				jqElm.prepend(tools);
			},
			tools: function (context) {

				$(context + " *[class^=SING]," + context + " *[class^=ENT]").map(function () {
					iEdit.init.tools_elm($(this));
				});

			},

			tools_addEnt: function (context) {

				function prepare_addForm(elD, addForm) {
					// *** ATENTIE trebuie golita si imaginea
					/**
					 * Se golesc toate fieldurile editabile
					 * apoi se pune o alta clasa fieldurilor editabile - cu CKeditor pentru a
					 * nu mai trebui sa distrug instantele de CKeditor pentru add-uri
					 *
					 * */
						// probabil ca cele 2 ar trebuii inlantuite

					addForm
						.find('*[class^=ED]')
						.empty();
					//  .find('*[class^=ED]').not('*[class=EDpic]').empty();

					addForm
						.find('*[class^=EDeditor]')
						.map(function () {
							var classEditor = $(this).attr('class');
							var classEditor = classEditor.replace('EDeditor', 'EDaddEditor');
							$(this).attr('class', classEditor);

						});

					/**
					 * Pentru formularul de adaugare de pic se creeaza un alt tip de ED
					 * EDaddPic
					 * */
					addForm
						.find('*[class^=EDpic]')
						.map(function () {
							var classEditor = $(this).attr('class');
							var classEditor = classEditor.replace('EDpic', 'EDaddPic');

							var imgHeight = $(this).height();
							var imgWidth = $(this).width();

							/**
							 * daca imaginea nu are width sau height atunci se presupune ca parintele care
							 * contine imaginea va avea unul din cele doua
							 */
							if (!imgHeight) imgHeight = $(this).parent().height();
							if (!imgWidth) imgWidth = $(this).parent().width();

							$(this)
								.attr('class', classEditor)
								.attr('src', "http://placehold.it/" + imgWidth + "x" + imgHeight + '&text=add%20Image');
							// $(this).css('background','gray');
						});

					//  alert( $('form[class^=addForm]').find('a').length);
					// nu imi e foarte clar de ce nu se foloseste selecta addForm
					$(sel.addForm(elD.nameENT))
						.find('a')
						.on('click', function () {
							return false;
						});
					// KK
					$(sel.addForm(elD.nameENT))
						.find('a[rel=alterPics_group] >img')
						.unwrap();

				}

				$(context + ' *[class^=allENTS]')
				.map(function () {

					var allENTS  = $(this);
					var firstENT = $(this).find('*[class^=ENT]:first');
					var elD      = get_elementToAdd(firstENT, allENTS); //from element Details
					var tools    = templates.get_addTools(elD);

					// console.log('addForm selector = '+context+" #"+elD.FORM_id);
					if (tools) {
						allENTS.prepend(tools);
					} else {
						//console.log("Atentie nu exista tools pt add");
					}

					//@todo: propunere de investigat
					/**
					 * propunere :
					 * if (firstENT.length == 0 || !elD.BTTadd.status)
					 * sau si mai si
					 *
					 * (firstENT.length == 0 || !elD.BTTadd.status) && return;
					 *
                */
					// daca sunt elemente in cadrul allENTS
					if (firstENT.length != 0 && elD.BTTadd.status) {
						$.when(
							firstENT.before(templates.get_addForm(elD))
						).then(function () {

							var addForm = $(sel.addForm(elD.nameENT, context));
							/**
							 * Daca nu exista ENTS visible inseamna ca nu a fost
							 * adaugat nici un ENT => trebuie sa apara TOOLSem din
							 * start , fara mouse over
							 */
							var countENTS = allENTS.find('*[class^=ENT]:visible').length;
							if (countENTS == 0) {
								addForm.prev('.TOOLSem')
									.css('visibility', 'visible');
							}
							prepare_addForm(elD, addForm);
							async_actionBind('addForm',   elD, 'saveAdd', 'addBt');
							transform(addForm, sel.addForm(elD.nameENT, context));
							//console.log('addForm id = ' + addForm.attr('id'));
						});
					}
				});

			}
		},

		evCallback: {

			// ============[ EDpic - events ]=================================
			changePic: function (param) {

				// pentru mai multa docum vezi functia de mai jos "loadPic"
				/**
				 * param = {
                 *     callBackFn,
                 *     jqObj_img,
                  *    newUrl
                 * }
				 * */
				/**
				 * WORKING ON
				 *
				 return   "<img class='"+INPUTclass+"' src='"+imgSrc+"' id='editImg_"+INPUTname+"'>" +
				 "<input type='hidden' name='"+INPUTname+"' value='' />" +
				 "<input type='button' name='replaceImg' value='loadImg' " +
				 " style='left: 0;position: absolute;'" +
				 " onclick='iEdit.evCallback.loadPic(\"editImg_"+INPUTname+"\")'>";

				 [ OR ]

				 return   "<img class='"+INPUTclass+"' src='"+imgSrc+"' id='editAddImg_"+INPUTname+"'>" +
				 "<input type='hidden' name='"+INPUTname+"' value='' />" +
				 "<input type='button' name='replaceImg' value='loadImg' " +
				 " style='left: 0;position: absolute;'" +
				 " onclick='iEdit.evCallback.loadPic(\"editAddImg_"+INPUTname+"\")'>" ;
				 * */
				param.jqObj_img.attr('src', param.newUrl);
				param.jqObj_img.next('input[type=hidden]').val(param.newUrl);


			},

			loadPic: function (id) {

				//alert($('img#'+id).attr('src'));
				//var jqImg = $('img#'+id);
				fmw.KCFinder_popUp({
					jqObj_img: $('img#' + id),                  //img-ul al carui url va fi schimbat cu noua imagine aleasa
					callBackFn: iEdit.evCallback.changePic   //functie apelata dupa ce a fost selectata o imagine
				})

			},

			generalRemove_addNew: function () {

				//  this.exitEditContent_byType('ENT');

				$('.TOOLSem > input[name=addNewENT]').parent().show();
				$("form[id^=new_]").hide();
			},

			exitEditContent_byType: function (TYPE) {

				$('textarea[id^=editor_]').map(function () {

					var id = $(this).attr('id');
					if (CKEDITOR.instances[id]) CKEDITOR.instances[id].destroy(true);
				});

				var editForm = $('form[id^=EDITform]');
				if (editForm.length > 0) {
					editForm.prev().show();
					editForm.remove();
					// $('.'+TYPE).not('*[id*=_new_]').show();
				}


			},

			//*** posibly deprecated since generalRemove_addNew = exists
			remove_addNew: function (nameENT) {
				// this.exitEditContent_byType('ENT');
				var addFORM_id = "new_" + nameENT + '_' + LG;

				$('.TOOLSem > input[name=addNewENT]').parent().show();
				$("#" + addFORM_id).hide();
				return false;
			},

			// *** de investigat - posibil deprecated
			exitEditContent_byName: function (Name, id) {

				var jqForm = $('form[id=EDITform_' + id + '][class$=' + Name + ']');
				jqForm.find('textarea[id^=editor_]').map(function () {

					var idTxa = $(this).attr('id');
					if (CKEDITOR.instances[idTxa]) CKEDITOR.instances[idTxa].destroy(true);
				});

				jqForm.remove();
				$('*[id^=' + Name + '_' + id + '_]').not('*[id*=_new_]').show();

				// alert('ExitEditContent_byName inchide '+'form[id=EDITform_'+id+'][class$='+Name+']');
				//$('*[class$='+Name+']').not('*[id*=_new_]').show();


			},

			general_ExitEditContent: function () {
			},

			//================================[ essencials ]====================
			// set by init.tools_addEnt
			addEnt: function (nameENT) {


				this.exitEditContent_byType('ENT');
				this.exitEditContent_byType('SING');
				//this.remove_addNew(nameENT);
				$('.TOOLSem > input[name=addNewENT]').parent().hide();
				//$("#" + addFORM_id).show();
				var addForm = $(sel.addForm(nameENT));
				addForm.show();

				//  $("#"+addFORM_id).find('.PRDpic > img').replaceWith("<img src='./MODELS/products/RES/small_img/site_produs_slice_pisici.jpg' alt='placeholder_img'>");


			},

			// set by init.tools
			editContent: function (id, Name, TYPE, cls) {

				/*
				 this.generalRemove_addNew();
				 if(TYPE == 'SING')
				 this.exitEditContent_byType(TYPE);
				 */
				this.generalRemove_addNew();
				this.exitEditContent_byType('ENT');
				this.exitEditContent_byType('SING');

				var elD = get_elementEdited(id, Name, TYPE, cls);
				var form = templates.get_editForm(elD);
				//console.log(form);

				$.when(
					elD.BLOCK.after(form)
				).then(function () {

					var editForm = elD.BLOCK.next();
					var tools = editForm.find('.TOOLSbtn');

					editForm.show();
					if (tools.length > 0 && typeof elD.BTT != 'undefined') {
						actionBtns_binds(elD, editForm, tools);
						async_actionBind('editForm', elD, 'delete', 'deleteBt');
						async_actionBind('editForm', elD, 'save', 'saveBt');
					}
				});
				//==============================================================

				// nu imi place unde este pozitiionata aceasta functie
				//@todo: inca nu imi place trebuie apelata dinamic
				//=====================[callbackFn]=========================================
				if (typeof elD.BTT.edit.callback.fn == "function") {

					elD.BTT.edit.callback.fn.apply(
						elD.BTT.edit.callback.context,
						elD.BTT.edit.callback.args
					);
				} else {
					//console.log("NU exista metoda cu numele " + elD.BTT.edit.callbackFn);

				}

				transform(elD.BLOCK, 'form[class$=' + Name + '][id=EDITform_' + id + ']', Name);
				// disable all links 'a' in this form
				$('form[class$=' + Name + '][id=EDITform_' + id + ']')
					.find('a')
					.on('click', function () {
						return false;
					});

				elD.BLOCK.hide();




			},
			//===========================================[ async ]====================

			/**
			 * DOC:
			 * daca Nu exista selector pentru form
			 * => iesi din script
			 *
			 * colecteaza datele din formular
			 * => efectueaza postul
			 * => daca s-a terminat de efectuat
			 *  + iesi din modul de editare
			 *  + daca exista metoda de reconstruct =>reconstruct it
			 *
			 * @param Name      - numele elementului editabil
			 * @param id        - indexul elementului editabil
			 * @param formSel   - string denumirea formului
			 * @param actionBtt - numele butonului care a apelat asincronul
			 */
			async_edit: function (Name, id, formSel, actionBtt) {

				if (typeof sel[formSel] != 'function') {
					console.log("async_edit : Nu exista metoda de selectare a formului " + formSel);
					return;
				}

				var callBacks = this;
				var postData = $(sel[formSel](Name, id)).collectData();
				var asyncConf = bttConf[Name][actionBtt].async;

				asyncConf
					.fnpost(postData, {"args": [Name, id] })
					.done(function (data) {

						//console.log("async_edit after post data = " + data);
						callBacks.exitEditContent_byName(Name, id);
						// reconstruct
						if (typeof  reconstruct[actionBtt] != 'function') {
							console.log("async_edit : Nu exista metoda de reconstruct after asyncron pt" + actionBtt);
							return;
						}
						reconstruct[actionBtt](Name, id, postData, data);
					});
			}
		}
	}
}();

/*=======================================[ call specific EDITMODE]====================================================*/

function spec_EditMode(elmType, elmName, elmId) {

	var elmSel = $(" *[class^=" + elmType + "][id^=" + elmName + "_" + elmId + "_]");
	elmSel.wrapInner("<div class='ELMcontent' />");


	spec_setTOOLS(elmSel, elmType, elmName, elmId);                                                                          // BTT de edit - EditContent(id,Name,TYPE)

}
function spec_setTOOLS(elmSel, elmType, elmName, elmId) {

	// desc    = elmSel.attr('id').split('_');
	// classes = elmSel.attr('class');

	var cls = elmSel.attr('class').replace(elmType, '');

	var TYPE = elmType;
	var id = elmId;
	var Name = elmName;        //ENTname || SINGname

	//================================================================================================================


	var BTT = {name: 'e', style: ''};

	if (typeof bttConf[Name].edit != 'undefined')
		$.extend(BTT, bttConf[Name].edit);

	//DEP
	/* var BTTvalue = typeof BTTedit.name[Name]!='undefined' ? BTTedit.name[Name] : 'e';
	 var BTTstyle = typeof BTTedit.style[Name]!='undefined' ? BTTedit.style[Name] : "";
	 */


	//================================================================================================================
	elmSel.prepend
		(
			"<div class='TOOLSem' style='display: none;'>" +
				"<div class='TOOLSbtn'>" +
				"   <span>" +
				"       <input type='button' class='iedit-btt' " + BTT.style + " name='EDIT' value='" + BTT.name + "'" +
				"                            onclick=\"iEdit.evCallback.editContent('" + id + "','" + Name + "','" + TYPE + "','" + cls + "'); return false;\">" +
				"       <i>Edit Content</i>" +
				"   </span>" +
				"</div>" +
				"</div>"
		);

}

//======================================================================================================================

$(document).ready(function () {

	/**
	 * -- SETTINGS--
	 *
	 *  EDsel => EDname = new SELoptions(Array_ro,Array_en);
	 *  extras => EDname_extra = functionName(id);
	 */
	iEdit.init.set_iEdit();
	//iEdit.init.setLG('ru');
	//iEdit.init.testFunc();

});



