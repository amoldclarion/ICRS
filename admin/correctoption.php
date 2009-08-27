<?php
    /**
    * @version correctoption.php 2009-08-13 $
    * @copyright Copyright (C) icrs.com. All rights reserved.
    *
    * @author Amol Divalkar
    * @Modified By 
    * @version 1.0 
    * @desc This is main file (correctoption) which is used to choice the correct option
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
    
    $arrOptId = explode("~",$_GET["id"]);
    $id = $arrOptId[0];
    $qid = $arrOptId[1];
    
    $intOption = $hldGlobal->fnCorrectOption($id,$qid);
    $strTab = $hldGlobal->fnFetchAnswerTemp($qid);
    echo $strTab; 
    echo '<script>document.getElementById("error_option").innerHTML="Correct option updated"</script>';
    exit;
    
?>