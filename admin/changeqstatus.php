<?php
    /**
    * @version changeqstatus.php 2009-07-10 $
    * @copyright Copyright (C) IRCS.com. All rights reserved.
    *
    * @author Amol Divalkar
    * @Modified By 
    * @version 1.0 
    * @desc This is main file (changeqstatus) which is used to change the status of the file
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
    
    $sqlChange = "UPDATE tblQuizTitle SET isactive='".$_GET["activeflg"]."' WHERE id='".$_GET["id"]."'";
    mysql_query($sqlChange) or die(mysql_error());
    
    $strReturn = $hldGlobal->fnDecodeURL($_GET["return"]);
    
    header("Location:message.php?mess=2&return=$strReturn");
    
?>
