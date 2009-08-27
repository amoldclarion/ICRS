<?php
    /**
    * @version fetchquestion.php 2009-08-07 $
    * @copyright Copyright (C) icrs.com. All rights reserved.
    *
    * @author Amol Divalkar
    * @Modified By 
    * @version 1.0 
    * @desc This is main file (fetchquestion) which is used to show the question for the quiz
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
    if($_SESSION["InTlAsTiD"]){
        $intLastId = $_SESSION["InTlAsTiD"];
    } else {
        $intLastId = $_GET["qid"];
    }
    $strTab = $hldGlobal->fnFetchQuestionQuiz($intLastId);
    
    echo $strTab;
?>
