<?php
    /**
    * @version managepage.php 2009-07-15 $
    * @copyright Copyright (C) icrs.com. All rights reserved.
    *
    * @author Amol Divalkar
    * @Modified By 
    * @version 1.0 
    * @desc This is main file (managepage) which is used to render managepage page for the project
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
    $hdlTpl->loadTemplateFile("managepage.htm",TRUE,TRUE); 
      
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
    
    $arrRecordSet = $hldGlobal->fnFetchPage(0,$strSearch);
    
    $arrPages = $hldGlobal->fnGetPagerArr($arrRecordSet);
    
    if(is_array($arrPages) && count($arrPages) > 0){
        foreach($arrPages as $key => $value){
            if(is_numeric($key)){
                if($value["isactive"]){
                    $intstatus = 0;
                    $hdlTpl->setVariable("status","Active");//Assigns status
                } else {
                    $intstatus = 1;
                    $hdlTpl->setVariable("status","In Active");//Assigns status
                }
                $hdlTpl->setVariable("returnurl",$hldGlobal->fnEncodeURL($_SERVER["REQUEST_URI"]));//Assigns encode url
                $hdlTpl->setVariable("id",$value["id"]);//Assigns id
                $hdlTpl->setVariable("activeflg",$intstatus);//Assigns flag
                $hdlTpl->setVariable("page_title",stripslashes(substr($value["page_title"],0,20)."..."));//Assigns title
                $hdlTpl->setVariable("page_name",$hldGlobal->fnGetPageName($value["page_name"]));//Assigns description
                $hdlTpl->setVariable("isorder",$value["isorder"]);//Assigns order
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