ivyMods.user = {
    templates: {
        deactivate      : "GENERAL/user/tmpl/deactivateAccount.php",
        recoverPassword : "GENERAL/user/tmpl/recoverPassword.php?id="+fmw.getData['id']+"&token="+fmw.getData['token'],
        loginForm       : "GENERAL/user/tmpl/loginform.html",
        changePass      : "GENERAL/user/tmpl/changePassword.php",
        inviteMember    : "GENERAL/user/tmpl/inviteMember.php?sid="+$.cookie('PHPSESSID'),
        inviteConfirm   : "GENERAL/user/tmpl/inviteConfirm.php"
    },
    popupwidth: {
        deactivate: '350',
        loginForm: '400',
        changePass: '300',
        inviteMember: '250',
        inviteConfirm: '400'
    },
	 sel: {
		 loginLink: "div.footerText"
	 },
    forgotPassword : function(){
        fmw.toggle('#recover-pass');
        fmw.toggle("#loginForm");
        fmw.toggle("#p-login");
        fmw.toggle("#p-recover");
    },
    loginForm : function(){
        fmw.toggle('#recover-pass');
        fmw.toggle("#loginForm");
        fmw.toggle("#p-login");
        fmw.toggle("#p-recover");
    },
    popup :function(pubUrl,template,  headerName, uid) {
	    /*if(typeof  fmw == 'undefined') {
		    alert("Aparent GEN.js nu este inca incarcat");
	    } else {
		    alert("Aparent GEN.js Ar trebui sa fie incarcat");
	    }*/
        fmw.popUp.init({
            pathGet: pubUrl + this.templates[template],
            headerName: headerName,
            widthPop: this.popupwidth[template],
            dataSend: {uid: uid}
        });
    },
	 writeLoginLink: function (){
	     var el = $(this.sel.loginLink);
	     el.html(el.html() + "<a href='/?login'>Login</a>");
	 },
	 init: function(){
		this.writeLoginLink();
	 }
};

$(document).ready(function(){
	ivyMods.user.init();
});

