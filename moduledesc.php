<?php
    /**
    * @version moduledesc.php 2009-08-03 $
    * @copyright Copyright (C) peachtree. All rights reserved.
    *
    * @author Amol Divalkar
    * @Modified By 
    * @version 1.0 
    * @desc This is main file (moduledesc) which is used to render moduledesc page for the project
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
    $hdlTpl->loadTemplateFile("moduledesc.htm",TRUE,TRUE); 
    
    $hldGlobal->fnTrackUser("Module Description",$_SESSION["MaInId"]);
    
    $strHeader = $hldGlobal->fnGetHeader();//Fetches header template
    $strMetatag = $hldGlobal->fnMetaTag();//Fetches Metatag template
    $strFooter = $hldGlobal->fnGetFooter();//Fetches footer template
    $strLmenu = $hldGlobal->fnMenu("curriculum",$arrMpage);//Fetches left menu
    
    $hdlTpl->setVariable("metatag",$strMetatag);//Assigns Metatag 
    $hdlTpl->setVariable("header",$strHeader);//Assigns header
    $hdlTpl->setVariable("footer",$strFooter);//Assigns footer
    $hdlTpl->setVariable("menu",$strLmenu);//Assigns left menu 
    
    
    $strSearch = $hldGlobal->fnGetSearch($_POST["search1"],"Curriculum","curriculum");
    $hdlTpl->setVariable("search",$strSearch);//Assigns search
    
    $strSearchTxt = "";
    $intCid = 0;
    if($_POST["search"] == "Search"){
        $hdlTpl->setVariable("search1",$_POST["search1"]);//Assigns search text
        $strSearchTxt = $_POST["search1"];
        $intCid = $_POST["cid"];
    }
    
    $arrModule = $hldGlobal->fnGetModuleDesc($_GET["mid"]);
    
    if(is_array($arrModule) && count($arrModule) > 0){
        if($arrModule[0]["img_path"]){ 
            $hdlTpl->setVariable("site_name",SITE_NAME);//Assigns site name
            $hdlTpl->setVariable("img1",$arrModule[0]["img_path"]);//Assigns module image
            $hdlTpl->parse("module_image");
        }
        $hdlTpl->setVariable("cource_name",$arrModule[0]["cname"]);//Assign course name
        $hdlTpl->setVariable("mname",$arrModule[0]["mname"]);//Assign module name
        $hdlTpl->setVariable("desc",strip_tags($arrModule[0]["mdescription"]));//Assign description
        $hdlTpl->parse("module");
    }
    $hdlTpl->setVariable("site_name",SITE_NAME);//Assigns site name
    $hdlTpl->setVariable("space","&nbsp;");//Assigns left menu
    $hdlTpl->parse("INDEX_PAGE");
    echo $hdlTpl->get();
    
?>