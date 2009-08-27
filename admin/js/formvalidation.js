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

function trim (myString){
	return myString.replace(/^\s+/g,'').replace(/\s+$/g,'')
}

function confirmLink(message,theLink, url) {
  var is_confirmed = confirm(message);  
  if (is_confirmed) {
	  return true;   
  }
  return is_confirmed;
} 

function validate(objFrm){
    if(document.getElementById("struname").value == ""){
       alert("Please enter user name");
       document.getElementById("struname").focus(); 
       return false;
    }
    if(document.getElementById("pass").value == ""){
       alert("Please enter password");
       document.getElementById("pass").focus(); 
       return false;
    }
    return true;
}

function validatecources(objFrm){
    if(document.getElementById("cname").value == ""){
       alert("Please enter cources name");
       document.getElementById("cname").focus(); 
       return false;
    }
    return true;
}

function validatemodule(objFrm){
    if(document.getElementById("course_id").value == "--"){
        alert("Please select course name");
        document.getElementById("course_id").focus();
        return false;
    }
	if(document.getElementById("page_name").value == "--"){
        alert("Please select page name");
        document.getElementById("page_name").focus();
        return false;
    }
    if(document.getElementById("mname").value == ""){
        alert("Please enter module name");
        document.getElementById("mname").focus();
        return false;
    }
	if(document.getElementById("fname").value == ""){
        alert("Presenter file should not be empty");
        document.getElementById("fname").focus();
        return false;
    }
    return true;
}

 function regIsDigit(fData)
 {
     var reg = new RegExp("^[0-9]*$");
     return (reg.test(fData));
 }

function assignval(objFrm){
	document.getElementById("cid").value = document.getElementById("course_id").value;
	document.getElementById("qtitle").value = document.getElementById("quiztitle").value;
}

function validatequizzes(objFrm){
	if(document.getElementById("course_id").value == "--"){
		alert("Please select course name");
		document.getElementById("course_id").focus();
		return false;
	}
	if(document.getElementById("quiztitle").value == ""){
		alert("Please select quiz title");
		document.getElementById("quiztitle").focus();
		return false;
	}
	/*if(document.getElementById("module_id") != null){
		if(document.getElementById("module_id").value == "--"){
			alert("Please select module name");
			document.getElementById("module_id").focus();
			return false;
		}
	}
    if(trim(document.getElementById("quizzes_question").value) == ""){
        alert("Please enter quiz question");
        document.getElementById("quizzes_question").focus();
        return false;
    }
    if(trim(document.getElementById("hmany").value) == ""){
        alert("Please enter how many answer you want for this question?");
        document.getElementById("hmany").focus();
        return false;
    }
    if(regIsDigit(trim(document.getElementById("hmany").value)) == false){
        alert("Please enter only numeric value");
        document.getElementById("hmany").focus();
        return false;
    }*/
    return true;
}

function validateanswer(objfrm,homany){
    var radio_choice = false;
    for(i=0; i<homany; i++){
        var field =  document.getElementById("ans[" + i + "]");
        var valtr = 0;
        if(field.value == ""){
            valtr = 1;
            break;
        }
    }
	// Loop from zero to the one minus the number of radio button selections
    for (var counter = 0; counter < objfrm.opt.length; counter++) {
        // If a radio button has been selected it will return true
        // (If not it will return false)          
        if (objfrm.opt[counter].checked)
            radio_choice = true; 
    }

    if (!radio_choice) {
        // If there were no selections made display an alert box 
        alert("Please select correct option.");
        document.frmaddsec.opt[0].focus();
        return false;
    } else if(valtr == 1){
        alert("Please enter answer for this question.");
		document.getElementById("ans[0]").focus();
        return false;
    } else {
        return true;
    }
}

function showhide(id,iid){
    var divid = "aans1"+id;
    var divid1 = "aans"+id;
    if(iid == 1){
        document.getElementById(divid).style.display = '';
        document.getElementById(divid1).style.display = 'none';
    }
    if(iid == 2){
        document.getElementById(divid).style.display = 'none';
        document.getElementById(divid1).style.display = '';
    }
}

function fnAjaxCall(mesg,url,id){ 
    var fname = "frmform"+id;
    new Ajax.Updater( mesg, url, {
                        method: 'post',
                        evalScripts:'true', 
                        postBody:Form.serialize(fname)
                    }
    );
    var aans = "aaans"+id;
    var ans = "ans"+id;
    document.getElementById(aans).innerHTML = document.getElementById(ans).value;
    showhide(id,2)
    return false;
}

function fnAjaxCall2(mesg,url,fname,id){ 
	var cid;
    if(id == 0){
		if(document.getElementById("course_id")){
	        cid = document.getElementById("course_id").value;
		}
    } else {
        cid = id;
    }
	if(cid){
		if(cid != "--"){
			document.getElementById("show_module").style.display = 'block';
			new Ajax.Updater( mesg, url, {
								method: 'post',
								evalScripts:'true', 
								parameters: { course_id: cid },
								postBody:Form.serialize(fname)
							}
			);
		} else {
			document.getElementById("show_module").style.display = 'none';
		}
	}
	return false;
}

function validatetechniques(objFrm){
    if(document.getElementById("title").value == ""){
        alert("Please enter title");
        document.getElementById("title").focus();
        return false;
    }
    if(document.getElementById("sdesc").value == ""){
        alert("Please enter shot description");
        document.getElementById("sdesc").focus();
        return false;
    }
    return true;
}

function validatenews(objFrm){
	if(document.getElementById("newstitle").value == ""){
		alert("Please enter news title");
		document.getElementById("newstitle").focus();
		return false;
	}
	if(document.getElementById("meta_keyword").value == ""){
		alert("Please enter metatag keyword");
		document.getElementById("meta_keyword").focus();
		return false;
	}
	if(document.getElementById("meta_description").value == ""){
		alert("Please enter metatag description");
		document.getElementById("meta_description").focus();
		return false;
	}
	return true;
}

function validateuser(objFrm){
	if(document.getElementById("utype").value == "--"){
		alert("Please select user type");
		document.getElementById("utype").focus();
		return false;
	}
	if(document.getElementById("username").value == ""){
		alert("Please enter user name");
		document.getElementById("username").focus();
		return false;
	}
	if(document.getElementById("strPassword").value == ""){
		alert("Please enter password");
		document.getElementById("strPassword").focus();
		return false;
	}
	if(document.getElementById("cPassword").value == ""){
		alert("Please enter confirm password");
		document.getElementById("cPassword").focus();
		return false;
	}
	if (echeck(document.getElementById("email").value)==false){
		document.getElementById("email").value=""
		document.getElementById("email").focus()
		return false
	}
	if(document.getElementById("utype").value == "temporary"){
		if(document.getElementById("date18").value == "" && document.getElementById("noofhrs").value == ""){
			alert("Please select date \n --OR-- \n Enter value for how many hours, week or month this user should be valid");
			document.getElementById("date18").focus();
			return false;
		}
		
		if(document.getElementById("noofhrs").value != ""){
			if(regIsDigit(document.getElementById("noofhrs").value) == false){
				alert("Please enter only numeric value");
				document.getElementById("noofhrs").focus();
				return false;
			}
		}
	}
	return true;
}

function validatepage(objFrm){
	if(document.getElementById("page_name").value == "--"){
		alert("Please select page name");
		document.getElementById("page_name").focus();
		return false;
	}
	if(document.getElementById("page_title").value == ""){
		alert("Please enter page title");
		document.getElementById("page_title").focus();
		return false;
	}
	if(document.getElementById("by_whom").value == ""){
		alert("Please enter by whome");
		document.getElementById("by_whom").focus();
		return false;
	}
	return true;
}

function hideshow(objfrm){
	if(document.getElementById("utype").value == "temporary"){
		document.getElementById("tempdate").style.display = 'block';
		document.getElementById("temphrs").style.display = 'block';
		document.getElementById("ordiv").style.display = 'block';
	} else {
		document.getElementById("tempdate").style.display = 'none';
		document.getElementById("temphrs").style.display = 'none';
		document.getElementById("ordiv").style.display = 'none';
	}
}

function openwin(id){
	var url = "showpath.php?id="+id;
	window.open (url,"mywindow","location=1,status=1,scrollbars=1,width=350,height=100"); 
}