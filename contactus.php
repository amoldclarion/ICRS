<?php
    /**
    * @version contactus.php 2009-06-12 $
    * @copyright Copyright (C) peachtree. All rights reserved.
    *
    * @author Amol Divalkar
    * @Modified By 
    * @version 1.0 
    * @desc This is main file (contactus) which is used to render contactus pages
    */
      
    //Includes config file
    include("includes/config.inc.php");
     
    $CHALLENGE_FIELD_PARAM_NAME = "challenge_string"; 
    //Includes config file
   include("includes/challenge.php");

    //Includes global class file
    include(DIR_CLASSES."/clsGlobal.php");   
    
    //Creates object for global class file
    $hldGlobal = new clsGlobal($hdlDb);
    
    if($_POST["Submit"] == "Contact Us"){
        $intSuccess = 0;
        
        if(trim($_POST["name"]) == "" || trim($_POST["email"]) == "" || trim($_POST["briefmessage"] == "" || trim($_POST['challenge_string']) == "")){
            $intSuccess = 1;
        } else if(isChallengeAccepted($_POST['challenge_string']) === FALSE) {
            $intSuccess = 3;
        } else {
            $sqlContact = "INSERT INTO tblContact(id,name,email,briefmessage) VALUES('','".addslashes(trim($_POST["name"]))."','".addslashes(trim($_POST["email"]))."','".addslashes(trim($_POST["briefmessage"]))."')";
            mysql_query($sqlContact) or die(mysql_error());
            $intSuccess = 2;
            header("Location:contactus.php?succ=2");
        }
    }
    
  
    //Creates the object for sigma template.
    $hdlTpl =& new HTML_Template_Sigma( DIR_TEMPLATE , DIR_TEMPLATE."/prepared");
    //Loads index template file
    $hdlTpl->loadTemplateFile("contactus.htm",TRUE,TRUE); 
               
    $strHeader = $hldGlobal->fnGetHeader();//Fetches header template
    $strMetatag = $hldGlobal->fnMetaTag();//Fetches Metatag template
    $strFooter = $hldGlobal->fnGetFooter();//Fetches footer template
    $strLmenu = $hldGlobal->fnMenu("",$arrMpage);//Fetches left menu
    
    $hdlTpl->setVariable("metatag",$strMetatag);//Assigns Metatag 
    $hdlTpl->setVariable("header",$strHeader);//Assigns header
    $hdlTpl->setVariable("footer",$strFooter);//Assigns footer
    $hdlTpl->setVariable("menu",$strLmenu);//Assigns left menu
    
    if($intSuccess == 1){
        $hdlTpl->setVariable("error_message","Some form fields empty");
    } else if($_GET["succ"] == 2){
        $hdlTpl->setVariable("error_message","Information saved successfully");
    } else if($intSuccess == 3){
        $hdlTpl->setVariable("error_message","The entered verification code was not correct.");
    }
    
    if($intSuccess){
        foreach($_POST as $key => $value){
            $hdlTpl->setVariable($key,$value);    
        }
    }
     
    
    $hdlTpl->setVariable("space","&nbsp;");
    $hdlTpl->parse("ADMIN_INNER_PAGE");
    $hdlTpl->parse("INDEX_PAGE");
     
    echo $hdlTpl->get();
?>