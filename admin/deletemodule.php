<?php
    /**
    * @version deletemodule.php 2009-07-10 $
    * @copyright Copyright (C) icrs.com. All rights reserved.
    *
    * @author Amol Divalkar
    * @Modified By 
    * @version 1.0 
    * @desc This is main file (deletemodule) which is used to delete the record from the table
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
    
    //This will select course id from the module table
    $sqlModule = "SELECT course_id FROM tblmodule WHERE id='".$_GET["id"]."'";
    $rs = mysql_query($sqlModule);
    $cid = mysql_result($rs,0,'course_id');
    
    //This will delete module from module table
    $sqlChange = "DELETE FROM tblmodule WHERE id='".$_GET["id"]."'";
    mysql_query($sqlChange);
    
    //This will delete quiz title related to that module.
    $sqlChange1 = "DELETE FROM tblQuizTitle WHERE course_id='".$cid."'";
    mysql_query($sqlChange1);
    
    //This will delete all the answer from the answer table related to module.
    $sqlChange2 = "DELETE FROM tblQuizAnswer WHERE module_id='".$_GET["id"]."'";
    mysql_query($sqlChange2);
    
    $strReturn = $hldGlobal->fnDecodeURL($_GET["return"]);   
    
    header("Location:message.php?mess=7&return=$strReturn");
    
?>
