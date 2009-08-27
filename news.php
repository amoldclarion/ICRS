<?php
    /**
    * @version news.php 2009-07-16 $
    * @copyright Copyright (C) ircs.net. All rights reserved.
    *
    * @author Amol Divalkar
    * @Modified By 
    * @version 1.0 
    * @desc This is main file (new) which is used to render show all the news on one page.
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
    $hdlTpl->loadTemplateFile("news.htm",TRUE,TRUE); 
    
    $hldGlobal->fnTrackUser("News",$_SESSION["MaInId"]);
    
    $strHeader = $hldGlobal->fnGetHeader();//Fetches header template
    $strMetatag = $hldGlobal->fnMetaTag();//Fetches Metatag template
    $strFooter = $hldGlobal->fnGetFooter();//Fetches footer template
    $strLmenu = $hldGlobal->fnMenu("",$arrMpage);//Fetches left menu
    
    $hdlTpl->setVariable("metatag",$strMetatag);//Assigns Metatag 
    $hdlTpl->setVariable("header",$strHeader);//Assigns header
    $hdlTpl->setVariable("footer",$strFooter);//Assigns footer
    $hdlTpl->setVariable("menu",$strLmenu);//Assigns left menu  
     
    $arrRecordSet = $hldGlobal->fnFetchNewsLeft();
    
    if(is_array($arrRecordSet) && count($arrRecordSet) > 0){
        for($i=0;$i<count($arrRecordSet);$i++){
            $hdlTpl->setVariable("site_name",SITE_NAME);
            $hdlTpl->setVariable("left_link",$arrRecordSet[$i]["datecreate"]);//Assigns left link
            $arrNewsResult = $hldGlobal->fnGetLeftMonth($arrRecordSet[$i]["datecreate"]);
            if(is_array($arrNewsResult) && count($arrNewsResult)){
                $strMonth = "";
                for($j=0;$j<count($arrNewsResult);$j++){
                    $hdlTpl->setVariable("site_name",SITE_NAME);//Assigns left link
                    $hdlTpl->setVariable("intMonthId",$arrNewsResult[$j]["intMonthId"]);//Assigns left link
                    $hdlTpl->setVariable("intYear",$arrNewsResult[$j]["intYear"]);//Assigns left link
                    $hdlTpl->setVariable("mon",$arrNewsResult[$j]["mon"]);//Assigns left link
                    $hdlTpl->setVariable("totcnt",$arrNewsResult[$j]["totcnt"]);//Assigns left link
                    $hdlTpl->parse("MON_LINK"); 
                    
                }
            }
            $hdlTpl->parse("YEAR_LINK"); 
        }
    }
    
    if($_GET["mon"] && $_GET["year"]){
        $arrRecordSet = $hldGlobal->fnFetchNewsMonYear($_GET["mon"],$_GET["year"]);
    } else {
        $arrRecordSet = $hldGlobal->fnFetchNews(0,$_GET["char"]);
    }
    
    $arrNewsSet = $hldGlobal->fnGetData($arrRecordSet,$_GET["char"],REC_PER_PAGE);
    
    if(is_array($arrNewsSet) && count($arrNewsSet) > 0){
        for($i=0;$i<(count($arrNewsSet)-1);$i++){
            $arrKeys = array_keys($arrNewsSet);
            $strNews = stripslashes(substr( strip_tags($arrNewsSet[$arrKeys[$i]]["content"]),0,200))."...";
            $strnewstitle = stripslashes($arrNewsSet[$i]["newstitle"]);
            $strurl = ereg_replace(" ","_",$strnewstitle);
            $hdlTpl->setVariable("date",$arrNewsSet[$arrKeys[$i]]["datecreated"]);//Assigns date
            $hdlTpl->setVariable("title",$strnewstitle);//Assigns news title
            $hdlTpl->setVariable("news_url",$strurl);//Assigns news title
            $hdlTpl->setVariable("site_url",SITE_NAME);//Assigns news title
            $hdlTpl->setVariable("news_desc",$strNews);//Assigns news description
            $hdlTpl->setVariable("id",$arrNewsSet[$arrKeys[$i]]["id"]);//Assigns id
            $hdlTpl->setVariable("paging",$arrNewsSet["paging"]);//Assigns news description
            $hdlTpl->parse("NEWS");
        }   
    } else {
        $hdlTpl->setVariable("no_news","No news added");//Assigns no news
        $hdlTpl->parse("NO_NEWS");
    }
    $hdlTpl->setVariable("site_name",SITE_NAME);//Assigns news title
    $hdlTpl->setVariable("space","&nbsp;");
    $hdlTpl->parse("INNER_PAGE");
    $hdlTpl->parse("INDEX_PAGE");
    echo $hdlTpl->get();
    
?>