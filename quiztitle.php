<?php
    /**
    * @version quiztitle.php 2009-08-13 $
    * @copyright Copyright (C) peachtree. All rights reserved.
    *
    * @author Amol Divalkar
    * @Modified By 
    * @version 1.0 
    * @desc This is main file (quiztitle) which is used to render quiz title for the cource
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
    $hdlTpl->loadTemplateFile("quiztitle.htm",TRUE,TRUE); 
    
    $strHeader = $hldGlobal->fnGetHeader();//Fetches header template
    $strMetatag = $hldGlobal->fnMetaTag();//Fetches Metatag template
    $strFooter = $hldGlobal->fnGetFooter();//Fetches footer template
    $strLmenu = $hldGlobal->fnMenu("quizzes",$arrMpage);//Fetches left menu
    
    $hdlTpl->setVariable("metatag",$strMetatag);//Assigns Metatag 
    $hdlTpl->setVariable("header",$strHeader);//Assigns header
    $hdlTpl->setVariable("footer",$strFooter);//Assigns footer
    $hdlTpl->setVariable("menu",$strLmenu);//Assigns left menu
    
    $hldGlobal->fnTrackUser("Quiz title",$_SESSION["MaInId"],$_GET["cid"]); 
    
    $arrCourseName = $hldGlobal->fnFetchCourse($_GET["cid"]);
    $hdlTpl->setVariable("cname",$arrCourseName[0]["cname"]);//Assigns course name
    
    $arrQuizQuestion = $hldGlobal->fnGetQuizTitle($_GET["cid"]);
    
    if(is_array($arrQuizQuestion) && count($arrQuizQuestion) > 0){
        $intCnt = 1;
        foreach($arrQuizQuestion as $key => $value){
            $hdlTpl->setVariable("qid",$value["id"]);//Assign qid
            $hdlTpl->setVariable("cid",$_GET["cid"]);//Assign cid
            $hdlTpl->setVariable("srno",$intCnt);//Assigns serial number
            $hdlTpl->setVariable("quiz_title",$value["quiz_title"]);//Assigns quiz title
            $intCheckQuiz = $hldGlobal->fnCheckQuizExist(0,$value["id"],$_SESSION["UID"]);
            
            $strTake = "";
            if($intCheckQuiz){
                $arrResult = $hldGlobal->fnFetchQuizResult(0,$value["id"],$_SESSION["UID"]);
                
                $arrAvg = explode(",",$arrResult[0]["percent"]);
                $arrDate = explode(",",$arrResult[0]["takendate"]);
                $intPercent = MAX($arrAvg);
                $arrKey  = array_keys($arrAvg, $intPercent);
                
                $strTake .= "<table><tr><td>";
                $strTake .= number_format($intPercent)."%";
                $strTake .= "</td></tr><tr><td>";
                $strTake .= $arrDate[$arrKey[0]];
                $strTake .= "</td></tr></table>";
                $hdlTpl->setVariable("quiz_take","Re-take!");
            } else {
                $hdlTpl->setVariable("quiz_take","Take now!");
            }
            $hdlTpl->setVariable("result",$strTake);//Assigns Take now
            $hdlTpl->setVariable("site_name",SITE_NAME);//Assigns site name
            $hdlTpl->parse("quiz_question");
            $intCnt++;
        }
    } else {
        $hdlTpl->setVariable("space1","&nbsp;");//Assigns space
        $hdlTpl->parse("no_question");
    }
    
    $hdlTpl->setVariable("space","&nbsp;");//Assigns left menu
    $hdlTpl->parse("INDEX_PAGE");
    echo $hdlTpl->get();
    
?>