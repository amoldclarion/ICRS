<?php
    /**
    * @version deletequizes.php 2009-07-10 $
    * @copyright Copyright (C) icrs.com. All rights reserved.
    *
    * @author Amol Divalkar
    * @Modified By 
    * @version 1.0 
    * @desc This is main file (deletequizes) which is used to delete the record from the table
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
    
    $arrQuizId = $hldGlobal->fnGetQuizId($_GET["id"]);
    
    $sqlDel1 = "DELETE FROM tblQuizOption qid in ('".$arrQuizId[0]["quesId"]."')";
    mysql_query($sqlDel1);
    
    $sqlDel2 = "DELETE FROM tblQuizQuestion quiz_title_id in ('".$_GET["id"]."')";
    mysql_query($sqlDel2);
    
    $sqlChange = "DELETE FROM tblQuizTitle WHERE id='".$_GET["id"]."'";
    mysql_query($sqlChange);
    
    $sqlChange2 = "DELETE FROM tblQuizAnswer WHERE qid='".$_GET["id"]."'";
    mysql_query($sqlChange2);
    
    $strReturn = $hldGlobal->fnDecodeURL($_GET["return"]);   
    
    header("Location:message.php?mess=10&return=$strReturn");
    
?>
