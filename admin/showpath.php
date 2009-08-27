<?php
include("../includes/config.inc.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>ICRS Module folder path</title>
<link rel="stylesheet" href="css/style.css" type="text/css"></link>
</head>
<body>
<table width="100%" cellpadding="3" cellspacing="3">
 <tr>
   <td style="font-family:Verdana; font-size:10px; font-weight:bold; width:100px;">Folder Path : </td>
   <td style="font-family:Verdana; font-size:10px;">
   <?php
        $sql = "SELECT id,fname FROM tblmodule WHERE id=".$_GET["id"];
        $rs = mysql_query($sql);
        $rw = mysql_fetch_array($rs);
        $sTestString = $rw["fname"];
        $sPattern = '/\s/';
        $sReplace = '_';
        $strModuleName = preg_replace( $sPattern, $sReplace, $sTestString );
        $strPathName = $strModuleName;
        $fname = "presenterFiles/".$strPathName;
        if(!is_dir("../$fname")){
            @mkdir("../$fname",0777);
        }
        echo $fname."/";
   ?>
   </td>
 </tr>
</table>
</body>
</html>
