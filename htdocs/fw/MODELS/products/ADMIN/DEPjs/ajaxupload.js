function $m(theVar){
	//return document.getElementById(theVar)

    ob = $('#'+theVar)[0];
    return ob;
}
function remove(theVar){
	var theParent = theVar.parentNode;
	theParent.removeChild(theVar);
}
function addEvent(mod, evType, fn){
	if(mod.addEventListener)
	    mod.addEventListener(evType, fn, true)
	if(mod.attachEvent)
	    mod.attachEvent("on"+evType, fn)
}
function removeEvent(mod, type, fn){
	if(mod.detachEvent){
		mod.detachEvent('on'+type, fn);
	}else{
		mod.removeEventListener(type, fn, false);
	}
}
function isWebKit(){
	return RegExp(" AppleWebKit/").test(navigator.userAgent);
}
function ajaxUpload(form,url_action,id_element,html_show_loading,html_error_http){

	var detectWebKit = isWebKit();
   // form = $('form#standard_use');
	form = typeof(form)=="string"?$m(form):form;
	var erro="";
	if(form==null || typeof(form)=="undefined"){
		erro += "The form of 1st parameter does not exists.\n";
	}else if(form.nodeName.toLowerCase()!="form"){
		erro += "The form of 1st parameter its not a form.\n";
	}
	if($m(id_element)==null){
		erro += "The element of 3rd parameter does not exists.\n";
	}
	if(erro.length>0){
		alert("Error in call ajaxUpload:\n" + erro);
		return;
	}
	/*var iframe = document.createElement("iframe");
	iframe.setAttribute("id","ajax-temp");
	iframe.setAttribute("name","ajax-temp");
	iframe.setAttribute("width","0");
	iframe.setAttribute("height","0");
	iframe.setAttribute("border","0");
	iframe.setAttribute("style","width: 0; height: 0; border: none;");*/


	//form.parentNode.appendChild(iframe);

    $('#'+'frontpic').append("<iframe name='ajax-temp' id='ajax-temp'></iframe>");

	window.frames['ajax-temp'].name="ajax-temp";



	var doUpload = function(){
		removeEvent($m('ajax-temp'),"load", doUpload);
		var cross = "javascript: ";
		cross += "window.parent.$m('"+id_element+"').innerHTML = document.body.innerHTML; void(0);";
		$m(id_element).innerHTML = html_error_http;
		$m('ajax-temp').src = cross;
		/*if(detectWebKit){
        	remove($m('ajax-temp'));
        }
        else{ setTimeout(function(){ remove($m('ajax-temp'))}, 250);  }*/
        setTimeout(function(){ remove($m('ajax-temp'))}, 250);
    }
	addEvent($m('ajax-temp'),"load", doUpload);
	form.setAttribute("target","ajax-temp");

	form.setAttribute("action",url_action);
	form.setAttribute("method","post");
	form.setAttribute("enctype","multipart/form-data");
	form.setAttribute("encoding","multipart/form-data");
	if(html_show_loading.length > 0){
	//	$m(id_element).innerHTML = html_show_loading;
	}
	 form.submit();

}