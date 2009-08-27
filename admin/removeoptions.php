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
    
    $arrIds = explode("~",$_GET["id"]);
    
    $hldGlobal->fnDeleteOptions($arrIds[0]);
    
    $strTab = $hldGlobal->fnFetchAnswerTemp($arrIds[1]);
    
    echo $strTab;
    
?>
