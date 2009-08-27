<?php
    /**
    * @version viewstats.php 2009-07-27 $
    * @copyright Copyright (C) icrs.com. All rights reserved.
    *
    * @author Amol Divalkar
    * @Modified By 
    * @version 1.0 
    * @desc This is main file (viewstats) which is used to render viewstats page for the project
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
    $hdlTpl->loadTemplateFile("viewstats.htm",TRUE,TRUE); 
      
    $strHeader = $hldGlobal->fnGetHeader();//Fetches header template
    $strMetatag = $hldGlobal->fnGetMetatag();//Fetches Metatag template
    $strFooter = $hldGlobal->fnGetFooter();//Fetches footer template
    $strLeftMenu = $hldGlobal->fnGetLMenu();//Fetches left menu template
    
    $hdlTpl->setVariable("metatag",$strMetatag);//Assigns Metatag
    $hdlTpl->setVariable("header",$strHeader);//Assigns Header 
    $hdlTpl->setVariable("footer",$strFooter);//Assigns footer
    $hdlTpl->setVariable("leftmenu",$strLeftMenu);//Assigns left menu
    $hdlTpl->setVariable("uname",$hldGlobal->fnGetUser($_GET["i"]));//Assigns user name
    $hdlTpl->setVariable("date1",base64_decode($_GET["d"]));//Assigns module name
     
    $arrStats = $hldGlobal->fnGetStatsUser($_GET["i"]);
    $arrExtra = array("i"=>$_GET["i"]);
    $arrModule = $hldGlobal->fnGetPagerArr($arrStats,$arrExtra);
    
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
                $hdlTpl->setVariable("returnurl",$hldGlobal->fnEncodeURL($_SERVER["REQUEST_URI"]));//Assigns encode url
                $intSecond = strtotime($value["timeout"]) - strtotime($value["timein"]);
                $hdlTpl->setVariable("ipaddress",$value["ipaddress"]);//Assigns encode ip
                $hdlTpl->setVariable("mid",$value["id"]);//Assigns encode ip
                $hdlTpl->setVariable("date",$value["dvisited"]);//Assigns encode ip
                $hdlTpl->setVariable("country",$value["cname"]);//Assigns module order
                $hdlTpl->setVariable("reqion",$value["rname"]);//Assigns cources name
                $hdlTpl->setVariable("city",$value["city"]);//Assigns cources name
                $hdlTpl->setVariable("tspent",$hldGlobal->sec2hms($intSecond));//Assigns cources name
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