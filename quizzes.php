<?php
    /**
    * @version quizzes.php 2009-07-15 $
    * @copyright Copyright (C) peachtree. All rights reserved.
    *
    * @author Amol Divalkar
    * @Modified By 
    * @version 1.0 
    * @desc This is main file (quizzes) which is used to render quizzes page for the project
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
    $hdlTpl->loadTemplateFile("quizzes.htm",TRUE,TRUE); 
    
    $hldGlobal->fnTrackUser("Quizzes",$_SESSION["MaInId"]);
    
    $strHeader = $hldGlobal->fnGetHeader();//Fetches header template
    $strMetatag = $hldGlobal->fnMetaTag();//Fetches Metatag template
    $strFooter = $hldGlobal->fnGetFooter();//Fetches footer template
    $strLmenu = $hldGlobal->fnMenu("quizzes",$arrMpage);//Fetches left menu
    
    $hdlTpl->setVariable("metatag",$strMetatag);//Assigns Metatag 
    $hdlTpl->setVariable("header",$strHeader);//Assigns header
    $hdlTpl->setVariable("footer",$strFooter);//Assigns footer
    $hdlTpl->setVariable("menu",$strLmenu);//Assigns left menu
    $hdlTpl->setVariable("uname",ucfirst($_SESSION["UNAME"]));//Assigns user name
    
    $arrQuizzesTopic = $hldGlobal->fnFetchCourse();
    if(is_array($arrQuizzesTopic) && count($arrQuizzesTopic) > 0){
        foreach($arrQuizzesTopic as $key => $value){
            $hdlTpl->setVariable("topic",$value["cname"]);//Assigns topic
            $hdlTpl->setVariable("qid",$value["id"]);//Assigns id
            $hdlTpl->setVariable("site_name",SITE_NAME);//Assigns site name
            $intCheckQuiz = $hldGlobal->fnCheckQuizExist($value["id"],0,$_SESSION["UID"]);
            $strTake = "";
            if($intCheckQuiz){
                $arrResult = $hldGlobal->fnFetchQuizResult($value["id"],0,$_SESSION["UID"]);
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
            $hdlTpl->parse("quiz_topic");
        }
    }
    
    $hdlTpl->setVariable("space","&nbsp;");//Assigns left menu
    $hdlTpl->parse("INDEX_PAGE");
    echo $hdlTpl->get();
    
?>