<?php
    /**
    * @version addoption.php 2009-08-06 $
    * @copyright Copyright (C) icrs.com. All rights reserved.
    *
    * @author Amol Divalkar
    * @Modified By 
    * @version 1.0 
    * @desc This is main file (addoption) which is used to add the options for the particular question
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
    
    $intError = 0;
    for($i=0;$i<$_POST["hid"];$i++){
        if($_POST["opt"][$i] == ""){
            $intError = 1;
            break;
        }
    }
    //print_r($_POST);
    
    if($intError){
        echo "Options should not be empty<br>";
    }
    
    if(!$intError){
        for($i=0;$i<$_POST["hid"];$i++){
            //$sql = "INSERT INTO temp_quiz_options(id,qid,options,isactive,dtcreated) values('','".$_POST["qid"]."','".addslashes(trim($_POST["opt"][$i]))."','1','".date("Y-m-d H:i:s")."')";
            $sql = "INSERT INTO tblQuizOption(id,qid,options,isactive,dtcreated) values('','".$_POST["qid"]."','".addslashes(trim($_POST["opt"][$i]))."','1','".date("Y-m-d H:i:s")."')";
            mysql_query($sql) or die(mysql_error());
            if($_POST["ans"] != ""){
                if($_POST["ans"] == $i){
                    $intOption = mysql_insert_id();
                }
            }
        }
        if($intOption){
            //$sqlUpd = "UPDATE temp_quiz_question SET option_id='".$intOption."' WHERE id=".$_POST["qid"];
            $sqlUpd = "UPDATE tblQuizQuestion SET option_id='".$intOption."' WHERE id=".$_POST["qid"];
            mysql_query($sqlUpd);
        }
        
        $strTab = $hldGlobal->fnFetchAnswerTemp($_POST["qid"]);
        //$sql = "SELECT quiz_title_id FROM temp_quiz_question WHERE id=".$_POST["qid"];
        $sql = "SELECT quiz_title_id FROM tblQuizQuestion WHERE id=".$_POST["qid"];
        $rs = mysql_query($sql);
        $rw = mysql_fetch_array($rs);
        $strResposeText = "AddOption~".$rw["quiz_title_id"];
        echo $strResposeText;
    }     
    exit;
?>
