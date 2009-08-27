<?php
    /**
    * @version adduser.php 2009-06-10 $
    * @copyright Copyright (C) peachtreecardiovascular.com. All rights reserved.
    *
    * @author Amol Divalkar
    * @Modified By 
    * @version 1.0 
    * @desc This is main file (adduser) which is used to adding user 
    */
    
    //Includes config file
    include("../includes/config.inc.php");
    //Includes global class file
    include(DIR_ADMIN_CLASSES."/clsGlobal.php");

    //Checks if session is set for admin login or not
    if($_SESSION["UsErId"] == "" && $_SESSION["UnAmE"] == ""){
        header("Location:index.php");
    }
    
    //Creates object for global class file
    $hldGlobal = new clsGlobal($hdlDb);

    //Creates the object for sigma template.
    $hdlTpl =& new HTML_Template_Sigma( DIR_ADMIN_TEMPLATE , DIR_ADMIN_TEMPLATE."/prepared");
    //Loads the template from template folder
    $hdlTpl->loadTemplateFile("adduser.htm",TRUE,TRUE);  
    
    if($_POST["submit"] == "Add User"){//Checks if form is submited or not.
        $strErrorMessage = "";
        $intError = 0;
        if(trim($_POST["utype"]) == "--"){
            $strErrorMessage .= "Please select user type<br />";
            $intError = 1;
        }
        if(trim($_POST["username"]) == ""){
            $strErrorMessage .= "User name should not be blank<br />";
            $intError = 1;
        }
        if(trim($_POST["strPassword"]) == ""){
            $strErrorMessage .= "Password should not be blank<br />";
            $intError = 1;
        }
        if(trim($_POST["cPassword"]) == ""){
            $strErrorMessage .= "Confirm Password should not be blank<br />";
            $intError = 1;
        }
        if(trim($_POST["email"]) == ""){
            $strErrorMessage .= "Email should not be blank<br />";
            $intError = 1;
        }
        if(trim(strlen($_POST["strPassword"])) < 4){
            $strErrorMessage .= "Password should be atlest 4 character long<br />";
            $intError = 1;
        }
        if(trim($_POST["strPassword"]) != trim($_POST["cPassword"])) {
            $strErrorMessage .= "Password does not match<br />";
            $intError = 1;                                                                     
        } 
        
        if(trim($_POST["email"]) != ""){
            $intEmail = $hldGlobal->fnCheckEmail($_POST["email"],$_SESSION["UsErId"]);
            if($intEmail){
                $strErrorMessage .= "Email already present<br />";  
                $intError = 1;
            }
        }
        
        if(trim($_POST["utype"]) == "--"){
            if($_POST["date18"] == "" && trim($_POST["noofhrs"]) == ""){
                $strErrorMessage .= "Please select date <br> --OR-- <br> Enter value for how many hours, week or month this user should be valid<br />";
                $intError = 1;
            }
            
            if(trim($_POST["noofhrs"]) != ""){
                if(!preg_match("/^[0-9]*$/",trim($_POST["noofhrs"]))){
                    $strErrormessage .= "Please enter only numeric value.<br>";
                    $intError = 0;
                }
            }
            
            if($_POST["date18"] != ""){
                $today = date("Y-m-d H:i:s");
                $arrTimeStamp = $hldGlobal->fnGetTimeStamp($_POST["date18"],$today);
                if($arrTimeStamp[0]["posteddate"] <= $arrTimeStamp[0]["currdate"]){
                    $strErrorMessage .= "Selected date should be greater then today's date.<br>";
                    $intError = 0;
                }
            }
            
        }
        
        
        if(!$intError) {
            $arrCheck = $hldGlobal->fnCheckUser(trim($_POST["username"]));
            if($arrCheck[0]["cnt"]){                                   
                $hdlTpl->setVariable("error_message","User name already present");//Assigns error message
            } else {
                foreach($_POST AS $key => $value){
                    $_POST[$key] = mysql_escape_string(trim($value));
                }
                $date18 = $_POST["date18"]." ".date("H:i:s");
                $sqlInsUser = "INSERT INTO tbluser(id,username,password,firstname,lastname,email,usertype,date,noofhrs,howmany,isactive,dtCreated) VALUES('','".$_POST["username"]."','".md5($_POST["strPassword"])."','".$_POST["firstname"]."','".$_POST["lastname"]."','".$_POST["email"]."','".$_POST["utype"]."','".$date18."','".$_POST["noofhrs"]."','".$_POST["howmany"]."','1','".date("Y-m-d H:i:s")."')";       
                mysql_query($sqlInsUser) or die(mysql_error());
                header("Location:message.php?mess=17");
            }
        }
        $hdlTpl->setVariable("error_message",$strErrorMessage);//Assigns error message
        $hdlTpl->setVariable("username",$_POST["username"]);//Assigns user name
        $hdlTpl->setVariable("firstname",$_POST["firstname"]);//Assigns first name
        $hdlTpl->setVariable("lastname",$_POST["lastname"]);//Assigns last name
        $hdlTpl->setVariable("email",$_POST["email"]);//Assigns email
    }   
      
    $strHeader = $hldGlobal->fnGetHeader();//Fetches header template
    $strMetatag = $hldGlobal->fnGetMetatag();//Fetches Metatag template
    $strFooter = $hldGlobal->fnGetFooter();//Fetches footer template
    $strLeftMenu = $hldGlobal->fnGetLMenu();//Fetches left menu template
    
    $hdlTpl->setVariable("metatag",$strMetatag);//Assigns Metatag
    $hdlTpl->setVariable("header",$strHeader);//Assigns Header 
    $hdlTpl->setVariable("footer",$strFooter);//Assigns footer
    $hdlTpl->setVariable("leftmenu",$strLeftMenu);//Assigns left menu
    
    
    $hdlTpl->parse("ADMIN_INNER_PAGE");
    $hdlTpl->parse("INNER_PAGE");
    echo $hdlTpl->get();
    
?>
