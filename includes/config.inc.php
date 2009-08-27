<?php
/* 
Clarion PHP R & D Group

Simple process file setup
*/
session_start();


//Uncomment it whenever needed
/*ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', dirname(__FILE__) . '/error_log.txt');
error_reporting(E_ALL);*/
ini_set('display_errors', 0);
define("REC_PER_PAGE",10);
define("ADMIN_EMAIL","alex@ontologic.com");
define("SITE_NAME", "http://125.18.107.178/alex_west/ontologic_dev1/ircs/");
define("DOCUMENT_ROOT", $_SERVER["DOCUMENT_ROOT"]."/alex_west/ontologic_dev1/ircs/");
define("SESSION_TIME_OUT",30);

/*---------------- DATABASE SETTING ----------------*/

$strDriver = "mysql"; // DATABASE WHICH YOU ARE USING
$strUser = "amol.diwalkar"; //Please put your database user name here
$strPassword = "clarion"; //Please put your database password here
$strHost = "localhost"; // DB HOST
$strDb = "ontologic_icrs"; //Please put your database name here


/*------------------ SYSTEM SETTINGS ---------------*/  

ini_set("include_path", DOCUMENT_ROOT."/lib/pear");      

                    /*  Appends new path to default include path */
ini_set("implicit_flush", TRUE)    ;    /*  FALSE by default. Changing this to TRUE tells PHP to tell
                                        the output layer to flush itself automatically after every
                                        output block. This is equivalent to calling the PHP
                                        function flush() after each and every call to print() or
                                        echo() and each and every HTML block. */
ini_set("max_execution_time", 0) ;    /*  This sets the maximum time in seconds a script is allowed to
                                        run before it is terminated by the parser. This helps prevent
                                        poorly written scripts from tying up the server. The default
                                        setting is 30.
                                        The maximum execution time is not affected by system calls,
                                        the sleep() function, etc. Please see the set_time_limit()
                                        function for more details. */
/*---------------------- END -----------------------*/

require_once "DB.php"; # PEAR LIB
# DSN 

$strDSN = $strDriver."://".$strUser.":".$strPassword."@".$strHost."/".$strDb;
 
$hdlDb =& DB::connect($strDSN);

   if (DB::isError ($hdlDb)){
     die ("Cannot connect: " . $hdlDb->getMessage () . "\n"); 
   }


/*------------------------ END ----------------------*/

define("DIR_CLASSES", DOCUMENT_ROOT."/classes"); //STRUCTURE CLASSES
define("DIR_TEMPLATE", DOCUMENT_ROOT."/tpl"); //STRUCTURE TEMPLATE

define("DIR_ADMIN_CLASSES", DOCUMENT_ROOT."/admin/classes"); //STRUCTURE CLASSES
define("DIR_ADMIN_TEMPLATE", DOCUMENT_ROOT."/admin/tpl"); //STRUCTURE TEMPLATE

$Public_Key = "6LcuAQcAAAAAAOTwJGp5OaUl0RmHRES9Pus5tYoh";

$Private_Key = "6LcuAQcAAAAAAOVuWj_3iB_v-k9qLgJYyEvyo0O7";

require_once "HTML/Template/Sigma.php";
require_once "Pager/Pager.php";

$arrPage = array("curriculum"=>"Curriculum","quick_review"=>"Quick Review","case_library"=>"Case Library","innovation"=>"Innovation","advisory"=>"Advisories");

$arrMpage = array("home","curriculum","quick_review","quizzes","case_library","innovations","advisory");

$arrCPage = array("General"=>"General","Preparation"=>"Preparation","Surgery"=>"Surgery","Conclusion"=>"Conclusion");

$arrPPage = array("Valve"=>"Valve Repair","ByPass"=>"ByPass");

?>