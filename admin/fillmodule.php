<?php
   /**
    * @version fillmodule.php 2009-08-20 $
    * @copyright Copyright (C) icrs.com. All rights reserved.
    *
    * @author Amol Divalkar
    * @Modified By 
    * @version 1.0 
    * @desc This is main file (fillmodule) which is used to check the generate the moudle drop down
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
    $strSel = '';
    $strSel .= '<select name="module_id" id="module_id">';
    $strSel .= '<option value="--">--Select module name--</option>';
    $strModuleOption =  $hldGlobal->fnGetModuleOption($_GET["cid"]);
    $strSel .= $strModuleOption;
    $strSel .= '</select>';
    echo $strSel;
    
?>