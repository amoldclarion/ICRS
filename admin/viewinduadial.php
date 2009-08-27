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
    $hdlTpl->loadTemplateFile("viewinduadial.htm",TRUE,TRUE); 
      
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
     
    $arrStats = $hldGlobal->fnViewStats($_GET["mid"]);
    $arrExtra = array("mid"=>$_GET["mid"]);
    $arrModule = $hldGlobal->fnGetPagerArr($arrStats,$arrExtra);
    
    if(is_array($arrModule) && count($arrModule) > 0){
        foreach($arrModule as $key => $value){
            if(is_numeric($key)){
                $strQuizTitle = "";
                //$strCourseName = $hldGlobal->fnGetCourcesName($value["course_id"]);
                $strQuizTitle = $hldGlobal->fnGetQuizTitle($value["quiz_title_id"],$value["course_id"]);
                if($value["timeout"] != 0){
                    $intSecond = strtotime($value["timeout"]) - strtotime($value["timein"]);
                    $tspent = $hldGlobal->sec2hms($intSecond);
                } else {
                    $tspent = "--";
                }
                $hdlTpl->setVariable("date",$value["dvisited"]);//Assigns date visited
                $hdlTpl->setVariable("page_name",$value["pagename"]);//Assigns page name
                $hdlTpl->setVariable("course_name",$strQuizTitle);//Assigns course name
                $hdlTpl->setVariable("tspent",$tspent);//Assigns time spend
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