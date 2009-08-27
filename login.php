<?php
    /**
    * @version login.php 2009-07-16 $
    * @copyright Copyright (C) icrs.com. All rights reserved.
    *
    * @author Amol Divalkar
    * @Modified By 
    * @version 1.0 
    * @desc This is main file (login) which is used to render login page for the project
    */
      
    //Includes config file
    include("includes/config.inc.php");
     
    //Includes global class file
    include(DIR_CLASSES."/clsGlobal.php");
    //include(DIR_CLASSES."/clsAdmin.php");
    
    //Creates object for global class file
    $hldGlobal = new clsGlobal($hdlDb);
  
    //Creates the object for sigma template.
    $hdlTpl =& new HTML_Template_Sigma( DIR_TEMPLATE , DIR_TEMPLATE."/prepared");
    //Loads index template file
    $hdlTpl->loadTemplateFile("login.htm",TRUE,TRUE); 
    
    if($_POST["submit"] == "Login"){
        $strErrorMessage = "";
        $intError = 1;
        if(trim($_POST["userid"]) == ""){
            $strErrorMessage .= "Please enter user name<br>";
            $intError = 0;
        }
        if(trim($_POST["pass"]) == ""){
            $strErrorMessage .= "Please enter password<br>";
            $intError = 0;
        }
        if(trim($_POST["userid"]) != "" && trim($_POST["pass"]) != ""){
            $arrCheck = $hldGlobal->fnCheckUserPass(trim($_POST["userid"]),trim($_POST["pass"]));
            if(is_array($arrCheck) && count($arrCheck) > 0){
                if($arrCheck[0]["usertype"] == "temporary"){
                    if($arrCheck[0]["last_logged_in"] != 0){
                        $intUCheck = $hldGlobal->fnUserCheckTemp($arrCheck[0]["id"]);
                        if($intUCheck){
                            $hldGlobal->fnInActiveUser($arrCheck[0]["id"]);
                            $strLogOut = "log/1/login.htm";
                            header("Location:$strLogOut");
                        }
                    } else {
                        $hldGlobal->fnFetchUserDetails($arrCheck[0]["id"],1);
                    }
                }
                
                $_SESSION["UID"] = $arrCheck[0]["id"];
                $_SESSION["UNAME"] = $_POST["userid"];
                
                $hldGlobal->fnUserTrackMain($arrCheck[0]["id"]);
                
                header("Location:index.htm");
            } else {
                $strErrorMessage .= "User name and password does not match<br>";
                $intError = 0;
            }
        }
    }                            
    
    $strHeader = $hldGlobal->fnGetHeader("");//Fetches header template
    $strMetatag = $hldGlobal->fnMetaTag();//Fetches Metatag template
    $strFooter = $hldGlobal->fnGetFooter();//Fetches footer template
    $strLmenu = $hldGlobal->fnMenu("",$arrMpage);//Fetches left menu
    
    $hdlTpl->setVariable("metatag",$strMetatag);//Assigns Metatag 
    $hdlTpl->setVariable("header",$strHeader);//Assigns header
    $hdlTpl->setVariable("footer",$strFooter);//Assigns footer
    $hdlTpl->setVariable("menu",$strLmenu);//Assigns left menu
    
    if($_GET["succ"]){
        $strErrorMessage = "Your login time has expired.<br> Please contact site administrator";
        $hdlTpl->setVariable("error_message",$strErrorMessage);//Assigns error message
    }     
    if(!$intError){
        $hdlTpl->setVariable("error_message",$strErrorMessage);//Assigns error message
    }
    
    foreach($_POST as $key=>$value){
        $hdlTpl->setVariable($key,$value);//Assigns values
    }
    
    $hdlTpl->setVariable("space","&nbsp;");//Assigns left menu
    $hdlTpl->parse("INDEX_PAGE");
    echo $hdlTpl->get();
    
?>