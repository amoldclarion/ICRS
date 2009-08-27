<?php
    /**
    * @version deletefiles.php 2009-06-10 $
    * @copyright Copyright (C) peachtreecardiovascular.com. All rights reserved.
    *
    * @author Amol Divalkar
    * @Modified By 
    * @version 1.0 
    * @desc This is main file (deletefiles) which is used to delete the record from the table
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
    
    $sqlChange = "DELETE FROM tblPeachUploadFiles WHERE intFileId='".$_GET["id"]."'";
    mysql_query($sqlChange);
    
    $strReturn = $hldGlobal->fnDecodeURL($_GET["return"]);
    
    //header("Location:$strReturn");
    
    header("Location:message.php?mess=15&return=$strReturn");
    
?>
