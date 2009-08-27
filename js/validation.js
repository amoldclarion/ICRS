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
function validatelogin(objFrm){
    if(document.getElementById("userid").value == ""){
        alert("Please enter user name");
        document.getElementById("userid").focus();
        return false;
    }
    if(document.getElementById("pass").value == ""){
        alert("Please enter password");
        document.getElementById("pass").focus();
        return false;
    }
    return true;
}

function checkform(objFrm){
	if(document.getElementById("oldpass").value == ""){
        alert("Please enter old password");
        document.getElementById("oldpass").focus();
        return false;
    }
	if(document.getElementById("newpass").value == ""){
        alert("Please enter new password");
        document.getElementById("newpass").focus();
        return false;
    }
	if(document.getElementById("cpass").value == ""){
        alert("Please enter Confirm password");
        document.getElementById("cpass").focus();
        return false;
    }
    return true;
}

function validateemail1(){
	if (echeck(document.getElementById("email").value)==false){
		document.getElementById("email").value=""
		document.getElementById("email").focus()
		return false
	}
	return true;	
}

function validatesearch(objFrm){
	if(document.getElementById("search1").value == ""){
		alert("Please enter search text");
		document.getElementById("search1").focus();
		return false;
	}
	return true;
}

function changeclass(idname){
	//alert(document.getElementById(idname).style.className);
	document.getElementById(idname).style.className = 'menuon';
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
		alert("Please enter message here");
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