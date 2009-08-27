<?php
    /**
    * @version managemodule.php 2009-07-10 $
    * @copyright Copyright (C) icrs.com. All rights reserved.
    *
    * @author Amol Divalkar
    * @Modified By 
    * @version 1.0 
    * @desc This is main file (managemodule) which is used to render managemodule page for the project
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
    $hdlTpl->loadTemplateFile("managemodule.htm",TRUE,TRUE); 
      
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
        if($_POST["status"] != "--"){
            $strSearch = $_POST["status"];
            $int = 1;
            if($strSearch){
               $hdlTpl->setVariable("act","selected='selected'");
            } else if(!$strSearch){
                $hdlTpl->setVariable("inact","selected='selected'");
            }
        }
        if($_POST["page_name"] != "--"){
            $int = 2;
            $strSearch = $_POST["page_name"];
        }
    }
    
    if(is_array($arrPage) && count($arrPage) > 0){
        $strOpt = "";
        foreach($arrPage as $key => $value){
            if($_POST["page_name"] == $key){
                $strSel = "selected='selected'";
            } else {
                $strSel = "";
            }
            $strOpt .= "<option value='".$key."' ".$strSel.">".$value."</option>";
        }
        $hdlTpl->setVariable("optpagename",$strOpt);//Assigns option
    }
    
    $arrModule1 = $hldGlobal->fnFetchModule(0,$strSearch,$int);
    $arrModule = $hldGlobal->fnGetPagerArr($arrModule1);
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
                $sPattern = '/\s/';
                $sReplace = '_';
                $strModuleName = preg_replace( $sPattern, $sReplace, $value["mname"] );
                $fname = $value["id"]."_".$strModuleName;
                
                $hdlTpl->setVariable("fname",$fname);//Assigns folder name
                $hdlTpl->setVariable("id",$value["id"]);//Assigns id
                $hdlTpl->setVariable("activeflg",$intstatus);//Assigns flag
                $hdlTpl->setVariable("mname",$value["mname"]);//Assigns module name
                $hdlTpl->setVariable("pname",$arrPage[$value["page_name"]]);//Assigns Page name 
                $hdlTpl->setVariable("cname",$value["cname"]);//Assigns cources name
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