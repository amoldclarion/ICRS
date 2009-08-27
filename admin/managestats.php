<?php
    /**
    * @version managestats.php 2009-07-27 $
    * @copyright Copyright (C) icrs.com. All rights reserved.
    *
    * @author Amol Divalkar
    * @Modified By 
    * @version 1.0 
    * @desc This is main file (managestats) which is used to render managestats page for the project
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
    $hdlTpl->loadTemplateFile("managestats.htm",TRUE,TRUE); 
      
    $strHeader = $hldGlobal->fnGetHeader();//Fetches header template
    $strMetatag = $hldGlobal->fnGetMetatag();//Fetches Metatag template
    $strFooter = $hldGlobal->fnGetFooter();//Fetches footer template
    $strLeftMenu = $hldGlobal->fnGetLMenu();//Fetches left menu template
    
    $hdlTpl->setVariable("metatag",$strMetatag);//Assigns Metatag
    $hdlTpl->setVariable("header",$strHeader);//Assigns Header 
    $hdlTpl->setVariable("footer",$strFooter);//Assigns footer
    $hdlTpl->setVariable("leftmenu",$strLeftMenu);//Assigns left menu
    
    $arrStats = $hldGlobal->fnGetStats();
    
    $arrModule = $hldGlobal->fnGetPagerArr($arrStats);
    if(is_array($arrModule) && count($arrModule) > 0){
        foreach($arrModule as $key => $value){
            if(is_numeric($key)){
                if($value["isactive"]){
                    $intstatus = 0;
                    $hdlTpl->setVariable("status","Active");//Assigns status
                } else {
                    $intstatus = 1;
                    $hdlTpl->setVariable("status","In Active");//Assigns status
                }
                //$intTimeSpend = strtotime($value["timeout"]) - strtotime($value["timein"]);
                $intTimeSpend = $value["tout"] - $value["tin"];
                $hdlTpl->setVariable("returnurl",$hldGlobal->fnEncodeURL($_SERVER["REQUEST_URI"]));//Assigns encode url
                $hdlTpl->setVariable("uid",$value["userid"]);//Assigns encode ip
                $hdlTpl->setVariable("udate",base64_encode($value["datecreated"]));//Assigns encode ip
                $hdlTpl->setVariable("uname",$hldGlobal->fnGetUser($value["userid"]));//Assigns module name            
                $hdlTpl->setVariable("date",$value["dvisited"]);//Assigns date visited
                $hdlTpl->setVariable("totspendtime",$hldGlobal->sec2hms($intTimeSpend));//Assigns time spend           
                $hdlTpl->setVariable("country",$value["cname"]);//Assigns country name
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