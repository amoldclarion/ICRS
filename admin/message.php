<?php
    /**
    * @version message.php 2009-06-10 $
    * @copyright Copyright (C) peachtreecardiovascular.com. All rights reserved.
    *
    * @author Amol Divalkar
    * @Modified By 
    * @version 1.0 
    * @desc This is message file which will show successfully message to admin
    */
    
    //Includes config file
    include("../includes/config.inc.php");
    //Includes global class file
    include(DIR_ADMIN_CLASSES."/clsGlobal.php");

    //Checks if session has values or not
    if($_SESSION["UsErId"] == "" && $_SESSION["UnAmE"] == ""){
        header("Location:index.php");
    }
    
    //Creates object for global class file
    $hldGlobal = new clsGlobal($hdlDb);
    
    //Creates object for sigma template
    $hdlTpl =& new HTML_Template_Sigma( DIR_ADMIN_TEMPLATE , DIR_ADMIN_TEMPLATE."/prepared");
    //Loads message template.
    $hdlTpl->loadTemplateFile("message.htm",TRUE,TRUE); 
    
    $strHeader = $hldGlobal->fnGetHeader();//Fetches header template
    $strFooter = $hldGlobal->fnGetFooter();//Fetches footer template
    $strLeftMenu = $hldGlobal->fnGetLMenu();//Fetches menu template
    $strMetatag = $hldGlobal->fnGetMetatag();//Fetches Metatag template
    
    $hdlTpl->setVariable("metatag",$strMetatag);//Assigns Metatag
    $hdlTpl->setVariable("header",$strHeader);//Assigns header template
    $hdlTpl->setVariable("footer",$strFooter);//Assigns footer template
    
    $return = $hldGlobal->fnDecodeURL($_GET["return"]);
    
    if($_GET["mess"]){ //Checks if mess has any value or not  
        if($_GET["mess"] == 1){
            //Assigns message to variable
            $strMessage = "Cources added successfully... <br><a href='addcource.php' class='green_link'><< Back</a>";
        }
        if($_GET["mess"] == 2){
            //Assigns message to variable
            $strMessage = "Status change successfully... <br><a href='".$return."' class='green_link'><< Back</a>";
        }
        if($_GET["mess"] == 3){
            //Assigns message to variable
            $strMessage = "Cources updated successfully... <br><a href='".$return."' class='green_link'><< Back</a>";
        }
        if($_GET["mess"] == 4){
            //Assigns message to variable
            $strMessage = "Cources deleted successfully... <br><a href='".$return."' class='green_link'><< Back</a>";
        }
        if($_GET["mess"] == 5){
            //Assigns message to variable
            $strMessage = "Module added successfully... <br><a href='addmodule.php' class='green_link'><< Back</a>";
        }
        if($_GET["mess"] == 6){
            //Assigns message to variable
            $strMessage = "Module updated successfully... <br><a href='".$return."' class='green_link'><< Back</a>";
        }
        if($_GET["mess"] == 7){
            //Assigns message to variable
            $strMessage = "Module deleted successfully... <br><a href='".$return."' class='green_link'><< Back</a>";
        }
        if($_GET["mess"] == 8){
            //Assigns message to variable
            $strMessage = "Answer added successfully... <br><a href='addquizzes.php' class='green_link'><< Back</a>";
        }
        if($_GET["mess"] == 9){
            //Assigns message to variable
            $strMessage = "Quiz updated successfully... <br><a href='".$return."' class='green_link'><< Back</a>";
        }
        if($_GET["mess"] == 10){
            //Assigns message to variable
            $strMessage = "Quiz deleted successfully... <br><a href='".$return."' class='green_link'><< Back</a>";
        }
        if($_GET["mess"] == 11){
            //Assigns message to variable
            $strMessage = "Teaching techniques added successfully... <br><a href='addttechniques.php' class='green_link'><< Back</a>";
        }
        if($_GET["mess"] == 12){
            //Assigns message to variable
            $strMessage = "Teaching techniques updated successfully... <br><a href='".$return."' class='green_link'><< Back</a>";
        }
        if($_GET["mess"] == 13){
            //Assigns message to variable
            $strMessage = "Teaching techniques deleted successfully... <br><a href='".$return."' class='green_link'><< Back</a>";
        }
        if($_GET["mess"] == 12){
            //Assigns message to variable
            $strMessage = "News added successfully... <br><a href='addnews.php' class='green_link'><< Back</a>";
        }
        if($_GET["mess"] == 13){
            //Assigns message to variable
            $strMessage = "News deleted successfully... <br><a href='".$return."' class='green_link'><< Back</a>";
        }
        if($_GET["mess"] == 14){
            //Assigns message to variable
            $strMessage = "News updated successfully... <br><a href='".$return."' class='green_link'><< Back</a>";
        }
        if($_GET["mess"] == 15){
            //Assigns message to variable
            $strMessage = "User deleted successfully... <br><a href='".$return."' class='green_link'><< Back</a>";
        }
        if($_GET["mess"] == 16){
            //Assigns message to variable
            $strMessage = "User edited successfully... <br><a href='".$return."' class='green_link'><< Back</a>";
        }
        if($_GET["mess"] == 17){
            //Assigns message to variable
            $strMessage = "User added successfully... <br><a href='adduser.php' class='green_link'><< Back</a>";
        }
        if($_GET["mess"] == 18){
            //Assigns message to variable
            $strMessage = "Page added successfully... <br><a href='addpage.php' class='green_link'><< Back</a>";
        }
        if($_GET["mess"] == 19){
            //Assigns message to variable
            $strMessage = "Page deleted successfully... <br><a href='".$return."' class='green_link'><< Back</a>";
        }
        if($_GET["mess"] == 20){
            //Assigns message to variable
            $strMessage = "Page updated successfully... <br><a href='".$return."' class='green_link'><< Back</a>";
        }
        if($_GET["mess"] == 21){
            //Assigns message to variable
            $strMessage = "Quiz added successfully... <br><a href='addquizzes.php' class='green_link'><< Back</a>";
        }
        if($_GET["mess"] == 22){
            //Assigns message to variable
            $strMessage = "Contact deleted successfully... <br><a href='".$return."' class='green_link'><< Back</a>";
        }
    }
    
    $hdlTpl->setVariable("leftmenu",$strLeftMenu);//Assigns left menu
    $hdlTpl->setVariable("message",$strMessage);//Assigns message
    $hdlTpl->parse("ADMIN_INNER_PAGE");
    $hdlTpl->parse("INNER_PAGE");
    echo $hdlTpl->get();
    
?>
