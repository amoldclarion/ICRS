<?php
    /**
    * @version editquizes.php 2009-07-10 $
    * @copyright Copyright (C) icrs.com. All rights reserved.
    *
    * @author Amol Divalkar
    * @Modified By 
    * @version 1.0 
    * @desc This is main file (editquizes) which is used to render editquizes page for the project
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
    $hdlTpl->loadTemplateFile("editquizes.htm",TRUE,TRUE); 
    $strErrormessage = "";
    $intError = 1;
    if($_POST["submit"] == "Save"){
        
        if($_POST["quiztitle"] == ""){
            $strErrormessage = "Quiz title should not be empty<br>";
            $intError = 0;
        }
        
        $hldGlobal->fnCheckQuiztitle($_POST["cid"],trim($_POST["quiztitle"]));
        
        
        if($intError){
            foreach($_POST as $key => $value){
                $_POST[$key] = mysql_escape_string(trim($value));
            }
            
            $update = "UPDATE tblQuizTitle SET quiz_title='".$_POST["quiztitle"]."' WHERE id=".$_GET["id"];
            mysql_query($update);
            
            $sqlQuestion = "INSERT INTO tblQuizQuestion(id,quiz_title_id,module_id,question,option_id,isactive,dtcreated) SELECT * FROM temp_quiz_question ON DUPLICATE KEY UPDATE quiz_title_id=".$_GET["id"];
            mysql_query($sqlQuestion) or die(mysql_error());
            
            $sql = "SELECT id FROM tblQuizQuestion WHERE quiz_title_id=".$_GET["id"];
            $rs = mysql_query($sql);
            $intCnt = mysql_num_rows($rs);
            if($intCnt){
                $rw = mysql_fetch_array($rs);
                $intQid = $rw["id"];
                
                $sqlOption = "INSERT INTO tblQuizOption(id,qid,options,isactive,dtcreated) SELECT * FROM temp_quiz_options ON DUPLICATE KEY UPDATE qid=".$intQid;
                mysql_query($sqlOption) or die(mysql_error());    
            }
            
                
            mysql_query("TRUNCATE TABLE `temp_quiz_question`");
            mysql_query("TRUNCATE TABLE `temp_quiz_title`");
            mysql_query("TRUNCATE TABLE `temp_quiz_options`");
                
            unset($_SESSION["InTlAsTiD"]);
                
            $return = $_GET["return"];
            header("Location:message.php?mess=9&return=$return");
        } 
    }
    if(!$intError){
        $hdlTpl->setVariable("error_message",$strErrormessage);//Assigns values
    }
    
    $arrQuizzes = $hldGlobal->fnFetchQuizzes($_GET["id"]);
    
    $strHeader = $hldGlobal->fnGetHeader();//Fetches header template
    $strMetatag = $hldGlobal->fnGetMetatag();//Fetches Metatag template
    $strFooter = $hldGlobal->fnGetFooter($arrQuizzes[0]["course_id"],$arrQuizzes[0]["id"]);//Fetches footer template
    $strLeftMenu = $hldGlobal->fnGetLMenu();//Fetches left menu template
    
    $hdlTpl->setVariable("metatag",$strMetatag);//Assigns Metatag
    $hdlTpl->setVariable("header",$strHeader);//Assigns Header 
    $hdlTpl->setVariable("footer",$strFooter);//Assigns footer
    $hdlTpl->setVariable("leftmenu",$strLeftMenu);//Assigns left menu
    $intError = 1;
    
    //print_r($arrQuizzes);
    if(is_array($arrQuizzes) && count($arrQuizzes) > 0){
        //$arrMname = $hldGlobal->fnFetchModule($arrQuizzes[0]["course_id"]);
        $arrMname = $hldGlobal->fnFetchCources($arrQuizzes[0]["course_id"]);
        $mcname = $arrMname[0]["cname"];
        $hdlTpl->setVariable("cname",$mcname);//Assigns module cource name
        $hdlTpl->setVariable("cid",$arrQuizzes[0]["course_id"]); //Assign course id
        $hdlTpl->setVariable("quiztitle",$arrQuizzes[0]["quiz_title"]);//Assigns quiz title
        $hdlTpl->setVariable("mid",$arrQuizzes[0]["module_id"]);//Assigns module id
        $hdlTpl->setVariable("id",$arrQuizzes[0]["id"]);//Assigns id
        $hdlTpl->setVariable("return",$_GET["return"]);//Assigns return
        
        $strTab = $hldGlobal->fnFetchQuestionQuiz($arrQuizzes[0]["id"],0);
        
        $hdlTpl->setVariable("question",$strTab);//Assigns question
        
    }
    if($intError) {
        $arrDuplicate = $hldGlobal->fnInsertDuplicate($arrQuizzes[0]["id"]);
    }
    unset($_SESSION["InTlAsTiD"]);
    $hdlTpl->parse("ADMIN_INNER_PAGE");
    
    $hdlTpl->parse("INNER_PAGE");
    echo $hdlTpl->get();
    
?>