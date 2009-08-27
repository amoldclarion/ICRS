<?php
    /**
    * @version index.php 2009-07-09 $
    * @copyright Copyright (C) icrs.com. All rights reserved.
    *
    * @author Amol Divalkar
    * @Modified By 
    * @version 1.0 
    * @desc This is main file (index) which is used to render index page for the project
    */
      
    //Includes config file
    include("../includes/config.inc.php");
    
    //Includes global class file
    include(DIR_ADMIN_CLASSES."/clsGlobal.php");
    include(DIR_ADMIN_CLASSES."/clsAdmin.php");
    
    //Creates object for global class file
    $hldGlobal = new clsGlobal($hdlDb);
    
    //Creates object for Admin class file
    $hldAdmin = new clsAdmin($hdlDb,$hldGlobal); 
    
    //Creates the object for sigma template.
    $hdlTpl =& new HTML_Template_Sigma( DIR_ADMIN_TEMPLATE , DIR_ADMIN_TEMPLATE."/prepared");
    //Loads index template file
    $hdlTpl->loadTemplateFile("index.htm",TRUE,TRUE); 
    
    
    $strHeader = $hldGlobal->fnGetHeader();//Fetches header template
    $strMetatag = $hldGlobal->fnGetMetatag();//Fetches Metatag template
    $strFooter = $hldGlobal->fnGetFooter();//Fetches footer template
    
    
    $hdlTpl->setVariable("metatag",$strMetatag);//Assigns Metatag
    $hdlTpl->setVariable("header",$strHeader);//Assigns header
    $hdlTpl->setVariable("footer",$strFooter);//Assigns footer
    
    if($_POST["submit"] == "Log In"){  //Checkes if post array has some values or not.
        if(trim($_POST["struname"]) == "" || trim($_POST["pass"]) == ""){ //Checks for the blank values
            //Assigns error message.
            $hdlTpl->setVariable("errormessage","User name or password should not be blank");
        } else {
            $intValidate = $hldAdmin->fnAdminLogin(trim($_POST["struname"]),trim($_POST["pass"]));//Checks the admin login
            if($intValidate){ //If all the values are correct
                $_SESSION["UsErId"] = $intValidate; //Assigns admin autoincremented id to session
                $_SESSION["UnAmE"] = $_POST["struname"];//Assigns admin user name to session
                header("Location:inner.php");//Redirect to inner page
            } else {
                //Assigns error message.
                $hdlTpl->setVariable("errormessage","Wrong user name or password");
            }
        }
        $hdlTpl->setVariable("struname",$_POST["struname"]);
    } 
    
    $hdlTpl->setVariable("space","&nbsp;");
    
    $hdlTpl->parse("INDEX_PAGE");
    echo $hdlTpl->get();
?>