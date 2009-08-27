<?php

    /**
    * @version logout.php 2009-06-10 $
    * @copyright Copyright (C) peachtreecardiovascular.com. All rights reserved.
    *
    * @author Amol Divalkar
    * @Modified By 
    * @version 1.0 
    * @desc This is logout file which will clear all the session values.
    */
    session_start();//Starts session
    $_SESSION["UsErId"] = "";//Empty session value
    $_SESSION["UnAmE"] = "";//Empty session value
    unset($_SESSION["UsErId"]);//unset session
    unset($_SESSION["UnAmE"]);//unset session
    header("Location:index.php");//Redirect to index page.
?>