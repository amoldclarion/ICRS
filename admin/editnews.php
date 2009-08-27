<?php
    /**
    * @version editnews.php 2009-06-12 $
    * @copyright Copyright (C) peachtreecardiovascular.com. All rights reserved.
    *
    * @author Amol Divalkar
    * @Modified By 
    * @version 1.0 
    * @desc This is main file (editnews) which is used to edit the news.
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
    $hdlTpl->loadTemplateFile("editnews.htm",TRUE,TRUE);  
    
    if($_POST["submit"] == "Save"){//Checks if form is submited or not.
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
        
        if(!$intError) {
            foreach($_POST AS $key => $value){
                $_POST[$key] = mysql_escape_string(trim($value));
            }
            $sqlUpd = "UPDATE tblnews SET newstitle='".$_POST["newstitle"]."',meta_keyword='".$_POST["meta_keyword"]."',meta_description='".$_POST["meta_description"]."',content='".$_POST["ncontent"]."' ";            
            $sqlUpd .= " WHERE id=".$_POST["id"];
            mysql_query($sqlUpd) or die(mysql_error());
            $return = $_POST["return"];
            header("Location:message.php?mess=14&return=$return");
        }
        $hdlTpl->setVariable("error_message",$strErrorMessage);//Assigns error message
    }   
    
    $arrEditUser = $hldGlobal->fnFetchManagenews($_GET["id"]);
    
    if(is_array($arrEditUser) && count($arrEditUser) > 0){
        $id = ($_GET["id"]) ? $_GET["id"] : $_POST["id"];
        $return = ($_GET["return"]) ? $_GET["return"] : $_POST["return"];
        $hdlTpl->setVariable("newstitle",stripslashes($arrEditUser[0]["newstitle"]));//Assigns news title
        $hdlTpl->setVariable("meta_keyword",stripslashes($arrEditUser[0]["meta_keyword"]));//Assigns news content
        $hdlTpl->setVariable("meta_description",stripslashes($arrEditUser[0]["meta_description"]));//Assigns news keyword
        $hdlTpl->setVariable("ncontent",stripslashes($arrEditUser[0]["content"]));//Assigns news description
        $hdlTpl->setVariable("id",$id);//Assigns id
        $hdlTpl->setVariable("return",$return);//Assigns return
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
