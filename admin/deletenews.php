<?php
    /**
    * @version deletenews.php 2009-07-14 $
    * @copyright Copyright (C) IRCS.com. All rights reserved.
    *
    * @author Amol Divalkar
    * @Modified By 
    * @version 1.0 
    * @desc This is main file (deletenews) which is used to delete news from the table
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
    
    $sqlChange = "DELETE FROM tblnews WHERE id='".$_GET["id"]."'";
    mysql_query($sqlChange);
    
    $strReturn = $hldGlobal->fnDecodeURL($_GET["return"]);
    
    //header("Location:$strReturn");
    
    header("Location:message.php?mess=13&return=$strReturn");
    
?>
