<?php
    /**
    * @version innovation.php 2009-07-15 $
    * @copyright Copyright (C) peachtree. All rights reserved.
    *
    * @author Amol Divalkar
    * @Modified By 
    * @version 1.0 
    * @desc This is main file (innovation) which is used to render innovation page for the project
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
    $hdlTpl->loadTemplateFile("innovation.htm",TRUE,TRUE); 
    
    $hldGlobal->fnTrackUser("Innovation",$_SESSION["MaInId"]);
    
    $strHeader = $hldGlobal->fnGetHeader();//Fetches header template
    $strMetatag = $hldGlobal->fnMetaTag();//Fetches Metatag template
    $strFooter = $hldGlobal->fnGetFooter();//Fetches footer template
    $strLmenu = $hldGlobal->fnMenu("innovations",$arrMpage);//Fetches left menu
    
    $hdlTpl->setVariable("metatag",$strMetatag);//Assigns Metatag 
    $hdlTpl->setVariable("header",$strHeader);//Assigns header
    $hdlTpl->setVariable("footer",$strFooter);//Assigns footer
    $hdlTpl->setVariable("menu",$strLmenu);//Assigns left menu
    
    $strSearch = $hldGlobal->fnGetSearch($_POST["search1"],"Innovation","innovation","innovation");
    $hdlTpl->setVariable("search",$strSearch);//Assigns search
    
    $strSearchTxt = "";
    $intCid = 0;
    if($_POST["search"] == "Search"){
        $hdlTpl->setVariable("search1",$_POST["search1"]);//Assigns search text
        $strSearchTxt = $_POST["search1"];
        $intCid = $_POST["cid"];
    }
    
    $strMidPage = $hldGlobal->fnGetMiddlePage(0,$intCid,$strSearchTxt,"innovation");
    $hdlTpl->setVariable("middle_page",$strMidPage);//Assigns mid page
    
    /*$arrPages = $hldGlobal->fnGetPages("innovation");  
    
    if(is_array($arrPages) && count($arrPages) > 0){
        foreach($arrPages as $key => $value){
            if(trim($value["image_path"]) != ""){
               $hdlTpl->setVariable("img1",$value["image_path"]);
                $hdlTpl->setVariable("site_name",SITE_NAME);
                $hdlTpl->parse("web_technique_image");
            } 
            $hdlTpl->setVariable("page_title",$value["page_title"]);
            $hdlTpl->setVariable("bywhome",$value["by_whom"]);
            $hdlTpl->setVariable("shot_desc",$value["shot_description"]); 
            $hdlTpl->parse("web_technique");
        }
    }
    
    if(is_array($arrCPage) && count($arrCPage) > 0){
        $strOption = "";
        foreach($arrCPage as $key => $value){
            $strOption .= "<option '".$key."'>".$value."</option>";
        }
        $hdlTpl->setVariable("cources",$strOption); 
    }
    
    if(is_array($arrPPage) && count($arrPPage) > 0){
        $strOption = "";
        foreach($arrPPage as $key => $value){
            $strOption .= "<option '".$key."'>".$value."</option>";
        }
        $hdlTpl->setVariable("procedural",$strOption); 
    }*/
    
    $hdlTpl->setVariable("space","&nbsp;");//Assigns left menu
    $hdlTpl->parse("INDEX_PAGE");
    echo $hdlTpl->get();
    
?>