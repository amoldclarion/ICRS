<?php
    /**
    * @version addmodule.php 2009-07-09 $
    * @copyright Copyright (C) icrs.com. All rights reserved.
    *
    * @author Amol Divalkar
    * @Modified By 
    * @version 1.0 
    * @desc This is main file (addmodule) which is used to render addmodule page for the project
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
    $hdlTpl->loadTemplateFile("addmodule.htm",TRUE,TRUE); 
      
    $strHeader = $hldGlobal->fnGetHeader();//Fetches header template
    $strMetatag = $hldGlobal->fnGetMetatag();//Fetches Metatag template
    $strFooter = $hldGlobal->fnGetFooter();//Fetches footer template
    $strLeftMenu = $hldGlobal->fnGetLMenu();//Fetches left menu template
    $strOption = $hldGlobal->fnGetCource($_POST["course_id"]);//Fetches options

    $hdlTpl->setVariable("metatag",$strMetatag);//Assigns Metatag
    $hdlTpl->setVariable("header",$strHeader);//Assigns Header 
    $hdlTpl->setVariable("footer",$strFooter);//Assigns footer
    $hdlTpl->setVariable("leftmenu",$strLeftMenu);//Assigns left menu
    
    if($_POST["submit"] == "Add Module"){
        $strErrormessage = "";
        $intError = 1;
        
        if(trim($_POST["course_id"]) == "--"){
            $strErrormessage .= "Please select cources name.<br>";
            $intError = 0;
        }
        if(trim($_POST["mname"]) == ""){
            $strErrormessage .= "Please enter module name.<br>";
            $intError = 0;
        }        
        if(trim($_POST["fname"]) == ""){
            $strErrormessage .= "Presenter file should not be empty.<br>";
            $intError = 0;
        }
        /*if($hldGlobal->fnCheckDescription($_POST["mdescription"]) == ""){
            $strErrormessage .= "Please enter module description.<br>";
            $intError = 0;
        }*/
        if(trim($_POST["mname"]) != ""){
            if($hldGlobal->fnCheckMoudleName(addslashes(trim($_POST["mname"])),trim($_POST["course_id"]),$_POST["course_id"])){
                $strErrormessage .= "Module name already added.<br>";
                $intError = 0;
            }
        } 
        
        if($hldGlobal->fnCheckFname(trim($_POST["fname"]))){
            $strErrormessage .= "Presenter file name already present.<br>";
            $intError = 0;
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
                if($strExtension == "jpg" || $strExtension == "jpeg" || $strExtension == "gif" || $strExtension == "png"){
                    if (move_uploaded_file($_FILES['img']['tmp_name'], $uploadfile)) {
                        if($width>100){
                           $image = new SimpleImage();
                           $image->load($uploadfile);
                           $image->resizeToWidth(100);
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
            $sql = "INSERT INTO tblmodule(id,course_id,page_name,mname,by_whom,mdescription,img_path,morder,fname,dtcreated) VALUES('','".$_POST["course_id"]."','".$_POST["page_name"]."','".$_POST["mname"]."','".$_POST["by_whom"]."','".$_POST["mdescription"]."','".$strfilename."','".$_POST["morder"]."','".$_POST["fname"]."','".date("Y-m-d H:i:s")."')";
            mysql_query($sql);                                              
            $intAutoinc = $hldGlobal->fnGetLastInsertId();
            $sTestString = $_POST["fname"];
            $sPattern = '/\s/';
            $sReplace = '_';
            $strModuleName = preg_replace( $sPattern, $sReplace, $sTestString );
            $strPathName = $strModuleName;
            if(!is_dir("../presenterFiles/$strPathName")){
                @mkdir("../presenterFiles/$strPathName",0777);
            }
            header("Location:message.php?mess=5");
        }
    }
    if(!$intError){
        $hdlTpl->setVariable("error_message",$strErrormessage);//Assigns values
    }
    $hdlTpl->setVariable("option",$strOption);//Assigns option
    foreach($_POST as $key => $value){
         $hdlTpl->setVariable($key,$value);//Assigns values
    }
    
    if(is_array($arrPage) && count($arrPage) > 0){
        $strOpt = "";
        foreach($arrPage as $key => $value){
            if($_POST["page_name"] == $key){
                $strSel = "selected='selected'";
            } else {
                $strSel = "";
            }
            $strOpt .= "<option value='".$key."' ".$strSel.">".$value."</option>";
        }
        $hdlTpl->setVariable("optpagename",$strOpt);//Assigns option
    }
     
    $hdlTpl->parse("ADMIN_INNER_PAGE");
    $hdlTpl->parse("INNER_PAGE");
    echo $hdlTpl->get();
    
?>