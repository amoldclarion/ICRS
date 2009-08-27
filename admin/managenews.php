<?php
    /**
    * @version managenews.php 2009-06-12 $
    * @copyright Copyright (C) peachtreecardiovascular.com. All rights reserved.
    *
    * @author Amol Divalkar
    * @Modified By 
    * @version 1.0 
    * @desc This is main file (managenews) which is used to manage the news
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
    $hdlTpl->loadTemplateFile("managenews.htm",TRUE,TRUE);  
    
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
    
    $arrRecordSet = $hldGlobal->fnFetchManagenews(0,$strSearch);//Fetches left menu template
    
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
            
            $hdlTpl->setVariable("title",stripslashes(substr($arrPagerData[$arrKeys[$i]]["newstitle"],0,20)."..."));//Assigns title
            $hdlTpl->setVariable("ndescription",stripslashes(substr(strip_tags($arrPagerData[$arrKeys[$i]]["content"]),0,20)."..."));//Assigns page name
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
