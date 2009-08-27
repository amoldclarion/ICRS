<?php
    /**
    * @version editmodule.php 2009-07-10 $
    * @copyright Copyright (C) icrs.com. All rights reserved.
    *
    * @author Amol Divalkar
    * @Modified By 
    * @version 1.0 
    * @desc This is main file (editmodule) which is used to render editmodule.php page for the project
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
    $hdlTpl->loadTemplateFile("editmodule.htm",TRUE,TRUE); 
      
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
            if($hldGlobal->fnCheckMoudleName(trim($_POST["mname"]),$_POST["course_id"],$_GET["id"])){
                $strErrormessage .= "Module name already added.<br>";
                $intError = 0;
            }
        }
        
        if($hldGlobal->fnCheckFname(trim($_POST["fname"]),$_GET["id"])){
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
                        $sqlfilename = ",img_path='".$strfilename."'";
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
            $sqlGet = "SELECT fname FROM tblmodule WHERE id=".$_GET["id"];
            $rsGet = mysql_query($sqlGet);
            $fname = mysql_result($rsGet,0,'fname');
            $sql = "UPDATE tblmodule SET course_id='".$_POST["course_id"]."',page_name='".$_POST["page_name"]."',by_whom='".$_POST["by_whom"]."',mname='".$_POST["mname"]."',mdescription='".$_POST["mdescription"]."',morder='".$_POST["morder"]."',fname='".$_POST["fname"]."',dtmodified='".date("Y-m-d H:i:s")."'".$sqlfilename." WHERE id=".$_GET["id"];
            mysql_query($sql);
            
            $sTestString = $_POST["fname"];
            $sPattern = '/\s/';
            $sReplace = '_';
            $strModuleName = preg_replace($sPattern,$sReplace,$sTestString);
            $fname = preg_replace($sPattern,$sReplace,$fname);
            $strPathName = $strModuleName;
            if(is_dir("../presenterFiles/$fname") && $fname != ""){
                $cmd = 'mv  "../presenterFiles/'.$fname.'" "../presenterFiles/'.$strPathName.'"';
                @exec($cmd, $output, $return_val);                                                
            } else {
                @mkdir("../presenterFiles/$strPathName",0777);
            }
            
            $return = $_GET["return"];
            header("Location:message.php?mess=6&return=$return");
        }
    }
    if(!$intError){
        $hdlTpl->setVariable("error_message",$strErrormessage);//Assigns values
    }
    
    $arrModule = $hldGlobal->fnFetchModule($_GET["id"]);
    $strOption = $hldGlobal->fnGetCource($arrModule[0]["course_id"]);//Fetches options
    $hdlTpl->setVariable("option",$strOption);//Assigns option
    if(is_array($arrPage) && count($arrPage) > 0){
        $strOpt = "";
        foreach($arrPage as $key => $value){
            if($arrModule[0]["page_name"] == $key){
                $strSel = "selected='selected'";
            } else {
                $strSel = "";
            }
            $strOpt .= "<option value='".$key."' ".$strSel.">".$value."</option>";
        }
        $hdlTpl->setVariable("optpagename",$strOpt);//Assigns option
    }
    foreach($arrModule as $key => $value){
         $hdlTpl->setVariable("mname",$value["mname"]);//Assigns mname
         $hdlTpl->setVariable("mdescription",$value["mdescription"]);//Assigns mdescription
         $hdlTpl->setVariable("morder",$value["morder"]);//Assigns morder
         $hdlTpl->setVariable("fname",$value["fname"]);//Assigns presenter file name
         $hdlTpl->setVariable("by_whom",$value["by_whom"]);//Assigns By whome
         if($value["img_path"] != ""){
            $hdlTpl->setVariable("imgname",$value["img_path"]);//Assigns image
            $hdlTpl->parse("image");
        }
         $hdlTpl->setVariable("id",$value["id"]);//Assigns id
         $hdlTpl->setVariable("return",$_GET["return"]);//Assigns return
    }
     
    $hdlTpl->parse("ADMIN_INNER_PAGE");
    $hdlTpl->parse("INNER_PAGE");
    echo $hdlTpl->get();
    
?>