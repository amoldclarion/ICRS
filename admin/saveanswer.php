<?php
    /**
    * @version saveanswer.php 2009-07-10 $
    * @copyright Copyright (C) ICRS.com. All rights reserved.
    *
    * @author Amol Divalkar
    * @Modified By 
    * @version 1.0 
    * @desc This file (saveanswer) is used to save the answer for the question
    */
      
    //Includes config file
    include("../includes/config.inc.php");
    
    //Includes global class file
    include(DIR_ADMIN_CLASSES."/clsGlobal.php");
    
    //Creates object for global class file
    $hldGlobal = new clsGlobal($hdlDb);
     
    if($_POST["submit"] == "Save"){
        $ans = "ans".$_POST["qid"];
        $ans1 = $_POST[$ans];
        if(trim($ans1) == ""){
            echo "Please enter value"; 
        } else {
            $sql = "UPDATE tblquizzesoptions SET options='".addslashes(trim($ans1))."' WHERE id=".$_POST["qid"];
            mysql_query($sql);
            echo "Answer saved successfully"; 
        }
    }
    exit;
    
?>