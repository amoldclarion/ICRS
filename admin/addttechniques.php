<?php
    /**
    * @version addttechniques.php 2009-07-10 $
    * @copyright Copyright (C) icrs.com. All rights reserved.
    *
    * @author Amol Divalkar
    * @Modified By 
    * @version 1.0 
    * @desc This is main file (addttechniques) which is used to render addttechniques page for the project
    */
   
    //Includes config file
    include("../includes/config.inc.php");
    //Includes global class file
    include(DIR_ADMIN_CLASSES."/clsGlobal.php");
    //Includes simple image file.
    include(DIR_ADMIN_CLASSES."/SimpleImage.php");

    //Checks if session is set for admin login or not
    if($_SESSION["UsErId"] == "" && $_SESSION["UnAmE"] == ""){
        header("Location:index.php");
    }
    //Creates object for global class file
    $hldGlobal = new clsGlobal($hdlDb);

    //Creates the object for sigma template.
    $hdlTpl =& new HTML_Template_Sigma( DIR_ADMIN_TEMPLATE , DIR_ADMIN_TEMPLATE."/prepared");
    //Loads the template from template folder
    $hdlTpl->loadTemplateFile("addttechniques.htm",TRUE,TRUE); 
      
    $strHeader = $hldGlobal->fnGetHeader();//Fetches header template
    $strMetatag = $hldGlobal->fnGetMetatag();//Fetches Metatag template
    $strFooter = $hldGlobal->fnGetFooter();//Fetches footer template
    $strLeftMenu = $hldGlobal->fnGetLMenu();//Fetches left menu template
    
    $hdlTpl->setVariable("metatag",$strMetatag);//Assigns Metatag
    $hdlTpl->setVariable("header",$strHeader);//Assigns Header 
    $hdlTpl->setVariable("footer",$strFooter);//Assigns footer
    $hdlTpl->setVariable("leftmenu",$strLeftMenu);//Assigns left menu
    
    if($_POST["submit"] == "Add Teching Techniques"){
        $strErrormessage = "";
        $intError = 1;
        
        if(trim($_POST["title"]) == "--"){
            $strErrormessage .= "Please enter title.<br>";
            $intError = 0;
        }
        if(trim($_POST["sdesc"]) == ""){
            $strErrormessage .= "Please enter shot description.<br>";
            $intError = 0;
        }        
        if($hldGlobal->fnCheckDescription($_POST["ldesc"]) == ""){
            $strErrormessage .= "Please enter long description.<br>";
            $intError = 0;
        }
        if(trim($_POST["title"]) != ""){
            if($hldGlobal->fnCheckTechnique(trim($_POST["title"]))){
                $strErrormessage .= "Technique already present.<br>";
                $intError = 0;
            }
        }
        
        if(is_array($_FILES["img"]) && $_FILES["img"]["error"] == 0){
            list($width, $height, $type, $attr) = getimagesize($_FILES['img']['tmp_name']);
            $arrPathInfo = pathinfo($_FILES["img"]["name"]);
            $strExtension = $arrPathInfo["extension"];
            list($usec, $sec) = explode(" ", microtime());
            $filename = $arrPathInfo["filename"].$usec.$sec.".".$arrPathInfo["extension"];
            $uploaddir = '../images/upload';
            $uploadfile = $uploaddir ."/". $filename;
            
            if(is_dir($uploaddir)){
                if(!chmod($uploaddir,0777)){
                    $intBool = chmod($uploaddir, 0777);
                    if(!$intBool){
                        $strErrormessage .= "Please give premission to the upload folder in images folder as 777.<br>";
                        $intError = 0;
                    }
                }  
            }
            
            if($_FILES['img']['error'] == 0){
                if($strExtension == "jpg" || $strExtension == "jpeg" || $strExtension == "gif" || $strExtension == "png"){
                    if (move_uploaded_file($_FILES['img']['tmp_name'], $uploadfile)) {
                        if($width>150){
                           $image = new SimpleImage();
                           $image->load($uploadfile);
                           $image->resizeToWidth(150);
                           $image->save($uploadfile);
                        }
                        $strfilename = $filename;
                    }
                } else {
                    $strErrormessage .= "Please upload only gif, jpg or png image.<br>";
                    $intError = 0;
                }
            }
            
        }
        if($intError){
            foreach($_POST as $key => $value){
                $_POST[$key] = mysql_escape_string(trim($value));
            }
            $sql = "INSERT INTO tbltechniques(id,title,sdesc,ldesc,img,torder,dtcreated) VALUES('','".$_POST["title"]."','".$_POST["sdesc"]."','".$_POST["ldesc"]."','".$filename."','".$_POST["torder"]."','".date("Y-m-d H:i:s")."')";
            mysql_query($sql) or die(mysql_error());
            header("Location:message.php?mess=11");
        }
    }
    if(!$intError){
        $hdlTpl->setVariable("error_message",$strErrormessage);//Assigns values
    }
    
    foreach($_POST as $key => $value){
         $hdlTpl->setVariable($key,$value);//Assigns values
    }
     
    $hdlTpl->parse("ADMIN_INNER_PAGE");
    $hdlTpl->parse("INNER_PAGE");
    echo $hdlTpl->get();
    
?>