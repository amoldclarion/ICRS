<?php
    /**
    * @version deletecources.php 2009-07-09 $
    * @copyright Copyright (C) icrs.com. All rights reserved.
    *
    * @author Amol Divalkar
    * @Modified By 
    * @version 1.0 
    * @desc This is main file (deletecources) which is used to delete the record from the table
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
    
    //This will delete cources
    $sqlChange = "DELETE FROM tblcources WHERE id='".$_GET["id"]."'";
    mysql_query($sqlChange);
    
    //This will delete all the modules related to the course
    $sqlChange1 = "DELETE FROM tblmodule WHERE course_id='".$_GET["id"]."'";
    mysql_query($sqlChange1);
    
    //This will delete all the quiz title related to the course
    $sqlChange2 = "DELETE FROM tblQuizTitle WHERE course_id='".$_GET["id"]."'";
    mysql_query($sqlChange2);
    
    //This will delete all the quiz answer related to that course.
    $sqlChange3 = "DELETE FROM tblQuizAnswer WHERE course_id='".$_GET["id"]."'";
    mysql_query($sqlChange3);
    
    $strReturn = $hldGlobal->fnDecodeURL($_GET["return"]);   
    
    header("Location:message.php?mess=4&return=$strReturn");
    
?>
