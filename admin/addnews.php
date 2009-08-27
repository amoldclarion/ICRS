<?php
    /**
    * @version addnews.php 2009-06-12 $
    * @copyright Copyright (C) peachtreecardiovascular.com. All rights reserved.
    *
    * @author Amol Divalkar
    * @Modified By 
    * @version 1.0 
    * @desc This is main file (addnews) which is used for adding news.
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
    $hdlTpl->loadTemplateFile("addnews.htm",TRUE,TRUE);  
    
    if($_POST["submit"] == "Add News"){//Checks if form is submited or not.
        $strErrorMessage = "";
        $intError = 0;
        if(trim($_POST["newstitle"]) == ""){
            $strErrorMessage .= "Please enter news title<br />";
            $intError = 1;
        } 
        if(trim($_POST["meta_keyword"]) == ""){
            $strErrorMessage .= "Please enter metatag keyword<br />";
            $intError = 1;
        } 
        if(trim(strip_tags($_POST["meta_description"])) == ""){            
            $strErrorMessage .= "Please enter metatag description<br />";
            $intError = 1;
        } 
        if($hldGlobal->fnCheckDescription(trim($_POST["ncontent"])) == ""){
            $strErrorMessage .= "Please enter news description<br />";
            $intError = 1;
        }
        
        if(!$intError){
            foreach($_POST AS $key => $value){
                $_POST[$key] = mysql_escape_string(trim($value));
            }
            $sqlInsUser = "INSERT INTO tblnews(id,newstitle,meta_keyword,meta_description,content,isactive,dtcreated) VALUES('','".$_POST["newstitle"]."','".$_POST["meta_keyword"]."','".$_POST["meta_description"]."','".$_POST["ncontent"]."','1','".date("Y-m-d H:i:s")."')";       
            mysql_query($sqlInsUser);
            header("Location:message.php?mess=12");
        }
        $hdlTpl->setVariable("error_message",$strErrorMessage);//Assigns error message
        $hdlTpl->setVariable("newstitle",$_POST["newstitle"]);//Assigns news title
        $hdlTpl->setVariable("meta_keyword",$_POST["meta_keyword"]);//Assigns news description
        $hdlTpl->setVariable("meta_description",$_POST["meta_description"]);//Assigns news description
        $hdlTpl->setVariable("ncontent",$_POST["ncontent"]);//Assigns news description
    }   
      
    $strHeader = $hldGlobal->fnGetHeader();//Fetches header template
    $strMetatag = $hldGlobal->fnGetMetatag();//Fetches Metatag template
    $strFooter = $hldGlobal->fnGetFooter();//Fetches footer template
    $strLeftMenu = $hldGlobal->fnGetLMenu();//Fetches left menu template
    
    $hdlTpl->setVariable("metatag",$strMetatag);//Assigns Metatag
    $hdlTpl->setVariable("header",$strHeader);//Assigns Header 
    $hdlTpl->setVariable("footer",$strFooter);//Assigns footer
    $hdlTpl->setVariable("leftmenu",$strLeftMenu);//Assigns left menu
    
    
    $hdlTpl->parse("ADMIN_INNER_PAGE");
    $hdlTpl->parse("INNER_PAGE");
    echo $hdlTpl->get();
    
?>
