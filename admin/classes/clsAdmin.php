<?php
/**
* @version clsAdmin 2009-07-09 $
* @copyright Copyright (C) icrs.com. All rights reserved.
*
* @author Amol Divalkar
* @Modified By 
* @version 1.0 
* @desc This class is admin class which have only admin related function
*/

class clsAdmin extends clsGlobal{
    
    /**
    * Takes the database object
    * @access private
    * @var object
    */
    var $hdlDb;
    
    /**
    * Constructor
    * @desc - Takes parameter as database handle
    * @param object $hdlConfDB
    */
    function __construct($hdlConfDB,$hldGlobal){
        /**
        * Assigns database object to class private variable
        */
        $this->hdlDb = $hdlConfDB;
        $this->hldGlobal = $hldGlobal;
    }
    
    /**
    * @desc Function fnAdminLogin - This function will check user name and password for admin login
    * @param string uname, string password
    * @return integer 
    */
    function fnAdminLogin($strUserName,$strPassword){
        $sqlQuery = "SELECT COUNT(id) as cnt,id FROM tbluser WHERE username='".$strUserName."' AND password='".md5($strPassword)."' AND isadmin='1' GROUP BY id";
        $arrRecordSet = $this->hldGlobal->fnDBFetch($sqlQuery,$this->hdlDb);
        if(is_array($arrRecordSet) && count($arrRecordSet) > 0){
            return $arrRecordSet[0]["id"];
        } else {
            return 0;
        }
    }
}
    
?>
