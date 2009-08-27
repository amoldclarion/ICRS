<?php
    /**
    * @version editprofile.php 2009-07-17 $
    * @copyright Copyright (C) ircs. All rights reserved.
    *
    * @author Amol Divalkar
    * @Modified By 
    * @version 1.0 
    * @desc This is main file (editprofile) which is used to render editprofile page for the project
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
    $hdlTpl->loadTemplateFile("editprofile.htm",TRUE,TRUE); 
    
    $hldGlobal->fnTrackUser("Edit Profile",$_SESSION["MaInId"]);
    
    $strHeader = $hldGlobal->fnGetHeader();//Fetches header template
    $strMetatag = $hldGlobal->fnMetaTag();//Fetches Metatag template
    $strFooter = $hldGlobal->fnGetFooter();//Fetches footer template
    $strLmenu = $hldGlobal->fnMenu("",$arrMpage);//Fetches left menu
    
    $hdlTpl->setVariable("metatag",$strMetatag);//Assigns Metatag 
    $hdlTpl->setVariable("header",$strHeader);//Assigns header
    $hdlTpl->setVariable("footer",$strFooter);//Assigns footer
    $hdlTpl->setVariable("menu",$strLmenu);//Assigns left menu
    
    if($_POST["submit"] == "Save"){
        foreach($_POST as $key => $value){
            $_POST[$key] = mysql_escape_string(trim($value));
        }
        $sqlUpd = "UPDATE tbluser SET firstname='".$_POST["fname"]."',lastname='".$_POST["lname"]."' WHERE id=".$_POST["id"];
        mysql_query($sqlUpd);
        header("Location:editprofile.php?er=1");
    }
    
    if($_GET["er"]){
        $hdlTpl->setVariable("emessage","User updated successfully!");//Assigns error message
        $hdlTpl->parse("error_blk");
    }
    
    $arrUser = $hldGlobal->fnGetUser($_SESSION["UID"]);
    
    if(is_array($arrUser) && count($arrUser) > 0){
        $hdlTpl->setVariable("uname",$arrUser[0]["username"]);//Assigns user name
        $hdlTpl->setVariable("email",$arrUser[0]["email"]);//Assigns email
        $hdlTpl->setVariable("fname",$arrUser[0]["firstname"]);//Assigns first name
        $hdlTpl->setVariable("lname",$arrUser[0]["lastname"]);//Assigns last name
        $hdlTpl->setVariable("id",$arrUser[0]["id"]);//Assigns last name
    }
    
    $hdlTpl->setVariable("space","&nbsp;");//Assigns space
    $hdlTpl->parse("INDEX_PAGE");
    echo $hdlTpl->get();
    
?>