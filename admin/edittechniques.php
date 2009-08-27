<?php
    /**
    * @version edittechniques.php 2009-07-13 $
    * @copyright Copyright (C) icrs.com. All rights reserved.
    *
    * @author Amol Divalkar
    * @Modified By 
    * @version 1.0 
    * @desc This is main file (edittechniques) which is used to render edittechniques page for the project
    */
   
    //Includes config file.
    include("../includes/config.inc.php");
    //Includes global class file.
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
    $hdlTpl->loadTemplateFile("edittechniques.htm",TRUE,TRUE); 
      
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
        
        if(trim($_POST["title"]) == ""){
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
            if($hldGlobal->fnCheckTechnique(trim($_POST["title"]),$_GET["id"])){
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
                if(chmod($uploaddir,0777)){
                    $intBool = chmod($uploaddir, 0777);
                    if(!$intBool){
                        $strErrormessage .= "Please give premission to the upload folder in images folder as 777.<br>";
                        $intError = 0;
                    }
                }  
            }
            
            if($_FILES['img']['error'] == 0){
                if(strtolower($strExtension) == "jpg" || strtolower($strExtension) == "jpeg" || strtolower($strExtension) == "gif" || strtolower($strExtension) == "png"){
                    if (move_uploaded_file($_FILES['img']['tmp_name'], $uploadfile)) {
                        if($width>150){
                           $image = new SimpleImage();
                           $image->load($uploadfile);
                           $image->resizeToWidth(150);
                           $image->save($uploadfile);
                        }
                        $strfilename = $filename;
                        $sqlfilename = ",img='".$strfilename."'";
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
            $sql = "UPDATE tbltechniques SET title='".$_POST["title"]."',sdesc='".$_POST["sdesc"]."',ldesc='".$_POST["ldesc"]."',torder='".$_POST["torder"]."'".$sqlfilename." WHERE id=".$_GET["id"];
            mysql_query($sql) or die(mysql_error());
            $return = $_GET["return"];
            //header("Location:message.php?mess=12&return=$return");
        }
    }
    if(!$intError){
        $hdlTpl->setVariable("error_message",$strErrormessage);//Assigns values
    }
    $arrTechnique = $hldGlobal->fnFetchTechnique($_GET["id"]);
    if(is_array($arrTechnique) && count($arrTechnique) > 0){
        $hdlTpl->setVariable("title",$arrTechnique[0]["title"]);//Assigns title
        $hdlTpl->setVariable("sdesc",$arrTechnique[0]["sdesc"]);//Assigns sort description
        $hdlTpl->setVariable("ldesc",$arrTechnique[0]["ldesc"]);//Assigns long description
        $hdlTpl->setVariable("torder",$arrTechnique[0]["torder"]);//Assigns order
        if($arrTechnique[0]["img"] != ""){
            $hdlTpl->setVariable("imgname",$arrTechnique[0]["img"]);//Assigns order
            $hdlTpl->parse("image");
        }
        $hdlTpl->setVariable("return",$_GET["return"]);//Assigns return
        $hdlTpl->setVariable("id",$_GET["id"]);//Assigns return
    }
     
    $hdlTpl->parse("ADMIN_INNER_PAGE");
    $hdlTpl->parse("INNER_PAGE");
    echo $hdlTpl->get();
    
?>