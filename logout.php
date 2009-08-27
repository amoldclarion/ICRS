<?php 
    /**
    * @version logout.php 2009-07-16 $
    * @copyright Copyright (C) ircs.com. All rights reserved.
    *
    * @author Amol Divalkar
    * @Modified By 
    * @version 1.0 
    * @desc This is logout file which will clear all the session values.
    */
    //Includes config file
    include("includes/config.inc.php");
     
    //Includes global class file
    include(DIR_CLASSES."/clsGlobal.php");
    //include(DIR_CLASSES."/clsAdmin.php");
    
    //Creates object for global class file
    $hldGlobal = new clsGlobal($hdlDb);
    
    $sqlUpd = "UPDATE tblusertrack SET timeout='".date("H:i:s")."' WHERE id=".$_SESSION["LaStId"];
    mysql_query($sqlUpd);
    
    $hldGlobal->fnUserTrackMain($_SESSION["UID"]);
    
    $_SESSION["UID"] = "";//Empty session value
    $_SESSION["UNAME"] = "";//Empty session value
    $_SESSION["LaStId"] = "";//Empty session value
    $_SESSION["PaGeNaMe"] = "";//Empty session value   
    $_SESSION["MaInId"] = "";//Empty session value
    
    unset($_SESSION["UID"]);//unset session
    unset($_SESSION["UNAME"]);//unset session
    unset($_SESSION["LaStId"]);//unset session
    unset($_SESSION["PaGeNaMe"]);//unset session
    unset($_SESSION["MaInId"]);//unset session
    
    header("Location:login.htm");//Redirect to login page.
?>