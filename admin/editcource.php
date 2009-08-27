<?php
    /**
    * @version editcource.php 2009-07-09 $
    * @copyright Copyright (C) icrs.com. All rights reserved.
    *
    * @author Amol Divalkar
    * @Modified By 
    * @version 1.0 
    * @desc This is main file (editcource) which is used to render editcource page for the project
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

    //Creates the object for sigma template.
    $hdlTpl =& new HTML_Template_Sigma( DIR_ADMIN_TEMPLATE , DIR_ADMIN_TEMPLATE."/prepared");
    //Loads the template from template folder
    $hdlTpl->loadTemplateFile("editcource.htm",TRUE,TRUE); 
      
    $strHeader = $hldGlobal->fnGetHeader();//Fetches header template
    $strMetatag = $hldGlobal->fnGetMetatag();//Fetches Metatag template
    $strFooter = $hldGlobal->fnGetFooter();//Fetches footer template
    $strLeftMenu = $hldGlobal->fnGetLMenu();//Fetches left menu template
    
    $hdlTpl->setVariable("metatag",$strMetatag);//Assigns Metatag
    $hdlTpl->setVariable("header",$strHeader);//Assigns Header 
    $hdlTpl->setVariable("footer",$strFooter);//Assigns footer
    $hdlTpl->setVariable("leftmenu",$strLeftMenu);//Assigns left menu
    
    if($_POST["submit"] == "Save"){
        $strErrormessage = "";
        $intError = 1;
        if(trim($_POST["cname"]) == ""){
            $strErrormessage = "Please enter cources name.<br>";
            $intError = 0;
        }
        if($intError){
            foreach($_POST as $key => $value){
                $_POST[$key] = mysql_escape_string(trim($value));
            }
            $sql = "UPDATE tblcources SET cname='".$_POST["cname"]."',cdescription='".$_POST["cdescription"]."',dtmodified='".date("Y-m-d H:i:s")."' WHERE id=".$_GET["id"];
            mysql_query($sql);
            $return = $_GET["return"];
            header("Location:message.php?mess=3&return=$return");
        }
    }
    if(!$intError){
        $hdlTpl->setVariable("error_message",$strErrormessage);//Assigns values
    }
    
    $arrCources = $hldGlobal->fnFetchCources($_GET["id"]);
    
    foreach($arrCources as $key => $value){
         $hdlTpl->setVariable("id",$value["id"]);//Assigns id
         $hdlTpl->setVariable("return",$_GET["return"]);//Assigns return
         $hdlTpl->setVariable("cname",$value["cname"]);//Assigns cname
         $hdlTpl->setVariable("cdescription",$value["cdescription"]);//Assigns cdescription
    }
    
    $hdlTpl->parse("ADMIN_INNER_PAGE");
    $hdlTpl->parse("INNER_PAGE");
    echo $hdlTpl->get();
    
?>