<?php
    /**
    * @version addquizzes.php 2009-07-10 $
    * @copyright Copyright (C) icrs.com. All rights reserved.
    *
    * @author Amol Divalkar
    * @Modified By 
    * @version 1.0 
    * @desc This is main file (addquizzes) which is used to render addquizzes page for the project
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
    $hdlTpl->loadTemplateFile("addquizzes.htm",TRUE,TRUE); 
      
    $strHeader = $hldGlobal->fnGetHeader();//Fetches header template
    $strMetatag = $hldGlobal->fnGetMetatag();//Fetches Metatag template
    $strFooter = $hldGlobal->fnGetFooter();//Fetches footer template
    $strLeftMenu = $hldGlobal->fnGetLMenu();//Fetches left menu template
    $strCourse = $hldGlobal->fnGetCource($_POST["course_id"]);
    //$strOption = $hldGlobal->fnGetModule($_POST["module_id"]);//Fetches options

    $hdlTpl->setVariable("metatag",$strMetatag);//Assigns Metatag
    $hdlTpl->setVariable("header",$strHeader);//Assigns Header 
    $hdlTpl->setVariable("footer",$strFooter);//Assigns footer
    $hdlTpl->setVariable("leftmenu",$strLeftMenu);//Assigns left menu
    $intError = 1;
    if($_POST["submit"] == "Add quiz"){
        $strErrormessage = "";
        
        if(trim($_POST["course_id"]) == "--"){
            $strErrormessage .= "Please select course name.<br>";
            $intError = 0;
        }
        if(trim($_POST["quiztitle"]) == ""){
            $strErrormessage .= "Please enter quiz title.<br>";
            $intError = 0;
        }        
        if($_SESSION["InTlAsTiD"] == ""){
            if($hldGlobal->fnCheckQuiztitle($_POST["course_id"],trim($_POST["quiztitle"]))){
                $strErrormessage .= "Quiz title already added.<br>";
                $intError = 0;                        
            }
        }
        
        if($intError){
            foreach($_POST as $key => $value){
                $_POST[$key] = mysql_escape_string(trim($value));
            }
            
            if($_SESSION["InTlAsTiD"]){
                $sql = "UPDATE tblQuizTitle SET to_show='1' WHERE id=".$_SESSION["InTlAsTiD"]; 
            } else {
                $sql = "INSERT INTO tblQuizTitle(id,course_id,quiz_title,to_show,isactive,dtcreated) VALUES('','".$_POST["course_id"]."','".$_POST["quiztitle"]."','1','1','".date("Y-m-d H:i:s")."')"; 
            }
            mysql_query($sql);
            
            unset($_SESSION["InTlAsTiD"]);
            
            header("Location:message.php?mess=21");
        }
    }
    
    $strSel = '';
    $strSel .= '<select name="module_id" id="module_id">';
    $strSel .= '<option value="--">--Select module name--</option>';
    if(!$intError){
        $strModuleOption =  $hldGlobal->fnGetModuleOption($_POST["course_id"],$_POST["module_id"]);
        $strSel .= $strModuleOption;
        $strSel .= '</select>';
        $hdlTpl->setVariable("selmodule",$strSel);//Assigns module drop down
        $hdlTpl->setVariable("error_message",$strErrormessage);//Assigns error message
    } else {
        $strSel .= '</select>';
        $hdlTpl->setVariable("selmodule",$strSel);//Assigns module drop down
    }
    
    unset($_SESSION["InTlAsTiD"]);
    $hdlTpl->setVariable("coptval",$strCourse);//Assigns option
    foreach($_POST as $key => $value){
         $hdlTpl->setVariable($key,$value);//Assigns values
    }
     
    $hdlTpl->parse("ADMIN_INNER_PAGE");
    $hdlTpl->parse("INNER_PAGE");
    echo $hdlTpl->get();
    
?>