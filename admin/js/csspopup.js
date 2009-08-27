
var isIE  = (navigator.appVersion.indexOf("MSIE") != -1) ? true : false;
var isIE6  = (navigator.appVersion.indexOf("MSIE 6.0") != -1) ? true : false;
var isWin = (navigator.appVersion.toLowerCase().indexOf("win") != -1) ? true : false;
var isMac = (navigator.appVersion.toLowerCase().indexOf("mac") != -1) ? true : false;
var isOpera = (navigator.userAgent.indexOf("Opera") != -1) ? true : false;
var isSafari = (navigator.userAgent.indexOf("Safari") != -1) ? true : false;
var isMozilla = (navigator.userAgent.indexOf("Mozilla") != -1 && !isSafari) ? true : false;
var winloadFuns = '';

function fnAjaxCall(mesg,url,fname){	
    new Ajax.Updater( mesg, url, {
                        method: 'post',
                        evalScripts:'true', 
                        postBody:Form.serialize(fname),
						onSuccess: function(transport){
							result = transport.responseText.split("~");
							switch(result[0]){
                                case 'ErrorMess':
                                      document.getElementById('added_question').innerHTML=result[1];  
                                      break;
                                case 'EditQuestion':
                                      showquestion(result[2],result[3]);
                                      document.getElementById('added_question').innerHTML=result[1];
                                      break;
                                case 'AddQuestion' : 
                                      popup('popUpDiv',0,0);
                                      document.getElementById('added_question').innerHTML=result[1];  
                                      break;
                                case 'removeQuestion':
                                      document.getElementById('added_question').innerHTML=result[1]; 
                                      break;
                                case 'AddOption':
                                      url = 'fetchquestion.php?qid='+result[1];
                                      toggle('blanket');
                                      document.getElementById('popUpDiv1').style.display='none';   
                                      fnAjaxCall('added_question',url,'form12');
                                      break;
								case 'ModifyOption':
									  var t=setTimeout("setDelay('error_option')",10);	
									  showanswer(2,result[2]);	
									  document.getElementById('ans36').innerHTML=result[1];
                                      break;
								/*case 'ChecQuestion':
									  var url = "fillmodule.php?cid="+result[1];	
									  fnAjaxCall('show_module',url,'form1');
									  popup('popUpDiv',1);
									  var t=setTimeout("setDelay('qtitle_e_message')",10);
                                      break;*/
                            }                                                                     
						}
                    }
    ); 
    return false;
}

function setDelay(mes){
	document.getElementById(mes).innerHTML='';
}

function fnAjaxCall_removequestion(mesg,url,fname){	
    new Ajax.Updater( mesg, url, {
                        method: 'get',
                        evalScripts:'true', 
                        postBody:Form.serialize(fname)
                    }
    ); 
    return false;
}

function fnAjaxCall_showoption(mesg,url,qid,fname){
    new Ajax.Updater( mesg, url, {
                        method: 'get',
                        evalScripts:'true',
                        parameters: { ques_id: qid}, 
                        postBody:Form.serialize(fname)
                    }
    ); 
    return false;
}

function fetchajaxdata(datasource,pagename) {
    var options = {
         'pagename' : pagename
     };
     
     $.post('redirect.php', options,
      function (contents) {
      } ,"html"
     );
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

function centerDiv1(id)
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
	$(id).style.top = '2%'; 
    if(id == 'popUpDiv')
        $(id).style.left = '36%';
    else
        $(id).style.left = '25%';
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
function regIsDigit(fData){
     var reg = new RegExp("^[0-9]*$");
     return (reg.test(fData));
}
function trim (myString){
	return myString.replace(/^\s+/g,'').replace(/\s+$/g,'')
}

function popup_option(windowname,val,sh) {
    blanket_size(windowname);
    centerDiv1(windowname);
    fnAjaxCall_showoption('show_options','showoptions.php',val,'form12');
    if(sh){
        url = 'fetchquestion.php?qid='+sh;
        fnAjaxCall('added_question',url,'form12');
    }
    document.getElementById('blanket').style.width = GetWindowWidth()+"px"; 
    document.getElementById('popUpDiv').style.position = isIE6?"absolute":"absolute";
    toggle('blanket');
    toggle(windowname);    
}

function popup_howmany(windowname,val,lim){
	var strtab = '';
	var hmany = "howmany"+val;
//	alert("Here");	return false;
    if(document.getElementById(hmany)){
        if(lim < 2 && document.getElementById(hmany).value < 2){
			alert("Entered value should not be less then 2");
			document.getElementById(hmany).focus();
			return false;
		}
		if(regIsDigit(trim(document.getElementById(hmany).value)) == false){
			alert("Please enter only numeric value");
			document.getElementById(hmany).focus();
			return false;
		}
		
		if(document.getElementById(hmany).value > 10){
			alert("Entered value should be less then 10");
			document.getElementById(hmany).focus();
			return false;
		}
		
		strtab += '<table width="100%" border="0">';
		var k=1;
		var j=1;
        var hh;
        hh = Math.abs(document.getElementById(hmany).value);
        if((hh+lim)>10){
            hh = 10-lim;
        }
        
		if(lim){
            strtab += '<tr><td colspan="2" style="color:red;">You have already added <b>' + lim + '</b> options</td></tr>';
        }
        for(var i=0; i< hh; i++){
			var txtname = 'opt['+ i + ']';
			strtab += '<tr><td>'+ j +')'+'<input type="radio" value="'+ i +'" name="ans"/></td><td>' ;
			strtab += '<textarea cols="40" rows="1" name="'+txtname+'" id="'+txtname+'"></textarea>';
			strtab += '</td></tr>';
			j++;
		}
		strtab += '</table>';
        document.getElementById('hid').value = hh; 
	}
	
	
	blanket_size(windowname);
	//window_pos(windowname);
	centerDiv1(windowname);
	document.getElementById('error_message1').innerHTML = ''; 	
	document.getElementById('qid').value = val; 
	document.getElementById('showopt').innerHTML = strtab; 
    document.getElementById('blanket').style.width = GetWindowWidth()+"px"; 
    document.getElementById('popUpDiv1').style.position = isIE6?"absolute":"absolute";
    //alert(isIE6);     
	toggle('blanket');
	toggle(windowname);
}

function checkTitle(objFrm){
	if(document.getElementById("course_id")){
		if(document.getElementById("course_id").value == "--"){
			alert("Please select course name");
			document.getElementById("course_id").focus();
			return false;
		}
	}
	if(document.getElementById("quiztitle").value == ""){
		alert("Please enter quiz title");
		document.getElementById("quiztitle").focus();
		return false;
	}
	if(document.getElementById("course_id")){
		var cid = document.getElementById("course_id").value;
		var qtitle = document.getElementById("quiztitle").value;
		var url = 'checkquestion.php?cid='+ cid +'&qtitle='+qtitle;
		fnAjaxCall('qtitle_e_message',url,'frmaddsec');
	} else {
		popup('popUpDiv',1);
	}
}


function popup(windowname,val) {
	if(document.getElementById('question')){
		document.getElementById('question').value = "";	
	}
	if(document.getElementById('module_id')){
		document.getElementById('module_id').value = "--";
	}
	if(document.getElementById('error_message_question')){
	    document.getElementById('error_message_question').innerHTML = "";    
	}
	//disableUserActions(windowname);
    blanket_size(windowname);
	//window_pos(windowname);
	centerDiv(windowname);
    document.getElementById('blanket').style.width = GetWindowWidth()+"px"; 
    document.getElementById('popUpDiv').style.position = isIE6?"absolute":"fixed";
    //alert(isIE6);     
	toggle('blanket');
	toggle(windowname);	
}

function submitMe(e) {
    if (e.keyCode == 13)
    {
		fnAjaxCall('error_message','login.php','form1');
		return false;
    }
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

function showquestion(iid,id){
	var qid = "quz"+ id;
	var qiid = "quz1"+ id;
	//alert(qid +'=>'+ qiid);
	if(iid == 1){
		document.getElementById(qid).style.display = 'none';
		document.getElementById(qiid).style.display = 'block';
	} else if(iid == 2){
		document.getElementById(qid).style.display = 'block';
		document.getElementById(qiid).style.display = 'none';
	}
}

function showanswer(iid,id){
	var qid = "ans"+ id;
	var qiid = "ans1"+ id;
	if(iid == 1){
		document.getElementById(qid).style.display = 'none';
		document.getElementById(qiid).style.display = 'block';
	} else if(iid == 2){
		document.getElementById(qid).style.display = 'block';
		document.getElementById(qiid).style.display = 'none';
	}
}

function checkoption(objFrm,frm,id){
	if(document.getElementById("answer").value == ""){
		alert("Please enter answer");
		document.getElementById("answer").focus();
		return false;		
	}
	var ans = document.getElementById("answer").value;
	var url = "addoption.php?id="+ id +"&amp;ans="+ans;
	fnAjaxCall('error_message1',url,id);
}