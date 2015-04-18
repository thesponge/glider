/*
Copyright (c) 2003-2011, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/
$(document).ready( function(){
if(typeof CKEDITOR.editorConfig == 'undefined') {

console.log('config.js - A luat CKEditor din locals');

CKEDITOR.editorConfig = function( config )
{
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';

					//config.extraPlugins = 'tableresize';
					config.extraPlugins = 'image';
					config.extraPlugins = 'dialog';
					config.extraPlugins = 'link';
					config.extraPlugins = 'save';
					//config.extraPlugins = 'scayt';
					config.language = 'en';
					config.skin = 'moono';
                   // config.extraPlugins = 'slideshow';




    config.filebrowserBrowseUrl          = './assets/kcfinder/browse.php?type=files';
    config.filebrowserImageBrowseUrl     = './assets/kcfinder/browse.php?type=images';
    config.filebrowserFlashBrowseUrl     = './assets/kcfinder/browse.php?type=flash';
    config.filebrowserUploadUrl          = './assets/kcfinder/upload.php?type=files';
    config.filebrowserImageUploadUrl     = './assets/kcfinder/upload.php?type=images';
    config.filebrowserFlashUploadUrl     = './assets/kcfinder/upload.php?type=flash';

 config.toolbar = 'Comoti';
	config.toolbar_Comoti =
[
	{ name: 'document', items : [ 'Source','Print','Save'] },
	{ name: 'clipboard', items : [ 'Cut','Copy','Paste','PasteText','PasteFromWord','-','Undo','Redo' ] },
	{ name: 'links', items : [ 'Link','Unlink' ] },
	{ name: 'insert', items : [ 'Image','Flash','Table','HorizontalRule','SpecialChar','Slideshow'  ] },
	'/',
	{ name: 'styles', items : [ 'Font','FontSize' ] },
	{ name: 'basicstyles', items : [ 'Bold','Italic','Underline','Strike','Subscript','Superscript','-','RemoveFormat' ] },
	{ name: 'paragraph', items : [ 'NumberedList','BulletedList','-','Outdent','Indent','-',
									'JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock' ] },
	{ name: 'colors', items : [ 'TextColor','BGColor' ] }
];
    config.toolbar_defaultSmall =
      [


          { name: 'document', items : [ 'Source'] },
          { name: 'basicstyles', items : [ 'Bold','Italic','Underline','RemoveFormat' ] },
          { name: 'paragraph', items : ['JustifyLeft','JustifyCenter','BulletedList' ] },
          { name: 'colors', items : [ 'TextColor' ] },
          { name: 'insert', items : [ 'Image', 'Link' ] }

      ];

    config.toolbar_default =
    [
        { name: 'styles', items : [ 'FontSize' ] },
        { name: 'links', items : [ 'Link','Unlink'] },
        { name: 'basicstyles', items : [ 'Bold','Italic','Underline','RemoveFormat' ] },
        { name: 'paragraph', items : ['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock','BulletedList' ] },
        { name: 'colors', items : [ 'TextColor','BGColor' ] },
        { name: 'insert', name:'save',items : [ 'Image','Flash','Table', 'Slideshow' ] }
    ];
config.toolbar = 'Full';
config.toolbar_Full =
[
	{ name: 'document', items : [ 'Source','-','Save','NewPage','DocProps','Preview','Print','-','Templates' ] },
	{ name: 'clipboard', items : [ 'Cut','Copy','Paste','PasteText','PasteFromWord','-','Undo','Redo' ] },
	{ name: 'editing', items : [ 'Find','Replace','-','SelectAll','-','SpellChecker', 'Scayt' ] },
	{ name: 'forms', items : [ 'Form', 'Checkbox', 'Radio', 'TextField', 'Textarea', 'Select', 'Button', 'ImageButton',
        'HiddenField' ] },
	'/',
	{ name: 'basicstyles', items : [ 'Bold','Italic','Underline','Strike','Subscript','Superscript','-','RemoveFormat' ] },
	{ name: 'paragraph', items : [ 'NumberedList','BulletedList','-','Outdent','Indent','-','Blockquote','CreateDiv',
	'-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock','-','BidiLtr','BidiRtl' ] },
	{ name: 'links', items : [ 'Link','Unlink','Anchor' ] },
	{ name: 'insert', items : [ 'Image','Flash','Table','HorizontalRule','Smiley','SpecialChar','PageBreak','Iframe' ] },
	'/',
	{ name: 'styles', items : [ 'Styles','Format','Font','FontSize' ] },
	{ name: 'colors', items : [ 'TextColor','BGColor' ] },
	{ name: 'tools', items : [ 'Maximize', 'ShowBlocks','-','About' ] }
];

    config.resize_enabled = false;



};
} //if
}); //document ready
/*
CKEDITOR.on('dialogDefinition', function(ev) {
      // Take the dialog name and its definition from the event data
      var dialogName = ev.data.name;
      var dialogDefinition = ev.data.definition;

      if (dialogName == 'image') {
         dialogDefinition.onOk = function(e) {
             alert('sa speram ca s-a terminat');
            var imageSrcUrl = e.sender.originalElement.$.src;
            var imgHtml = CKEDITOR.dom.element.createFromHtml("<img src=" + imageSrcUrl + " alt='' align='right'/>");
            CKEDITOR.instances.body.insertElement(imgHtml);
         };
      }
});*/
