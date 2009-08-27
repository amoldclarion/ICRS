<?php
    /**
    * @version news_details.php 2009-07-16 $
    * @copyright Copyright (C) ircs.net. All rights reserved.
    *
    * @author Amol Divalkar
    * @Modified By 
    * @version 1.0 
    * @desc This is main file (news_details) which is used to render news in details.
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
    $hdlTpl->loadTemplateFile("news_details.htm",TRUE,TRUE); 
    
    $hldGlobal->fnTrackUser("News details",$_SESSION["MaInId"]);
    
    $strHeader = $hldGlobal->fnGetHeader();//Fetches header template
    $strMetatag = $hldGlobal->fnMetaTag();//Fetches Metatag template
    $strFooter = $hldGlobal->fnGetFooter();//Fetches footer template
    $strLmenu = $hldGlobal->fnMenu("",$arrMpage);//Fetches left menu
    
    $hdlTpl->setVariable("metatag",$strMetatag);//Assigns Metatag 
    $hdlTpl->setVariable("header",$strHeader);//Assigns header
    $hdlTpl->setVariable("footer",$strFooter);//Assigns footer
    $hdlTpl->setVariable("menu",$strLmenu);//Assigns left menu 
    
    $arrNews = $hldGlobal->fnFetchNews(REC_PER_PAGE);
    
    if(is_array($arrNews) && count($arrNews) > 0){
        foreach($arrNews as $key => $value){
            
            $hdlTpl->setVariable("date",$value["dcreated"]);//Assigns date
            $hdlTpl->setVariable("title",substr($value["newstitle"],0,20)."...");//Assigns title
            $hdlTpl->setVariable("news_desc",substr(strip_tags($value["content"]),0,100)."...");//Assigns description
            $hdlTpl->setVariable("id",$value["id"]);//Assigns id
            //$strurl = ereg_replace(" ","_",$value["newstitle"]);
            $strurl = preg_replace('/[^a-z0-9]/i', '_', $value["newstitle"]);
            $hdlTpl->setVariable("site_name",SITE_NAME);
            $hdlTpl->setVariable("news_url",$strurl);//Assigns url
            $hdlTpl->parse("NEWS_LEFT");
        }
    }
    
    $arrRecordSet = $hldGlobal->fnFetchNewsDetails($_GET["id"]);
    if(is_array($arrRecordSet) && count($arrRecordSet) > 0){
        $hdlTpl->setVariable("date",$arrRecordSet[0]["datecreated"]);
        $hdlTpl->setVariable("title",$arrRecordSet[0]["newstitle"]);
        $hdlTpl->setVariable("news_desc",$arrRecordSet[0]["content"]);
        $hdlTpl->parse("NEWS");
    }
    $hdlTpl->setVariable("site_name",SITE_NAME);
    $hdlTpl->parse("INNER_PAGE");
    $hdlTpl->parse("INDEX_PAGE");
    echo $hdlTpl->get();
    
?>