<?php
    /**
    * @version edituser.php 2009-06-10 $
    * @copyright Copyright (C) peachtreecardiovascular.com. All rights reserved.
    *
    * @author Amol Divalkar
    * @Modified By 
    * @version 1.0 
    * @desc This is main file (edituser) which is used to edit the user.
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
    $hdlTpl->loadTemplateFile("edituser.htm",TRUE,TRUE);  
    
    if($_POST["submit"] == "Save"){//Checks if form is submited or not.
        $strErrorMessage = "";
        $intError = 0;
        foreach($_POST as $key => $value){
            $_POST[$key] = trim($value);
        }
        if($_POST["username"] == ""){
            $strErrorMessage .= "User name should not be empty<br />";
            $intError = 1;
        } else if($_POST["strNPassword"] != ""){
            /*$intCheckOld = $hldGlobal->fnCheckPassword($_POST["strOPassword"],$_POST["id"]);
            
            if(!$intCheckOld){
                $strErrorMessage .= "Old Password does not match with our records<br />";
                $intError = 1;
            }*/
            if(trim(strlen($_POST["strNPassword"])) < 4){
                $strErrorMessage .= "Password should be atlest 4 character long<br />";
                $intError = 1;
            } 
            if($_POST["strNPassword"] == "" || $_POST["cPassword"] == ""){
                $strErrorMessage .= "New Password and Confirm password should not be blank<br />";
                $intError = 1;
            } 
            if($_POST["strNPassword"] != $_POST["cPassword"]) {
                $strErrorMessage .= "Password does not match<br />";
                $intError = 1;
            }
        }
        
        if($_POST["date18"] == "" && trim($_POST["noofhrs"]) == ""){
            $strErrorMessage .= "Please select date <br> --OR-- <br> Enter value for how many hours, week or month this user should be valid<br />";
            $intError = 1;
        }
        
        if(trim($_POST["noofhrs"]) != ""){
            if(!preg_match("/^[0-9]*$/",trim($_POST["noofhrs"]))){
                $strErrorMessage .= "Please enter only numeric value.<br>";
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
        
        $hdlTpl->setVariable("error_message",$strErrorMessage);//Assigns error message
        
        if(!$intError) {
            $arrCheck = $hldGlobal->fnCheckUser($_POST["strUserName"],$_POST["id"]);
            if($arrCheck[0]["cnt"]){                                   
                $hdlTpl->setVariable("error_message","User name already present");//Assigns error message
            } else {
                foreach($_POST AS $key => $value){
                    $_POST[$key] = mysql_escape_string(trim($value));
                }
                $date18 = $_POST["date18"]." ".date("H:i:s");
                $sqlUpd = "UPDATE tbluser SET username='".$_POST["username"]."',firstname='".$_POST["firstname"]."',lastname='".$_POST["lastname"]."',date='".$date18."',noofhrs='".$_POST["noofhrs"]."',howmany='".$_POST["howmany"]."' ";
                if($_POST["strOPassword"] != ""){
                    $sqlUpd .= ",password='".md5($_POST["strNPassword"])."'";
                }
                $sqlUpd .= " WHERE id=".$_POST["id"];
                mysql_query($sqlUpd) or die(mysql_error());
                $return = $_POST["return"];
                header("Location:message.php?mess=16&return=$return");
            }
        }
    }   
    
    $arrEditUser = $hldGlobal->fnFetchManageusers($_GET["id"]);
    
    if(is_array($arrEditUser) && count($arrEditUser) > 0){
        $id = ($_GET["id"]) ? $_GET["id"] : $_POST["id"];
        $return = ($_GET["return"]) ? $_GET["return"] : $_POST["return"];
        $strDuration = "";
        if($arrEditUser[0]["date"] != 0){
            $strDuration = $arrEditUser[0]["date"];
        } else if($arrEditUser[0]["noofhrs"]){
            $strDuration = $arrEditUser[0]["noofhrs"]." ".$arrEditUser[0]["howmany"];
        }
        
        $hdlTpl->setVariable("utype",ucfirst($arrEditUser[0]["usertype"]));//Assigns user type
        $hdlTpl->setVariable("email",$arrEditUser[0]["email"]);//Assigns user name
        $hdlTpl->setVariable("duration",$strDuration);//Assigns Duration
        $hdlTpl->setVariable("username",$arrEditUser[0]["username"]);//Assigns user name
        $hdlTpl->setVariable("firstname",$arrEditUser[0]["firstname"]);//Assigns user name
        $hdlTpl->setVariable("lastname",$arrEditUser[0]["lastname"]);//Assigns user name
        $hdlTpl->setVariable("id",$id);//Assigns id
        $hdlTpl->setVariable("return",$return);//Assigns return
        if($arrEditUser[0]["date"] != 0 || $arrEditUser[0]["noofhrs"] != 0){
            $hdlTpl->setVariable("date18",$arrEditUser[0]["date"]);//Assigns date
            $hdlTpl->setVariable("noofhrs",$arrEditUser[0]["noofhrs"]);//Assigns no of hrs
            if($arrEditUser[0]["howmany"] == "HOUR"){
                $hdlTpl->setVariable("selhrs","selected='selected'");//Assigns hours
            } else if($arrEditUser[0]["howmany"] == "DAY"){
                $hdlTpl->setVariable("selday","selected='selected'");//Assigns day
            } else if($arrEditUser[0]["howmany"] == "MONTH"){
                $hdlTpl->setVariable("selmon","selected='selected'");//Assigns months
            }
            $hdlTpl->parse("temp_time");
        }
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
