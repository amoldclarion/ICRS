<?php
    /**
    * @version showresult.php 2009-07-22 $
    * @copyright Copyright (C) peachtree. All rights reserved.
    *
    * @author Amol Divalkar
    * @Modified By 
    * @version 1.0 
    * @desc This is main file (showresult) which is used to render showresult page for the project
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
    $hdlTpl->loadTemplateFile("showresult.htm",TRUE,TRUE); 
    
    $hldGlobal->fnTrackUser("Show Result",$_SESSION["MaInId"]);
    
    $strHeader = $hldGlobal->fnGetHeader();//Fetches header template
    $strMetatag = $hldGlobal->fnMetaTag();//Fetches Metatag template
    $strFooter = $hldGlobal->fnGetFooter();//Fetches footer template
    $strLmenu = $hldGlobal->fnMenu("quizzes",$arrMpage);//Fetches left menu
    
    $hdlTpl->setVariable("metatag",$strMetatag);//Assigns Metatag 
    $hdlTpl->setVariable("header",$strHeader);//Assigns header
    $hdlTpl->setVariable("footer",$strFooter);//Assigns footer
    $hdlTpl->setVariable("menu",$strLmenu);//Assigns left menu
    
    $arrQuizAnswer = $hldGlobal->fnGetResult($_GET["cid"],$_SESSION["UID"]);
    
    $arrCorrect = $hldGlobal->fnGetQuizResult($_GET["cid"],$_SESSION["UID"],$_SESSION["StRuNiQuE"]);
    
    $intCid = $hldGlobal->fnGetCourseId($_GET["cid"]);
    
    $arrCourseName = $hldGlobal->fnFetchCourse($intCid);
    
    $hdlTpl->setVariable("cname",$arrCourseName[0]["cname"]);//Assigns course name
    
    $hdlTpl->setVariable("outof",$arrCorrect[0]["correct"]);//Assigns out of
    $hdlTpl->setVariable("howmany",$hldGlobal->fnCountQuestion($_GET["qid"],$_GET["cid"]));//Assigns howmany
    $hdlTpl->parse("quiz_question");
    
    $arrList = $hldGlobal->fnListQuestion($arrCorrect[0]["question"],$_GET["cid"]);
    
    if(is_array($arrList) && count($arrList) > 0){
        $intCnt = 1;
        foreach($arrList as $key => $value){
            $hdlTpl->setVariable("srno",$intCnt);//Assigns sr no
            $hdlTpl->setVariable("question",$value["question"]);//Assigns question
            $hdlTpl->setVariable("mid",$value["module_id"]);//Assigns module id
            $hdlTpl->setVariable("site_name",SITE_NAME);//Assigns site name
            $hdlTpl->parse("wrong_quiz_question");
            $intCnt ++;
        }
    }
    
    $hdlTpl->setVariable("space","&nbsp;");//Assigns left menu
    $hdlTpl->parse("INDEX_PAGE");
    echo $hdlTpl->get();
    
?>