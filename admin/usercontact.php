<?php
    /**
    * @version usercontact.php 2009-08-07 $
    * @copyright Copyright (C) icrs.com. All rights reserved.
    *
    * @author Amol Divalkar
    * @Modified By 
    * @version 1.0 
    * @desc This is main file (usercontact) which is used to render all contact done by user
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
    $hdlTpl->loadTemplateFile("usercontact.htm",TRUE,TRUE); 
      
    $strHeader = $hldGlobal->fnGetHeader();//Fetches header template
    $strMetatag = $hldGlobal->fnGetMetatag();//Fetches Metatag template
    $strFooter = $hldGlobal->fnGetFooter();//Fetches footer template
    $strLeftMenu = $hldGlobal->fnGetLMenu();//Fetches left menu template
    
    $hdlTpl->setVariable("metatag",$strMetatag);//Assigns Metatag
    $hdlTpl->setVariable("header",$strHeader);//Assigns Header 
    $hdlTpl->setVariable("footer",$strFooter);//Assigns footer
    $hdlTpl->setVariable("leftmenu",$strLeftMenu);//Assigns left menu
    $strSearch = "";
    $int = 0;
    if($_POST["submit"] == "Go"){
        if($_POST["nsearch"] != ""){
            $strSearch = $_POST["nsearch"];
            if($strSearch){
               $hdlTpl->setVariable("nsearch",$strSearch);
            }
        }
    }
 
    $arrModule1 = $hldGlobal->fnFetchContact(0,$strSearch);
    $arrModule = $hldGlobal->fnGetPagerArr($arrModule1);
    if(is_array($arrModule) && count($arrModule) > 0){
        foreach($arrModule as $key => $value){
            if(is_numeric($key)){
                $hdlTpl->setVariable("returnurl",$hldGlobal->fnEncodeURL($_SERVER["REQUEST_URI"]));//Assigns encode url
                $hdlTpl->setVariable("id",$value["id"]);//Assigns id
                $hdlTpl->setVariable("name",$value["name"]);//Assigns name
                $hdlTpl->setVariable("email",$value["email"]);//Assigns email address
                $hdlTpl->parse("managefiles");
            } else {
                $strPaging = $value;
            }
        }
        $hdlTpl->setVariable("paging",$strPaging);//Assigns Paging
        $hdlTpl->parse("StartManageFiles");
    } else {
        $hdlTpl->setVariable("space","&nbsp;");//Assigns space
        $hdlTpl->parse("NoManageFiles");
    }
    
    $hdlTpl->parse("ADMIN_INNER_PAGE");
    $hdlTpl->parse("INNER_PAGE");
    echo $hdlTpl->get();
    
?>