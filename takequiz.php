<?php
    /**
    * @version takequiz.php 2009-07-15 $
    * @copyright Copyright (C) peachtree. All rights reserved.
    *
    * @author Amol Divalkar
    * @Modified By 
    * @version 1.0 
    * @desc This is main file (takequiz) which is used to render takequiz page for the project
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
    $hdlTpl->loadTemplateFile("takequiz.htm",TRUE,TRUE); 
    
    $strHeader = $hldGlobal->fnGetHeader();//Fetches header template
    $strMetatag = $hldGlobal->fnMetaTag();//Fetches Metatag template
    $strFooter = $hldGlobal->fnGetFooter();//Fetches footer template
    $strLmenu = $hldGlobal->fnMenu("quizzes",$arrMpage);//Fetches left menu
    
    $hdlTpl->setVariable("metatag",$strMetatag);//Assigns Metatag 
    $hdlTpl->setVariable("header",$strHeader);//Assigns header
    $hdlTpl->setVariable("footer",$strFooter);//Assigns footer
    $hdlTpl->setVariable("menu",$strLmenu);//Assigns left menu
    
    if($_POST["submit_ans"] == "Submit Answer"){
        if(is_array($_POST["opt"]) && count($_POST["opt"]) > 0){
            $strUnique = $hldGlobal->generatePassword();
            foreach($_POST["opt"] as $key1 => $value1){
                $sqlIns = "INSERT INTO tblQuizAnswer(id,qid,userid,option_id,module_id,quiz_title_id,course_id,quiz_date,attempted) VALUES('','".$key1."','".$_SESSION["UID"]."','".$value1."','".$_POST["mid"]."','".$_POST["quiz_title_id"]."','".$_GET["cid"]."','".date("Y-m-d")."','".$strUnique."')";
                mysql_query($sqlIns);
            }
            $_SESSION["StRuNiQuE"] = $strUnique;
            $hldGlobal->fnStoreCorrectPercent($_GET["cid"],$_GET["qid"],$_POST["mid"],$_SESSION["UID"],$strUnique); 
            $redirect = SITE_NAME."showresult/".$_GET["qid"]."/".$_POST["mid"].".htm";
            header("Location:$redirect");
        }
    }
    
    $hldGlobal->fnTrackUser("Take Quiz",$_SESSION["MaInId"],$_GET["cid"],$_GET["qid"]); 
    
    $arrCourseName = $hldGlobal->fnFetchCourse($_GET["cid"]);
    $hdlTpl->setVariable("cname",$arrCourseName[0]["cname"]);//Assigns course name
    
    $arrQuizquestion = $hldGlobal->fnGetQuizQuestion($_GET["qid"]);
    
    if(is_array($arrQuizquestion) && count($arrQuizquestion) > 0){
        $intCnt = 1;
        foreach($arrQuizquestion as $key=>$value){
            $hdlTpl->setVariable("mid",$value["module_id"]);//Assign mid
            $hdlTpl->setVariable("quiz_title_id",$value["quiz_title_id"]);//Assign quiz title id
            $arrOption = $hldGlobal->fnFetchOption($value["id"]);
            $intCnt1 = count($arrOption);
            $strTab = "";
            if($intCnt1){
                $strTab .= '<table width="100%"  border="0" cellspacing="2" cellpadding="1">';
                for($c=0;$c<$intCnt1;)
                {
                    $temp = $c;
                    $strTab .='<tr>';
                    while($c < ($temp+2))
                    {   
                        $strTab .='<td width="50%" valign="top" align="left">';
                        $strTab .= '<input type="radio" name="opt['.$value["id"].']" value="'.$arrOption[$c]["id"].'" />'.$arrOption[$c]["options"];
                        $strTab .='</td>';                                
                        $c++;
                        
                        if($c >= $intCnt1 && ($c % 2))
                        {
                            $strTab .="<td>&nbsp;</td>";
                            break;
                        }
                    }
                    $strTab .='</tr>';
                }
                $strTab .='</table>';
            }
            $hdlTpl->setVariable("option",$strTab); //Assign option
            $hdlTpl->setVariable("srno",$intCnt);//Assigns serial number
            $hdlTpl->setVariable("question",$value["question"]);//Assigns quiz question
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