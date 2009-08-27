<?php
    /**
    * @version addoptions.php 2009-07-10 $
    * @copyright Copyright (C) icrs.com. All rights reserved.
    *
    * @author Amol Divalkar
    * @Modified By 
    * @version 1.0 
    * @desc This is main file (addoptions) which is used to render addoptions page for the project
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
    $hdlTpl->loadTemplateFile("addoptions.htm",TRUE,TRUE); 
      
    $strHeader = $hldGlobal->fnGetHeader();//Fetches header template
    $strMetatag = $hldGlobal->fnGetMetatag();//Fetches Metatag template
    $strFooter = $hldGlobal->fnGetFooter();//Fetches footer template
    $strLeftMenu = $hldGlobal->fnGetLMenu();//Fetches left menu template
    $arrCMName = $hldGlobal->fnGetCMName($_GET["id"]);//Fetches cource and module name

    $hdlTpl->setVariable("metatag",$strMetatag);//Assigns Metatag
    $hdlTpl->setVariable("header",$strHeader);//Assigns Header 
    $hdlTpl->setVariable("footer",$strFooter);//Assigns footer
    $hdlTpl->setVariable("leftmenu",$strLeftMenu);//Assigns left menu
    
    if($_POST["submit"] == "Add answers"){
        
        $strErrormessage = "";
        $intError = 1;
        if(is_array($_POST["ans"]) && count($_POST["ans"]) > 0){
            for($i=0;$i<count($_POST["ans"]);$i++){
                if(trim($_POST["ans"][$i]) != ""){
                    $intError = 0;
                    $sql = "INSERT INTO tblquizzesoptions(id,quizzes_id,options) values('','".$_GET["id"]."','".addslashes(trim($_POST["ans"][$i]))."')";
                    mysql_query($sql);
                    if($_POST["opt"] == $i){
                        $intOption = mysql_insert_id();
                    }
                }
            }
        }
        $sqlquestion = "UPDATE tblquizzes SET option_id='".$intOption."' WHERE id=".$_GET["id"];
        mysql_query($sqlquestion);
        
        if(!$intError){
            header("Location:message.php?mess=8");
        } else {
          $strErrormessage = "Please enter atleast one answer for this question.";  
          $hdlTpl->setVariable("error_message",$strErrormessage);//Assigns values
        }   
    }
    
    
    if(is_array($arrCMName) && count($arrCMName) > 0){
        $hdlTpl->setVariable("mname",$arrCMName[0]["cname"]."->".$arrCMName[0]["mname"]);//Assigns module and cource name
        $hdlTpl->setVariable("quizzes_question",$arrCMName[0]["quizzes_question"]);//Assigns question
        $hdlTpl->setVariable("iid",$_GET["id"]); //Assigns id
        $hdlTpl->setVariable("howmany",$_GET["howmany"]); //Assigns id
        
        if($_GET["howmany"]){
            $stropt = "";
            $intans = 1;
            for($i=0;$i<$_GET["howmany"];$i++){
                $stropt .= "<input type='radio' id='opt' name='opt' value='".$i."' >".$intans.")";
                $intans++;
            }
            $hdlTpl->setVariable("correct_options",$stropt); //Assigns correct answer
        }
        
        $int =1;
        for($i=0;$i<$_GET["howmany"];$i++){
            $hdlTpl->setVariable("id1",$int);
            $hdlTpl->setVariable("id",$i);//Assigns id
            $hdlTpl->parse("ans");
            $int++;
        }
    }
     
    $hdlTpl->parse("ADMIN_INNER_PAGE");
    $hdlTpl->parse("INNER_PAGE");
    echo $hdlTpl->get();
    
?>