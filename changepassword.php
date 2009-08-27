<?php
    /**
    * @version changepassword.php 2009-07-17 $
    * @copyright Copyright (C) ircs. All rights reserved.
    *
    * @author Amol Divalkar
    * @Modified By 
    * @version 1.0 
    * @desc This is main file (changepassword) which is used to render changepassword page for the project
    */
      
    //Includes config file
    include("includes/config.inc.php");
     
    //Includes global class file
    include(DIR_CLASSES."/clsGlobal.php");
    
    //Checks if session is set for admin login or not
    if($_SESSION["UID"] == "" && $_SESSION["UNAME"] == ""){
        $strRedirect = SITE_NAME."login.htm";
        header("Location:$strRedirect");
    }
    
    //Creates object for global class file
    $hldGlobal = new clsGlobal($hdlDb);
  
    $intUCheck = $hldGlobal->fnUserCheckTemp($_SESSION["UID"]);
    if($intUCheck){
        $hldGlobal->fnInActiveUser($_SESSION["UID"]);
        $strLogOut = SITE_NAME."log/1/login.htm";
        header("Location:$strLogOut");
    }
    
    //Creates the object for sigma template.
    $hdlTpl =& new HTML_Template_Sigma( DIR_TEMPLATE , DIR_TEMPLATE."/prepared");
    //Loads index template file
    $hdlTpl->loadTemplateFile("changepassword.htm",TRUE,TRUE); 
    
    $hldGlobal->fnTrackUser("Change Password",$_SESSION["MaInId"]);
    
    $strHeader = $hldGlobal->fnGetHeader();//Fetches header template
    $strMetatag = $hldGlobal->fnMetaTag();//Fetches Metatag template
    $strFooter = $hldGlobal->fnGetFooter();//Fetches footer template
    $strLmenu = $hldGlobal->fnMenu("",$arrMpage);//Fetches left menu
    
    if($_POST["submit"] == "Update Password"){
        $strErrormessage = "";
        $intError = 1;
        if(trim($_POST["oldpass"]) == ""){
            $strErrormessage .= "Please enter old password<br>";
            $intError = 0;
        }
        if(trim($_POST["newpass"]) == ""){
            $strErrormessage .= "Please enter new password<br>";
            $intError = 0;
        }
        if(trim($_POST["cpass"]) == ""){
            $strErrormessage .= "Please enter confirm password<br>";
            $intError = 0;
        }
        if(trim($_POST["newpass"]) != trim($_POST["cpass"])){
            $strErrormessage .= "New password and confirm password does not match<br>";
            $intError = 0;
        }
        if(strlen(trim($_POST["newpass"])) < 4 ){
            $strErrormessage .= "Password should be atleast 4 character long<br>";
            $intError = 0;
        }
        $intCheck = $hldGlobal->fnCheckUserOldPass(trim($_POST["oldpass"]),$_POST["id"]);
        if(!$intCheck){
            $strErrormessage .= "Old password does not match<br>";
            $intError = 0;
        }
        
        if($intError){
            $sqlUpd = "UPDATE tbluser SET password='".md5(trim($_POST["newpass"]))."' WHERE id=".$_POST["id"];
            mysql_query($sqlUpd) or die(mysql_error());
            header("Location:changepassword.php?er=1");
        } 
    }
    
    if(!$intError){
        $hdlTpl->setVariable("emessage",$strErrormessage);//Assigns message
        $hdlTpl->parse("error_blk");
    }
    if($_GET["er"]){
        $hdlTpl->setVariable("emessage","Password updated successfully!");//Assigns message
        $hdlTpl->parse("error_blk");
    }
    
    $hdlTpl->setVariable("metatag",$strMetatag);//Assigns Metatag 
    $hdlTpl->setVariable("header",$strHeader);//Assigns header
    $hdlTpl->setVariable("footer",$strFooter);//Assigns footer
    $hdlTpl->setVariable("menu",$strLmenu);//Assigns left menu
    $hdlTpl->setVariable("id",$_SESSION["UID"]);//Assigns id
    
    $hdlTpl->setVariable("space","&nbsp;");//Assigns space
    $hdlTpl->parse("INDEX_PAGE");
    echo $hdlTpl->get();
    
?>