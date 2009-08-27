
var isIE  = (navigator.appVersion.indexOf("MSIE") != -1) ? true : false;
var isIE6  = (navigator.appVersion.indexOf("MSIE 6.0") != -1) ? true : false;
var isWin = (navigator.appVersion.toLowerCase().indexOf("win") != -1) ? true : false;
var isMac = (navigator.appVersion.toLowerCase().indexOf("mac") != -1) ? true : false;
var isOpera = (navigator.userAgent.indexOf("Opera") != -1) ? true : false;
var isSafari = (navigator.userAgent.indexOf("Safari") != -1) ? true : false;
var isMozilla = (navigator.userAgent.indexOf("Mozilla") != -1 && !isSafari) ? true : false;
var winloadFuns = '';

function fnAjaxCall(mesg,url,fname){
	alert(fname);
	return false;
    new Ajax.Updater( mesg, url, {
                        method: 'post',
                        evalScripts:'true', 
                        postBody:Form.serialize(fname)
                    }
    ); 
    return false;
}

function toggle(div_id) {
	var el = document.getElementById(div_id);
	if ( el.style.display == 'none' ) {	el.style.display = 'block';}
	else {el.style.display = 'none';}
}
function blanket_size(popUpDivVar) {
	if (typeof window.innerWidth != 'undefined') {
		viewportheight = window.innerHeight;
	} else {
		viewportheight = document.documentElement.clientHeight;
	}
	if ((viewportheight > document.body.parentNode.scrollHeight) && (viewportheight > document.body.parentNode.clientHeight)) {
		blanket_height = viewportheight;
	} else {
		if (document.body.parentNode.clientHeight > document.body.parentNode.scrollHeight) {
			blanket_height = document.body.parentNode.clientHeight;
		} else {
			blanket_height = document.body.parentNode.scrollHeight;
		}
	}
    
	var blanket = document.getElementById('blanket');
	blanket.style.height = blanket_height + 'px';
	var popUpDiv = document.getElementById(popUpDivVar);
	popUpDiv_height=blanket_height/2-150;//150 is half popup's height
	popUpDiv.style.top = popUpDiv_height + 'px';
}
function blanket_vote_size(popUpDivVar) {
    if (typeof window.innerWidth != 'undefined') {
        viewportheight = window.innerHeight;
    } else {
        viewportheight = document.documentElement.clientHeight;
    }
    if ((viewportheight > document.body.parentNode.scrollHeight) && (viewportheight > document.body.parentNode.clientHeight)) {
        blanket_height = viewportheight;
    } else {
        if (document.body.parentNode.clientHeight > document.body.parentNode.scrollHeight) {
            blanket_height = document.body.parentNode.clientHeight;
        } else {
            blanket_height = document.body.parentNode.scrollHeight;
        }
    }
    //alert(blanket_height);
    var blanket = document.getElementById('blanket_vote');
    blanket.style.height = blanket_height + 'px';
    var popUpDiv = document.getElementById(popUpDivVar);
    popUpDiv_height=blanket_height/2-150;//150 is half popup's height
    popUpDiv.style.top = popUpDiv_height + 'px';
}
function window_pos(popUpDivVar) {
	if (typeof window.innerWidth != 'undefined') {
		viewportwidth = window.innerHeight;
	} else {
		viewportwidth = document.documentElement.clientHeight;
	}
	if ((viewportwidth > document.body.parentNode.scrollWidth) && (viewportwidth > document.body.parentNode.clientWidth)) {
		window_width = viewportwidth;
	} else {
		if (document.body.parentNode.clientWidth > document.body.parentNode.scrollWidth) {
			window_width = document.body.parentNode.clientWidth;
		} else {
			window_width = document.body.parentNode.scrollWidth;
		}
	}
	var popUpDiv = document.getElementById(popUpDivVar);
	window_width=window_width/2-150;//150 is half popup's width
    widthX = document.body.clientWidth;

	popUpDiv.style.left = window_width + 'px';
}

function centerDiv(id)
{  
    
    if(!$(id))
        return false;              
        
    var pageScrollStats = getScrollStats();
    var windowWidth = GetWindowWidth();
    var windowHeight = GetWindowHeight();
    var divWidth = $(id).clientWidth;
    var divHeight = $(id).clientHeight; 
               
    divWidth = divWidth ? divWidth : (windowWidth/2) ;
    divHeight = divHeight ? divHeight : (windowHeight/2); 
    
    
    //To center element according to window scroll.
	/*$(id).style.top = ''+Math.round(pageScrollStats[1]+((windowHeight/2)-(divHeight/2))+50)+'px'; 
    $(id).style.left = ''+Math.round(pageScrollStats[0]+((windowWidth/2)-(divWidth/2))+100)+'px';        */
    $(id).style.top = '30%'; 
    if(id == 'popUpDiv')
        $(id).style.left = '36%';
    else
        $(id).style.left = '25%';
    //$(id).style.left = '50%';          
    
    
    //To center element to a fixed position irrespective of the window scroll
    //$(id).style.top = ''+(((windowHeight/2)-(divHeight/2)))+'px';
    //$(id).style.left = ''+(((windowWidth/2)-(divWidth/2)))+'px';

}

function getScrollStats()
{
	var scrollLeft = 0;
	var scrollTop = 0;
	if (window.pageYOffset){  
	scrollTop = window.pageYOffset 
	} else if(document.documentElement && document.documentElement.scrollTop){ 
		scrollTop = document.documentElement.scrollTop; 
	} else if(document.body){ 
		scrollTop = document.body.scrollTop; 
	} 

	if(window.pageXOffset){ 
		scrollLeft=window.pageXOffset 
	} else if(document.documentElement && document.documentElement.scrollLeft){ 
		scrollLeft=document.documentElement.scrollLeft; 
	} else if(document.body){ 
		scrollLeft=document.body.scrollLeft; 
	}
    var retArray = Array();
	retArray[0] = scrollLeft;
	retArray[1] = scrollTop;

	return retArray;
}

function GetWindowWidth() { 
 
//return screen.width;

  //return window.innerWidth||
    //document.documentElement&&document.documentElement.clientWidth||
    //document.body.clientWidth||0;
    
    var windowWidth = 0;
        if (typeof(window.innerWidth) == 'number') {
            windowWidth = window.innerWidth;
        }
        else {
            if (document.documentElement && document.documentElement.clientWidth) {
                windowWidth = document.documentElement.clientWidth;
            }
            else {
                if (document.body && document.body.clientWidth) {
                    windowWidth = document.body.clientWidth;
                }
            }
        }
        return windowWidth;

}
// function  GetWindowHeight return the client browser height
//modified by : Manish Sharma
// modified on : 10-03-2009
function GetWindowHeight() { 

  //return window.innerHeight||
    //document.documentElement&&document.documentElement.clientHeight||
    //document.body.clientHeight||0;
    
     var windowHeight = 0;
        if (typeof(window.innerHeight) == 'number') {
            windowHeight = window.innerHeight;
        }
        else {
            if (document.documentElement && document.documentElement.clientHeight) {
                windowHeight = document.documentElement.clientHeight;
            }
            else {
                if (document.body && document.body.clientHeight) {
                    windowHeight = document.body.clientHeight;
                }
            }
        }
        
        return windowHeight; 
}

function disableUserActions(windowname)
{

	var theBody = document.getElementsByTagName('body')[0];
	var opacityRatio;

	if(isWin)
		opacityRatio = 60;
	else
		opacityRatio = 40;
	
	if(!$('actnBlckr'))
	{
		var actionBlckDv = document.createElement('DIV');
		actionBlckDv.setAttribute('id', windowname);
		
		theBody.appendChild(actionBlckDv);

		actionBlckDv.setAttribute('style', 'display: none;position: absolute; top: 0px; left: 0px; background-color: #fff; filter:alpha(opacity='+opacityRatio+'); -moz-opacity:.'+opacityRatio+'; opacity:.'+opacityRatio+';');
		/** IE patches */
		actionBlckDv.style.position = isIE6?"absolute":"fixed";
		actionBlckDv.style.top = "0px";
		actionBlckDv.style.left = "0px";
        actionBlckDv.style.left = GetWindowWidth()+"px";
		actionBlckDv.style.backgroundColor = "#999";
		actionBlckDv.style.filter = "alpha(opacity="+opacityRatio+");";
		actionBlckDv.style.zIndex = "999";   
		/** IE patches */

	}
	
	/**
	var pageSize = getPageSizeWithScroll();
	var width = pageSize[0];
	var height = pageSize[1];
	*/
	$(windowname).style.display = '';
	$(windowname).style.width = GetWindowWidth() + 'px';
	$(windowname).style.height = GetWindowHeight() + 'px';
    
	if(isIE6){
		fixActionBlockerInIe6(windowname);
		//addCallbackToWindowScrollEvent(" fixActionBlockerInIe6(); ");
	}
}

function fixActionBlockerInIe6(windowname)
{
	var pageScrollStats = getScrollStats(); 
	if($(windowname))
		$(windowname).style.top = pageScrollStats[1] + 'px';       
    
}
                   
function popup(windowname,val) {

	//disableUserActions(windowname);
    blanket_size(windowname);
	//window_pos(windowname);
	centerDiv(windowname);
    document.getElementById('blanket').style.width = GetWindowWidth()+"px"; 
    document.getElementById('popUpDiv').style.position = isIE6?"absolute":"fixed";
    //alert(isIE6);     
	toggle('blanket');
	toggle(windowname);	
	document.getElementById("redirect").value = val;
}

function submitMe(e,path) {
    if (e.keyCode == 13)
    {   
        path1 = path+'login.php';
		fnAjaxCall('error_message',path1,'form11');
		return false;
    }
} 



function fnAjaxCall(mesg,url,fname){ 
	new Ajax.Updater( mesg, url, {
                        method: 'post',
                        evalScripts:'true', 
						parameters: { uname: $F('uname'),pass : $F('pass'),submit1 : $F('submit1'),redirect : $F('redirect')},
                        postBody:Form.serialize(fname)
                    }
    ); 
    return false;
}

function echeck(str) {
	var at="@"
	var dot="."
	var lat=str.indexOf(at)
	var lstr=str.length
	var ldot=str.indexOf(dot)
	if (str.indexOf(at)==-1){
	   alert("Invalid E-mail ID")
	   return false
	}

	if (str.indexOf(at)==-1 || str.indexOf(at)==0 || str.indexOf(at)==lstr){
	   alert("Invalid E-mail ID")
	   return false
	}

	if (str.indexOf(dot)==-1 || str.indexOf(dot)==0 || str.indexOf(dot)==lstr){
		alert("Invalid E-mail ID")
		return false
	}

	 if (str.indexOf(at,(lat+1))!=-1){
		alert("Invalid E-mail ID")
		return false
	 }

	 if (str.substring(lat-1,lat)==dot || str.substring(lat+1,lat+2)==dot){
		alert("Invalid E-mail ID")
		return false
	 }

	 if (str.indexOf(dot,(lat+2))==-1){
		alert("Invalid E-mail ID")
		return false
	 }
	
	 if (str.indexOf(" ")!=-1){
		alert("Invalid E-mail ID")
		return false
	 }

	 return true					
}

function validatecontact(objFrm){
	if(document.getElementById("name").value == ""){
		alert("Please enter name");
		document.getElementById("name").focus();
		return false;
	}
	if (echeck(document.getElementById("email").value)==false){
		document.getElementById("email").value=""
		document.getElementById("email").focus()
		return false
	}
	if(document.getElementById("briefmessage").value == ""){
		alert("Please enter message");
		document.getElementById("briefmessage").focus();
		return false;
	}
	if(document.getElementById("challenge_string").value == ""){
		alert("Please enter verification code");
		document.getElementById("challenge_string").focus();
		return false;
	}
	return true;
}

function validatepatient(objFrm){
	if(document.getElementById("pname").value == ""){
		alert("Please enter patient name");
		document.getElementById("pname").focus();
		return false;
	}
	if(document.getElementById("dateofvisit").value == ""){
		alert("Please select date of visit");
		document.getElementById("dateofvisit").focus();
		return false;
	}
	if(document.getElementById("preferralnopcp").value == ""){
		alert("Please enter Referral Number from PCP");
		document.getElementById("preferralnopcp").focus();
		return false;
	}
	if(document.getElementById("phomeaddress").value == ""){
		alert("Please enter patient home address");
		document.getElementById("phomeaddress").focus();
		return false;
	}
	if(document.getElementById("phomeno").value == ""){
		alert("Please enter home number");
		document.getElementById("phomeno").focus();
		return false;
	}
	if(document.getElementById("pssn").value == ""){
		alert("Please enter Social Security Number");
		document.getElementById("pssn").focus();
		return false;
	}
	if(document.getElementById("pemployer").value == ""){
		alert("Please enter patient employer name");
		document.getElementById("pemployer").focus();
		return false;
	}
	if(document.getElementById("pcontactname").value == ""){
		alert("Please enter patient emergency contact person name");
		document.getElementById("pcontactname").focus();
		return false;
	}
	if(document.getElementById("pcontactrelation").value == ""){
		alert("Please enter relationship for contact person");
		document.getElementById("pcontactrelation").focus();
		return false;
	}
	if(document.getElementById("pcontactphone").value == ""){
		alert("Please enter contact person phone number");
		document.getElementById("pcontactphone").focus();
		return false;
	}
	if(document.getElementById("pcontactphysican").value == ""){
		alert("Please enter Referring Physician name");
		document.getElementById("pcontactphysican").focus();
		return false;
	}
	if(document.getElementById("pconphyphone").value == ""){
		alert("Please enter phone number for Referring Physician");
		document.getElementById("pconphyphone").focus();
		return false;
	}
	if(document.getElementById("fphysician").value == ""){
		alert("Please enter Family Physician name");
		document.getElementById("fphysician").focus();
		return false;
	}
	if(document.getElementById("fphyphone").value == ""){
		alert("Please enter Family Physician contact number");
		document.getElementById("fphyphone").focus();
		return false;
	}
	if(document.getElementById("fphyaddress").value == ""){
		alert("Please enter Family Physician address");
		document.getElementById("fphyaddress").focus();
		return false;
	}
	if(document.getElementById("primaryinsurance").value == ""){
		alert("Please enter Primary insurance");
		document.getElementById("primaryinsurance").focus();
		return false;
	}
	if(document.getElementById("pipolicyno").value == ""){
		alert("Please enter policy number");
		document.getElementById("pipolicyno").focus();
		return false;
	}	
	if(document.getElementById("authorization").checked == false){
		alert("Please check the checkbox for authorization");
		return false;
	}
	if(document.getElementById("challenge_string").value == ""){
		alert("Please enter verification code");
		document.getElementById("challenge_string").focus();
		return false;
	}
	return true;
}

function validatepassword(objFrm){
	if(document.getElementById("opwd").value == ""){
		alert("Please enter Old Password");
		document.getElementById("opwd").focus();
		return false;
	}
	if(document.getElementById("npwd").value == ""){
		alert("Please enter New password");
		document.getElementById("npwd").focus();
		return false;
	}
	if(document.getElementById("ncpwd").value == ""){
		alert("Please enter confirm password");
		document.getElementById("ncpwd").focus();
		return false;
	}
	return true;
}


function validateprofile(objFrm){
	if(document.getElementById("strFirstName").value == ""){
		alert("Please enter first name");
		document.getElementById("strFirstName").focus();
		return false;
	}
	if(document.getElementById("strLastName").value == ""){
		alert("Please enter last name");
		document.getElementById("strLastName").focus();
		return false;
	}
	if (echeck(document.getElementById("strEmail").value)==false){
		document.getElementById("strEmail").value=""
		document.getElementById("strEmail").focus()
		return false
	}
	return true;
}

function fnSafari(){
    if(isSafari){
        document.getElementById("arightmenu").style.width = '910px';
    } else {
        document.getElementById("arightmenu").style.width = '58%';
    }
}