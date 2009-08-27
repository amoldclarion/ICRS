<?php
    /**
    * @version addquestion.php 2009-08-05 $
    * @copyright Copyright (C) icrs.com. All rights reserved.
    *
    * @author Amol Divalkar
    * @Modified By 
    * @version 1.0 
    * @desc This is main file (addquestion) which is used to add the question for particular quiz
    */
    
    //Includes config file
    include("../includes/config.inc.php");
    //Includes global class file
    include(DIR_ADMIN_CLASSES."/clsGlobal.php");

    //Checks if session is set for admin login or not
    if($_SESSION["UsErId"] == "" && $_SESSION["UnAmE"] == ""){
        header("Location:index.php");
    }
    $hldGlobal = new clsGlobal($hdlDb);
    
    if($_GET["eid"]){ // This will edit the question
         $intCheck = $hldGlobal->fnCheckQuestionQuiz($_GET["mid"],$_POST["question"],$_GET["qqid"],1,$_GET["qtid"]);
         if(!$intCheck){
             $hldGlobal->fnInsQuestionQuiz(0,0,$_POST["question"],$_GET["qqid"],1);
         } else {
             $strErrormessage =  'ErrorMess~Question already added';
             echo $strErrormessage."~2~".$_GET["qqid"];
             exit;
         }
         if($_SESSION["InTlAsTiD"]){
             $qqqid = $_SESSION["InTlAsTiD"];
         } else {
             $qqqid = $_GET["qtid"];
         }
         $strTab = $hldGlobal->fnFetchQuestionQuiz($qqqid,$_GET["eid"]);
         $strResposeText = "EditQuestion~".$strTab."~2~".$_GET["qqid"];
         echo $strResposeText;
    } /*else if($_POST["editid"]){
            $strErrormessage = '';
            if($_POST["module_id"] == "--"){
                echo  "Please select module name<br>";
                exit;
            }
            if($_POST["question"] == ""){
                echo "Please enter question<br>";
                exit;
            }
            if($_POST["module_id"] != "--" && $_POST["question"] != ""){
                $intCnt = $hldGlobal->fnCheckQuestionQuiz($_POST["module_id"],$_POST["question"],0,1,$_POST["editid"]);
                if($intCnt){
                    echo "Question already added<br>";
                    exit;
                } else {
                   $hldGlobal->fnInsQuestionQuiz($_POST["editid"],$_POST["module_id"],$_POST["question"]);
                    $strTab = $hldGlobal->fnFetchQuestionQuiz($_POST["editid"]);
                    $strResposeText = "AddQuestion~".$strTab;
                    echo $strResposeText; 
                    exit;
                }
            }
    }*/ else { //This will add the question 
    
        
        if(!$_SESSION["InTlAsTiD"] && ! $_POST["editid"]){
            $intLastId = $hldGlobal->fnInsTemp($_POST["cid"],$_POST["qtitle"]);
        } else if($_POST["editid"]) {
            $intLastId = $_POST["editid"];
        } else {
            $intLastId = $_SESSION["InTlAsTiD"];
        }
        
        $strErrormessage = '';
        if($_POST["module_id"] == "--"){
            echo "Please select module name<br>";
            exit;
        }
        
        if($_POST["question"] == ""){
            echo "Please enter question<br>";
            exit;
        }
        
        if($_POST["module_id"] != "--" || $_POST["question"] != ""){
            $intCnt = $hldGlobal->fnCheckQuestionQuiz($_POST["module_id"],$_POST["question"],0,0,$intLastId);
            if($intCnt){
                echo "Question already added<br>";
                exit;
            } else {
                $hldGlobal->fnInsQuestionQuiz($intLastId,$_POST["module_id"],$_POST["question"]);
                $strTab = $hldGlobal->fnFetchQuestionQuiz($intLastId);
                $strResposeText = "AddQuestion~".$strTab;
                echo $strResposeText;
                exit;
            }
        }
    }
    exit;
    
?>