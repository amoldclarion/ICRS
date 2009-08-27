<?php
   /**
    * @version checkquestion.php 2009-08-11 $
    * @copyright Copyright (C) icrs.com. All rights reserved.
    *
    * @author Amol Divalkar
    * @Modified By 
    * @version 1.0 
    * @desc This is main file (checkquestion) which is used to check the quiz title
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
    $intcheckid = 0;
    if(!$_SESSION["InTlAsTiD"]){
        $intcheckid = $hldGlobal->fnCheckTitleAjax($_GET["cid"],$_GET["qtitle"]);
    }
    
    if($intcheckid){
        echo "Quiz title already added.";
        exit;
    } else {
        /*$strResposeText = "ChecQuestion~".$_GET["cid"];
        echo $strResposeText;
        exit;*/
        $url = "fillmodule.php?cid=".$_GET["cid"];
        echo "<script>
              fnAjaxCall('show_module','".$url."','form1');
              popup('popUpDiv',0);
             </script>";
    }
?>
