<?php
    /**
    * @version addquizzes.php 2009-07-10 $
    * @copyright Copyright (C) icrs.com. All rights reserved.
    *
    * @author Amol Divalkar
    * @Modified By 
    * @version 1.0 
    * @desc This is main file (addquizzes) which is used to render addquizzes page for the project
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
    $hdlTpl->loadTemplateFile("addquizzes.htm",TRUE,TRUE); 
      
    $strHeader = $hldGlobal->fnGetHeader();//Fetches header template
    $strMetatag = $hldGlobal->fnGetMetatag();//Fetches Metatag template
    $strFooter = $hldGlobal->fnGetFooter();//Fetches footer template
    $strLeftMenu = $hldGlobal->fnGetLMenu();//Fetches left menu template
    $strCourse = $hldGlobal->fnGetCource($_POST["course_id"]);
    //$strOption = $hldGlobal->fnGetModule($_POST["module_id"]);//Fetches options

    $hdlTpl->setVariable("metatag",$strMetatag);//Assigns Metatag
    $hdlTpl->setVariable("header",$strHeader);//Assigns Header 
    $hdlTpl->setVariable("footer",$strFooter);//Assigns footer
    $hdlTpl->setVariable("leftmenu",$strLeftMenu);//Assigns left menu
    $intError = 1;
    if($_POST["submit"] == "Add quiz"){
        $strErrormessage = "";
        
        if(trim($_POST["course_id"]) == "--"){
            $strErrormessage .= "Please select course name.<br>";
            $intError = 0;
        }
        /*if(trim($_POST["module_id"]) == "--"){
            $strErrormessage .= "Please select module name.<br>";
            $intError = 0;
        }*/
        if(trim($_POST["quiztitle"]) == ""){
            $strErrormessage .= "Please enter quiz title.<br>";
            $intError = 0;
        }        
        /*if(trim($_POST["hmany"]) == ""){
            $strErrormessage .= "Please enter how many answer you want for this question?.<br>";
            $intError = 0;
        }
        if(trim($_POST["hmany"]) != ""){
            if(!preg_match("/^[0-9]*$/",trim($_POST["hmany"]))){
                $strErrormessage .= "Please enter only numeric value.<br>";
                $intError = 0;
            } 
            if(trim($_POST["hmany"]) <= 0){
                $strErrormessage .= "Entered value should be greater then zero.<br>";
                $intError = 0;
            }
            if(trim($_POST["hmany"]) > 10){
                $strErrormessage .= "Please enter value less then or equal to ten.<br>";
                $intError = 0;
            }
        }
        
        if($hldGlobal->fnCheckQuiztitle($_POST["course_id"],trim($_POST["quiztitle"]))){
            $strErrormessage .= "Quiz title already added.<br>";
            $intError = 0;                        
        }*/
        
        if($intError){
            foreach($_POST as $key => $value){
                $_POST[$key] = mysql_escape_string(trim($value));
            }
            
            /*$sqlTitle = "SELECT * FROM temp_quiz_title";
            $rs = mysql_query($sqlTitle);
            $int = mysql_num_rows($rs);
            
            if($int){
                for($i=0;$i<$int;$i++){
                    $rw = mysql_fetch_array($rs);    
                    $sqlTitle = "INSERT INTO tblQuizTitle(id,course_id,quiz_title,isactive,dtcreated) VALUES('','".$rw["course_id"]."','".$rw["quiz_title"]."','".$rw["isactive"]."','".$rw["dtcreated"]."')";
                    mysql_query($sqlTitle) or die(mysql_error());
                    $intLTitle = mysql_insert_id();
                    
                    $sqlQuestion = "SELECT * FROM temp_quiz_question WHERE quiz_title_id=".$rw["id"];
                    $rsQuestion = mysql_query($sqlQuestion);
                    $intQuestion = mysql_num_rows($rsQuestion);
                    
                    if($intQuestion){
                        for($j=0;$j<$intQuestion;$j++){
                            $rwQuestion = mysql_fetch_array($rsQuestion);
                            
                            $insQuestion = "INSERT INTO tblQuizQuestion(id,quiz_title_id,module_id,question,option_id,isactive,dtcreated) VALUES('','".$intLTitle."','".$rwQuestion["module_id"]."','".$rwQuestion["question"]."','".$rwQuestion["option_id"]."','".$rwQuestion["isactive"]."','".$rwQuestion["dtcreated"]."')";
                            mysql_query($insQuestion);
                            $intLQid = mysql_insert_id();
                            
                            $sqlOption = "SELECT * FROM temp_quiz_options WHERE qid=".$rwQuestion["id"];
                            $rsOption = mysql_query($sqlOption);
                            $intOption = mysql_num_rows($rsOption);
                            
                            if($intOption){
                                for($k=0;$k<$intOption;$k++){
                                    $rwOptions = mysql_fetch_array($rsOption);
                                    
                                    $insOption = "INSERT INTO tblQuizOption(id,qid,options,isactive,dtcreated) VALUES('','".$intLQid."','".$rwOptions["options"]."','".$rwOptions["isactive"]."','".$rwOptions["dtcreated"]."')";
                                    mysql_query($insQuestion);
                                }
                            }
                            
                        }
                    }
                    
                }
            } else {
                 $sqlTitle = "INSERT INTO tblQuizTitle(id,course_id,quiz_title,isactive,dtcreated) VALUES('','".$_POST["course_id"]."','".$_POST["quiz_title"]."','1','".date("Y-m-d H:i:s")."')";
                 mysql_query($sqlTitle) or die(mysql_error());
            }
            $sql = "SELECT count(id) as cnt FROM temp_quiz_question";*/
            
            /*$sqlQuestion = "INSERT INTO tblQuizQuestion(id,quiz_title_id,module_id,question,option_id,isactive,dtcreated) SELECT * FROM temp_quiz_question";
            mysql_query($sqlQuestion) or die(mysql_error());
            
            $sqlOption = "INSERT INTO tblQuizOption(id,qid,options,isactive,dtcreated) SELECT * FROM temp_quiz_options";
            mysql_query($sqlOption) or die(mysql_error());*/
            if($_SESSION["InTlAsTiD"]){
                $sql = "UPDATE tblQuizTitle SET to_show='1' WHERE id=".$_SESSION["InTlAsTiD"]; 
            } else {
                $sql = "INSERT INTO tblQuizTitle(id,course_id,quiz_title,to_show,isactive,dtcreated) VALUES('','".$_POST["course_id"]."','".$_POST["quiztitle"]."','1','1','".date("Y-m-d H:i:s")."')"; 
            }
            mysql_query($sql);
            
            /*mysql_query("TRUNCATE TABLE `temp_quiz_question`");
            mysql_query("TRUNCATE TABLE `temp_quiz_title`");
            mysql_query("TRUNCATE TABLE `temp_quiz_options`");*/
            
            unset($_SESSION["InTlAsTiD"]);
            
            header("Location:message.php?mess=21");
        }
    }
    $strSel = '';
    $strSel .= '<select name="module_id" id="module_id">';
    $strSel .= '<option value="--">--Select module name--</option>';
    if(!$intError){
        $strModuleOption =  $hldGlobal->fnGetModuleOption($_POST["course_id"],$_POST["module_id"]);
        $strSel .= $strModuleOption;
        $strSel .= '</select>';
        $hdlTpl->setVariable("selmodule",$strSel);//Assigns module drop down
        $hdlTpl->setVariable("error_message",$strErrormessage);//Assigns error message
    } else {
        $strSel .= '</select>';
        $hdlTpl->setVariable("selmodule",$strSel);//Assigns module drop down
    }
    if(!$intError){
        mysql_query("TRUNCATE TABLE `temp_quiz_question`");
        mysql_query("TRUNCATE TABLE `temp_quiz_title`");
        mysql_query("TRUNCATE TABLE `temp_quiz_options`");
        
        unset($_SESSION["InTlAsTiD"]);
    }
    unset($_SESSION["InTlAsTiD"]);
    $hdlTpl->setVariable("coptval",$strCourse);//Assigns option
    foreach($_POST as $key => $value){
         $hdlTpl->setVariable($key,$value);//Assigns values
    }
     
    $hdlTpl->parse("ADMIN_INNER_PAGE");
    $hdlTpl->parse("INNER_PAGE");
    echo $hdlTpl->get();
    
?>