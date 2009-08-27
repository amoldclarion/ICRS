<?php
    /**
    * @version viewcontact.php 2009-08-09 $
    * @copyright Copyright (C) icrs.com. All rights reserved.
    *
    * @author Amol Divalkar
    * @Modified By 
    * @version 1.0 
    * @desc This is main file (addmodule) which is used to render addmodule page for the project
    */
   
    //Includes config file
    include("../includes/config.inc.php");
    //Includes global class file
    include(DIR_ADMIN_CLASSES."/clsGlobal.php");
    //Includes simple image file.
    include(DIR_ADMIN_CLASSES."/SimpleImage.php");

    //Checks if session is set for admin login or not
    if($_SESSION["UsErId"] == "" && $_SESSION["UnAmE"] == ""){
        header("Location:index.php");
    }
    //Creates object for global class file
    $hldGlobal = new clsGlobal($hdlDb);

    //Creates the object for sigma template.
    $hdlTpl =& new HTML_Template_Sigma( DIR_ADMIN_TEMPLATE , DIR_ADMIN_TEMPLATE."/prepared");
    //Loads the template from template folder
    $hdlTpl->loadTemplateFile("viewcontact.htm",TRUE,TRUE); 
      
    $strHeader = $hldGlobal->fnGetHeader();//Fetches header template
    $strMetatag = $hldGlobal->fnGetMetatag();//Fetches Metatag template
    $strFooter = $hldGlobal->fnGetFooter();//Fetches footer template
    $strLeftMenu = $hldGlobal->fnGetLMenu();//Fetches left menu template
    //$strOption = $hldGlobal->fnGetCource($_POST["course_id"]);//Fetches options

    $hdlTpl->setVariable("metatag",$strMetatag);//Assigns Metatag
    $hdlTpl->setVariable("header",$strHeader);//Assigns Header 
    $hdlTpl->setVariable("footer",$strFooter);//Assigns footer
    $hdlTpl->setVariable("leftmenu",$strLeftMenu);//Assigns left menu
   
    $arrModule1 = $hldGlobal->fnFetchContact($_GET["id"]);
    if(is_array($arrModule1) && count($arrModule1) > 0){
        $hdlTpl->setVariable("name",$arrModule1[0]["name"]);//Assigns name
        $hdlTpl->setVariable("emailaddress",$arrModule1[0]["email"]);//Assigns email address
        $hdlTpl->setVariable("message",nl2br($arrModule1[0]["briefmessage"]));//Assigns message
    }
    $hdlTpl->parse("ADMIN_INNER_PAGE");
    $hdlTpl->parse("INNER_PAGE");
    echo $hdlTpl->get();
    
?>