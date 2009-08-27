<?php
    /**
    * @version modifyoption.php 2009-08-13 $
    * @copyright Copyright (C) icrs.com. All rights reserved.
    *
    * @author Amol Divalkar
    * @Modified By 
    * @version 1.0 
    * @desc This is main file (modifyoption) which is used to modify the options for the particular question
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
    
    $arrOptId = explode("~",$_GET["id"]);
    $id = $arrOptId[0];
    $qid = $arrOptId[1];
    $dydiv = "ans".$id;
    $opt = 'options'.$id;
    $ans = $_POST[$opt];
    $show = 'show'.$id;
    
    $intOption = $hldGlobal->fnCheckOption($id,$qid,$ans);
    
    if($intOption){
        echo "Option Already exists";
        exit;
    } else {
        $hldGlobal->fnUpdateOption($id,$qid,$ans);
        $stra = '<a href="javascript:showanswer(1,'.$id.');">'.$ans.'</a>';
        $strResposeText = "ModifyOption~".$stra."~".$id;
        echo $strResposeText;
        exit;
    }
    
?>