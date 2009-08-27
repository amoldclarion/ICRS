<?php
    /**
    * @version removequestion.php 2009-08-06 $
    * @copyright Copyright (C) icrs.com. All rights reserved.
    *
    * @author Amol Divalkar
    * @Modified By 
    * @version 1.0 
    * @desc This is main file (removequestion) which is used to remove the question for particular quiz
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
        $sql = "SELECT quiz_title_id FROM tblQuizQuestion WHERE id=".$_GET["id"];
        $rs = mysql_query($sql);
        $rw = mysql_fetch_array($rs);
        $intLastId = $rw["quiz_title_id"];
    }
    
    $hldGlobal->fnDeleteQuestion($_GET["id"]);
    
    $strTab = $hldGlobal->fnFetchQuestionQuiz($intLastId);
    echo $strTab;
?>
