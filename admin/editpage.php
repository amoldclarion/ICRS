<?php
    /**
    * @version editpage.php 2009-07-15 $
    * @copyright Copyright (C) icrs.com. All rights reserved.
    *
    * @author Amol Divalkar
    * @Modified By 
    * @version 1.0 
    * @desc This is main file (editpage) which is used to render editpage page for the project
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
    $hdlTpl->loadTemplateFile("editpage.htm",TRUE,TRUE); 
      
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
        
        if(trim($_POST["page_name"]) == "--"){
            $strErrormessage .= "Please enter page name.<br>";
            $intError = 0;
        }
        if(trim($_POST["page_title"]) == ""){
            $strErrormessage .= "Please enter page title.<br>";
            $intError = 0;
        }        
        if(trim($_POST["by_whom"]) == ""){
            $strErrormessage .= "Please enter By whome.<br>";
            $intError = 0;
        }
        /*if($hldGlobal->fnCheckDescription($_POST["shot_description"]) == ""){
            $strErrormessage .= "Please enter shot description.<br>";
            $intError = 0;
        }
        if($hldGlobal->fnCheckDescription($_POST["long_description"]) == ""){
            $strErrormessage .= "Please enter long description.<br>";
            $intError = 0;
        }*/
        if(trim($_POST["page_title"]) != ""){
            if($hldGlobal->fnCheckTitle(trim($_POST["page_title"]),trim($_POST["page_name"]),$_GET["id"])){
                $strErrormessage .= "Title already present for ".$hldGlobal->fnGetPageName(trim($_POST["page_name"]))." .<br>";
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
                        if($width>100){
                           $image = new SimpleImage();
                           $image->load($uploadfile);
                           $image->resizeToWidth(100);
                           $image->save($uploadfile);
                        }
                        $strfilename = $filename;
                        $sqlfilename = ",image_path='".$strfilename."'";
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
            $sql = "UPDATE tblpages SET page_name='".$_POST["page_name"]."',page_title='".$_POST["page_title"]."',by_whom='".$_POST["by_whom"]."',shot_description='".$_POST["shot_description"]."',long_description='".$_POST["long_description"]."',isorder='".$_POST["isorder"]."'".$sqlfilename." WHERE id=".$_GET["id"];
            mysql_query($sql) or die(mysql_error());
            $return = $_GET["return"];
            header("Location:message.php?mess=20&return=$return");
        }
    }
    if(!$intError){
        $hdlTpl->setVariable("error_message",$strErrormessage);//Assigns values
    }
    $arrPages = $hldGlobal->fnFetchPage($_GET["id"]);
    
    if(is_array($arrPages) && count($arrPages) > 0){
        if(is_array($arrPage) && count($arrPage) > 0){
            $strOpt = "";
            foreach($arrPage as $key => $value){
                if($arrPages[0]["page_name"] == $key){
                    $strSel = "selected='selected'";
                } else {
                    $strSel = "";
                }
                $strOpt .= "<option value='".$key."' ".$strSel.">".$value."</option>";
            }
            $hdlTpl->setVariable("optpagename",$strOpt);//Assigns option
        }
        $hdlTpl->setVariable("page_title",$arrPages[0]["page_title"]);//Assigns title
        $hdlTpl->setVariable("by_whom",$arrPages[0]["by_whom"]);//Assigns by whome
        $hdlTpl->setVariable("shot_description",$arrPages[0]["shot_description"]);//Assigns shot description
        $hdlTpl->setVariable("long_description",$arrPages[0]["long_description"]);//Assigns long description
        if($arrPages[0]["image_path"] != ""){
            $hdlTpl->setVariable("imgname",$arrPages[0]["image_path"]);//Assigns image
            $hdlTpl->parse("image");
        }
        $hdlTpl->setVariable("isorder",$arrPages[0]["isorder"]);//Assigns isorder       
        $hdlTpl->setVariable("return",$_GET["return"]);//Assigns return
        $hdlTpl->setVariable("id",$_GET["id"]);//Assigns return
    }
     
    $hdlTpl->parse("ADMIN_INNER_PAGE");
    $hdlTpl->parse("INNER_PAGE");
    echo $hdlTpl->get();
    
?>