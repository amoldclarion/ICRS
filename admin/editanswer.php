<?php
    /**
    * @version editanswer.php 2009-07-10 $
    * @copyright Copyright (C) icrs.com. All rights reserved.
    *
    * @author Amol Divalkar
    * @Modified By 
    * @version 1.0 
    * @desc This is main file (editanswer) which is used to render editanswer page for the project
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
    $hdlTpl->loadTemplateFile("editanswer.htm",TRUE,TRUE); 
      
    $strHeader = $hldGlobal->fnGetHeader();//Fetches header template
    $strMetatag = $hldGlobal->fnGetMetatag();//Fetches Metatag template
    $strFooter = $hldGlobal->fnGetFooter();//Fetches footer template
    $strLeftMenu = $hldGlobal->fnGetLMenu();//Fetches left menu template
    
    $hdlTpl->setVariable("metatag",$strMetatag);//Assigns Metatag
    $hdlTpl->setVariable("header",$strHeader);//Assigns Header 
    $hdlTpl->setVariable("footer",$strFooter);//Assigns footer
    $hdlTpl->setVariable("leftmenu",$strLeftMenu);//Assigns left menu
    $intmess = 0;
    if($_POST["save"] == "Save Answer"){
       $sqlUpd = "UPDATE tblquizzes SET option_id='".$_POST["ans"]."' WHERE id=".$_GET["id"];
       mysql_query($sqlUpd) or die(mysql_error());
       $intmess = 1;
    }
    
    if($intmess){
        $hdlTpl->setVariable("error_message","Correct option updated successfully"); //Assigns error message
    }
    
    $arrCMName = $hldGlobal->fnGetCMName($_GET["id"]);//Fetches cource and module name
    if(is_array($arrCMName) && count($arrCMName) > 0){
        $hdlTpl->setVariable("mname",$arrCMName[0]["cname"]."->".$arrCMName[0]["cname"]);//Assigns module and cource name
        $hdlTpl->setVariable("quizzes_question",$arrCMName[0]["quizzes_question"]);//Assigns question
        $hdlTpl->setVariable("qid",$_GET["id"]); //Assigns id
        $hdlTpl->setVariable("return",$_GET["return"]); //Assigns return
        $arrAnswer = $hldGlobal->fnGetCountAnswer($_GET["id"]);
        $arrAns = $hldGlobal->fnFetchAnswer($_GET["id"]);
        if(is_array($arrAns) && count($arrAns)){
            $stropt = "";
            $intans = 1;
            foreach($arrAns as $key1 => $value1){
                if($arrCMName[0]["option_id"] == $value1["id"]){
                    $strChecked = "checked='checked'";
                } else {
                    $strChecked = "";
                }
                $stropt .= "<input type='radio' name='ans' value='".$value1["id"]."' ".$strChecked.">".$intans.")";
                $intans++;
            }
            $hdlTpl->setVariable("correct_option",$stropt); //Assigns correct answer
        }
        if(is_array($arrAns) && count($arrAns) > 0){
            $int = 1;
            foreach($arrAns as $key => $value){
                $hdlTpl->setVariable("ans",$value["options"]); //Assigns options
                $hdlTpl->setVariable("id",$value["id"]); //Assigns id
                $hdlTpl->setVariable("id1",$int); //Assigns int
                $hdlTpl->parse("ans");
                $int++;
            }
        }
    }
     
    $hdlTpl->parse("ADMIN_INNER_PAGE");
    $hdlTpl->parse("INNER_PAGE");
    echo $hdlTpl->get();
    
?>