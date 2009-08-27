<?php
   /**
    * @version getmodule.php 2009-07-24 $
    * @copyright Copyright (C) icrs.com. All rights reserved.
    *
    * @author Amol Divalkar
    * @Modified By 
    * @version 1.0 
    * @desc This is main file (getmodule) which is used to display module drop down
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
    
    $strModuleOption =  $hldGlobal->fnGetModuleOption($_POST["course_id"]);
    $strSel = '';
    $strSel .= '<select name="module_id" id="module_id">';
    $strSel .= '<option value="--">--Select module name--</option>';
    $strSel .= $strModuleOption;
    $strSel .= '</select>';    
    echo $strSel;
    
?>
