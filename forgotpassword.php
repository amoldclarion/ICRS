<?php
    /**
    * @version forgotpassword.php 2009-07-22 $
    * @copyright Copyright (C) icrs.com. All rights reserved.
    *
    * @author Amol Divalkar
    * @Modified By 
    * @version 1.0 
    * @desc This is main file (forgotpassword) which is used to render forgotpassword page for the project
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
    $hdlTpl->loadTemplateFile("forgotpassword.htm",TRUE,TRUE); 
    
    $strHeader = $hldGlobal->fnGetHeader();//Fetches header template
    $strMetatag = $hldGlobal->fnMetaTag();//Fetches Metatag template
    $strFooter = $hldGlobal->fnGetFooter();//Fetches footer template
    $strLmenu = $hldGlobal->fnMenu("",$arrMpage);//Fetches left menu
    
    $hdlTpl->setVariable("metatag",$strMetatag);//Assigns Metatag 
    $hdlTpl->setVariable("header",$strHeader);//Assigns header
    $hdlTpl->setVariable("footer",$strFooter);//Assigns footer
    $hdlTpl->setVariable("menu",$strLmenu);//Assigns left menu
    
    if($_POST["submit"] == "Forgot Password"){
        $strErrorMessage = "";
        $intError = 0;
        $intCheck = $hldGlobal->fnCheckEmail($_POST["email"]);
        if(!$intCheck){
            $strErrorMessage = "Email address does not exists!";
            $intError = 1;
        } else {
            $strPass =  $hldGlobal->generatePassword(5,8);
            $arrUser = $hldGlobal->fnGetUserEmail($_POST["email"]);
            
            $sqlUpd = "UPDATE tbluser SET password='".md5($strPass)."' WHERE id=".$arrUser[0]["id"];
            mysql_query($sqlUpd) or die(mysql_error());
            
            $to      = $_POST["email"];
            $subject = 'Login credentials';
            $message = 'Hi 
            Your login credentials
            
            User name : '.$arrUser[0]["username"].'
            Password : '.$strPass;
            
            $headers = 'From: '.ADMIN_EMAIL . "\r\n" .
                'X-Mailer: PHP/' . phpversion();

            mail($to, $subject, $message, $headers);
            
            header("Location:succ/1/forgotpassword.htm");

        }
    }
    if($_GET["succ"]){
        $strErrorMessage = "Please check your email.<br>Password reseted successfully.";
    }
    $hdlTpl->setVariable("error_message",$strErrorMessage);//Assigns error
    foreach($_POST as $key => $value){
        $hdlTpl->setVariable($key,$value);//Assigns value
    }
    $hdlTpl->setVariable("space","&nbsp;");//Assigns left menu
    $hdlTpl->parse("INDEX_PAGE");
    echo $hdlTpl->get();
    
?>