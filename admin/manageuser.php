<?php
    /**
    * @version manageuser.php 2009-06-10 $
    * @copyright Copyright (C) peachtreecardiovascular.com. All rights reserved.
    *
    * @author Amol Divalkar
    * @Modified By 
    * @version 1.0 
    * @desc This is main file (manageuser) which is used to manage the user
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
    $hdlTpl->loadTemplateFile("manageuser.htm",TRUE,TRUE);  
    
    $strHeader = $hldGlobal->fnGetHeader();//Fetches header template
    $strMetatag = $hldGlobal->fnGetMetatag();//Fetches Metatag template
    $strFooter = $hldGlobal->fnGetFooter();//Fetches footer template
    $strLeftMenu = $hldGlobal->fnGetLMenu();//Fetches left menu template
    
    $hdlTpl->setVariable("metatag",$strMetatag);//Assigns Metatag
    $hdlTpl->setVariable("header",$strHeader);//Assigns Header 
    $hdlTpl->setVariable("footer",$strFooter);//Assigns footer
    $hdlTpl->setVariable("leftmenu",$strLeftMenu);//Assigns left menu
    
    if($_POST["submit"] == "Go"){
        if($_POST["status"] != "--"){
            $strSearch = $_POST["status"];
            if($strSearch){
               $hdlTpl->setVariable("act","selected='selected'");
            } else if(!$strSearch){
                $hdlTpl->setVariable("inact","selected='selected'");
            }
        }
    }
    
    $arrRecordSet = $hldGlobal->fnFetchManageusers(0,$strSearch);//Fetches left menu template
    
    $arrPagerData = $hldGlobal->fnGetPagerArr($arrRecordSet);
    
    if($arrPagerData){
        for($i=0;$i<count($arrPagerData)-1;$i++){
            $arrKeys = array_keys($arrPagerData);
            if($arrPagerData[$arrKeys[$i]]["isactive"]){
                $strStatus = "Active";
                $intActiveFlg = 0;
            } else {
                $strStatus = "In Active";
                $intActiveFlg = 1;
            }
            $hdlTpl->setVariable("uname",$arrPagerData[$arrKeys[$i]]["username"]);//Assigns title
            $hdlTpl->setVariable("email",$arrPagerData[$arrKeys[$i]]["email"]);//Assigns page name
            $hdlTpl->setVariable("utype",ucfirst($arrPagerData[$arrKeys[$i]]["usertype"]));//Assigns user type
            $hdlTpl->setVariable("status",$strStatus);//Assigns status
            $hdlTpl->setVariable("returnurl",$hldGlobal->fnEncodeURL($_SERVER["REQUEST_URI"]));//Assigns encode url
            $hdlTpl->setVariable("activeflg",$intActiveFlg);//Assigns Active / Inactive flag
            $hdlTpl->setVariable("id",$arrPagerData[$arrKeys[$i]]["id"]);//Assigns id
            $hdlTpl->parse("managefiles");        
        }
        $hdlTpl->setVariable("paging",$arrPagerData["paging"]);
    } else {
        $hdlTpl->setVariable("space","&nbsp;");//Assigns space
        $hdlTpl->parse("NoManageFiles");
    }
    
    $hdlTpl->parse("ADMIN_INNER_PAGE");
    $hdlTpl->parse("INNER_PAGE");
    echo $hdlTpl->get();
    
?>
