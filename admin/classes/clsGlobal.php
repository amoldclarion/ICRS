<?php
/**
* @version clsGlobal 2009-07-09 $
* @copyright Copyright (C) icrs.com. All rights reserved.
*
* @author Amol Divalkar
* @Modified By 
* @version 1.0 
* @desc This class is global class which have all common functions
*/
class clsGlobal {
    
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
    function __construct($hdlConfDB){
        /**
        * Assigns database object to class private variable
        */
        $this->hdlDb = $hdlConfDB;
    }

    /**
    * @desc Function fnDBUpdate - This function will execute the update query
    * @param string $strSql
    * @return array
    */
    function fnDBUpdate($strSql) {
        //fire a SQL on default connection
        $arrResult =& $this->hdlDb->query($strSql);
        if (DB::isError ($arrResult)) {
            print $arrResult->getDebugInfo() ."<br>";
            die ("Failed: " . $arrResult->getMessage () . "\n");
        }
    }

    /**
    * @desc Function fnDBUpdateCon - This function is used to query the connection handle pased as a parameter.
    * @param string $strSql, string $hdlConfDB
    * @return array
    */   
    function fnDBUpdateCon($strSql,$hdlConfDB) {   // fire a SQL on default connection
        $arrResult =& $this->hdlDb->query($strSql);
        if (DB::isError ($arrResult)) {
            print $arrResult->getDebugInfo() ."<br>";
            die ("Failed: " . $arrResult->getMessage () . "\n");
        }
    }

    /**
    * @desc Function fnDBFetch - This function is used to query the default database.
    * @param string $strSql, string $hdlDb
    * @return array
    */   
    function fnDBFetch($strSql,$hdlDb) { 
        $arrResult =&  $hdlDb->getAll($strSql,DB_FETCHMODE_ASSOC);
        if (DB::isError ($arrResult)) {
            print $arrResult->getDebugInfo() ."<br>";
            die ("Failed: " . $arrResult->getMessage () . "\n");
        }
        //return the result set
        return $arrResult;
    }

    /**
    * @desc Function fnDBFetchCon - This function is used to query the connection handle pased as a parameter.
    * @param string $strSql, string $hdlConfDB
    * @return array
    */
    function fnDBFetchCon($strSql,$hdlConfDB) {
        $arrResult =&  $this->hdlDb->getAll($strSql,DB_FETCHMODE_ASSOC);
        if (DB::isError ($arrResult)) {
            print $arrResult->getDebugInfo() ."<br>";
            die ("Failed: " . $arrResult->getMessage () . "\n");
        }
        return $arrResult;    //return the result set
    }

    /**
    * @desc Function fnDBFetchCon - This function returns the last inserted id for the current insertion record in database.
    * @param NULL
    * @return integer
    */
    function fnGetLastInsertId() {
        $intLastId=mysql_insert_id();
        return $intLastId;
    }

    /**
    * @desc Function fnGetHeader - This function will get header from the template (tpl) folder
    * @param NULL
    * @return HTML
    */
    function fnGetHeader() {
        $hdlTpl1 =& new HTML_Template_Sigma( DIR_ADMIN_TEMPLATE , DIR_ADMIN_TEMPLATE."/prepared");
        $hdlTpl1->loadTemplateFile("header.htm",TRUE,TRUE);    
        $hdlTpl1->setVariable('admin_heading',"Admin ICRS"); 
        $hdlTpl1->parse("__HEADER__");     
        return $hdlTpl1->get();
    }

    /**
    * @desc Function fnGetFooter - This function will get footer from the template (tpl) folder
    * @param NULL
    * @return HTML
    */
    function fnGetFooter($intCid=0,$intQid=0) {
        $hdlTpl1 =& new HTML_Template_Sigma( DIR_ADMIN_TEMPLATE , DIR_ADMIN_TEMPLATE."/prepared");
        $hdlTpl1->loadTemplateFile("footer.htm",TRUE,TRUE);    
        $hdlTpl1->setVariable('tdate',date("Y")); 
        if($intCid && $intQid){
            $strSel = '';
            $strSel .= '<select name="module_id" id="module_id">';
            $strSel .= '<option value="--">--Select module name--</option>';
            $strModuleOption =  $this->fnGetModuleOption($intCid);
            $strSel .= $strModuleOption;
            $strSel .= '</select>';
            $hdlTpl1->setVariable("editid",$intQid);//Assigns hidden id
            $hdlTpl1->setVariable("qid",$intQid);
            $hdlTpl1->setVariable("selmodule",$strSel);//Assigns module drop down
        } else {
            $hdlTpl1->setVariable("qid",0);
        }
        $hdlTpl1->parse("__FOOTER__");
        return $hdlTpl1->get();
    } 

    /**
    * @desc Function fnGetLMenu - This function will get left menu from the template (tpl) folder
    * @param NULL
    * @return HTML
    */
    function fnGetLMenu() {
        $hdlTpl1 =& new HTML_Template_Sigma( DIR_ADMIN_TEMPLATE , DIR_ADMIN_TEMPLATE."/prepared");
        $hdlTpl1->loadTemplateFile("leftmenu.htm",TRUE,TRUE);    
        $hdlTpl1->setVariable('left','&nbsp;'); 
        $hdlTpl1->parse("LEFT_MENU");
        return $hdlTpl1->get();
    }

    /**
    * @desc Function fnGetMetatag - This function will get metatag from the template (tpl) folder
    * @param NULL
    * @return HTML
    */
    function fnGetMetatag() {
        $hdlTpl1 =& new HTML_Template_Sigma( DIR_ADMIN_TEMPLATE , DIR_ADMIN_TEMPLATE."/prepared");
        $hdlTpl1->loadTemplateFile("metatag.htm",TRUE,TRUE);    
        $hdlTpl1->setVariable('space','&nbsp;'); 
        $hdlTpl1->parse("__METATAG__");
        return $hdlTpl1->get();
    }

    /**
    * @desc Function fnEncodeURL - This function will get the url and will encode the url.
    * @param string $url
    * @return string
    */
    function fnEncodeURL($url){
        $url = ereg_replace("&","-",$url);
        return $url;
    }

    /**
    * @desc Function fnDecodeURL - This function will get the url and will decode the url.
    * @param string $url
    * @return string
    */
    function fnDecodeURL($url){
        $url = ereg_replace("-","&",$url);
        return $url;
    }               
    
    /**
    * @desc Function fnFetchCources - This function will fetch all the cources from tblcources table.
    * @param integer $intId
    * @return array
    */
    function fnFetchCources($intId=0,$strSearch=""){
        if($intId){
            $sqlWhere = " WHERE id=".$intId;
        }
        if($strSearch != ""){
            $sqlWhere = " WHERE isactive='".$strSearch."'";
        }
        $sql = "SELECT * FROM tblcources ".$sqlWhere." ORDER BY id DESC";
        $arrCources = $this->fnDBFetch($sql,$this->hdlDb);
        if(is_array($arrCources) && count($arrCources) > 0){
            return $arrCources;
        } else {
            return 0;
        }
    }
    
    function fnFetchContact($intId=0,$strSearch=""){
        $sqlWhere = '';
        if($intId){
            $sqlWhere = " WHERE id=".$intId;
        }
        if($strSearch != ""){
            $sqlWhere = " WHERE (name like '%".$strSearch."%' OR email like '%".$strSearch."%')";
        }
        $sql = "SELECT * FROM tblContact ".$sqlWhere." ORDER BY id DESC";
        $arrCources = $this->fnDBFetch($sql,$this->hdlDb);
        if(is_array($arrCources) && count($arrCources) > 0){
            return $arrCources;
        } else {
            return 0;
        }
    }
    
    /**
    * @desc Function fnGetCourcesName - This function will fetch cource name from tblcources table.
    * @param integer $intId
    * @return array
    */
    function fnGetCourcesName($course_id){
        $sql = "SELECT cname FROM tblcources WHERE id=".$course_id." ORDER BY id DESC";
        $arrCources = $this->fnDBFetch($sql,$this->hdlDb);
        if(is_array($arrCources) && count($arrCources) > 0){
            return $arrCources[0]["cname"];
        } else {
            return "--";
        }
    }
    
    /**
    * @desc Function fnGetCource - This function will fetch all the cources name from tblcources table.
    * @param integer $intoption
    * @return string
    */
    function fnGetCource($intoption = 0){
        $sql = "SELECT id,cname FROM tblcources WHERE isactive='1' ORDER BY id DESC";
        $arrcname = $this->fnDBFetch($sql,$this->hdlDb);
        if(is_array($arrcname) && count($arrcname) > 0){
            $stroption = '';
            foreach($arrcname as $key => $value){
                if($intoption == $value["id"]){
                    $sel = "selected='selected'";
                } else {
                    $sel = "";
                }
                $stroption .= '<option value="'.$value["id"].'" '.$sel.'>'.$value["cname"].'</option>';
            }
            return $stroption;
        } else {
            return 0;
        }
    }
    
    /**
    * @desc Function fnGetModule - This function will fetch all the Module name from tblmodule table.
    * @param integer $intoption
    * @return string
    */
    function fnGetModule($intoption = 0){
        $sql = "SELECT tm.id,tm.mname,tc.cname FROM tblmodule tm,tblcources tc WHERE tm.isactive='1' AND tm.course_id = tc.id ORDER BY tm.id DESC";
        $arrcname = $this->fnDBFetch($sql,$this->hdlDb);
        if(is_array($arrcname) && count($arrcname) > 0){
            $stroption = '';
            foreach($arrcname as $key => $value){
                if($intoption == $value["id"]){
                    $sel = "selected='selected'";
                } else {
                    $sel = "";
                }
                $stroption .= '<option value="'.$value["id"].'" '.$sel.'>'.$value["cname"].'-->'.$value["mname"].'</option>';
            }
            return $stroption;
        } else {
            return 0;
        }
    }
    
    
    /**
    * @desc Function fnGetModuleOption - This function will fetch all the Module name from tblmodule table.
    * @param integer $intoption,$intMid
    * @return string
    */
    function fnGetModuleOption($intoption,$intMid = 0){
        $sql = "SELECT tm.id,tm.mname FROM tblmodule tm WHERE tm.isactive='1' AND tm.course_id = ".$intoption." ORDER BY tm.id DESC";
        $arrcname = $this->fnDBFetch($sql,$this->hdlDb);
        if(is_array($arrcname) && count($arrcname) > 0){
            $stroption = '';
            $sel = '';
            foreach($arrcname as $key => $value){
                if($intMid){
                    if($intMid == $value["id"]){
                        $sel = "selected='selected'";
                    } else {
                        $sel = '';
                    }
                }
                $stroption .= '<option value="'.$value["id"].'" '.$sel.'>'.$value["mname"].'</option>';
            }
            return $stroption;
        } else {
            return 0;
        }
    }
    
    
    /**
    * @desc Function fnGetCMName - This function will fetch modules name and cource name from tblmodule and tblcources table.
    * @param integer $intId
    * @return array
    */
    function fnGetCMName($intId){
        $sql = "SELECT c.cname,quiz_title FROM tblQuizTitle
                INNER JOIN tblcources c ON c.id=course_id";
        $arrCMName = $this->fnDBFetch($sql,$this->hdlDb);
        if(is_array($arrCMName) && count($arrCMName)){
            return $arrCMName;
        } else {
            return 0;
        }
    }
    
    /**
    * @desc Function fnGetCountAnswer - This function will fetch count from tblquizzesoptions table and will pass to for editing.
    * @param integer $intQid
    * @return array
    */
    function fnGetCountAnswer($intQid){
        $sql = "SELECT id FROM tblquizzesoptions WHERE quizzes_id=".$intQid;
        $arrCount = $this->fnDBFetch($sql,$this->hdlDb);
        if(is_array($arrCount) && count($arrCount)){
            return $arrCount;
        } else {
            return 0;
        }
    }
    
    /**
    * @desc Function fnFetchQuizzes - This function will fetch all the question from tblquizzes table.
    * @param integer $intId , string $strSearch
    * @return array
    */
    function fnFetchQuizzes($intId = 0, $strSearch=""){
        if($intId){
            $sqlWhere = " AND id=".$intId;
        }
        if($strSearch != ""){
            $sqlWhere = " AND course_id='".$strSearch."'";
        }
        $sql = "SELECT * FROM tblQuizTitle WHERE to_show='1' ".$sqlWhere." ORDER BY id DESC";
        $arrQuizzes = $this->fnDBFetch($sql,$this->hdlDb);
        if(is_array($arrQuizzes) && count($arrQuizzes)){
            return $arrQuizzes;
        } else {
            return 0;
        }
    }
    
    /**
    * @desc Function fnCheckQuestion - This function will check the question in the question table for same module.
    * @param integer $mid, string $strQuestion
    * @return integer
    */
    function fnCheckQuestion($mid,$strQuestion,$id=0){
        if($id){
            $sqlWhere = " AND id!= ".$id;
        }
        $sql = "SELECT COUNT(*) as cnt FROM tblquizzes WHERE module_id='".$mid."' AND quizzes_question='".trim($strQuestion)."' ".$sqlWhere." ORDER BY id DESC";
        $arrQuizzes = $this->fnDBFetch($sql,$this->hdlDb);
        if(is_array($arrQuizzes) && count($arrQuizzes)){
            return $arrQuizzes[0]["cnt"];
        } else {
            return 0;
        }
    }
    
    /**
    * @desc Function fnFetchModule - This function will fetch all the modules from tblmodule table.
    * @param integer $intId,$int string $strSearch
    * @return array
    */
    function fnFetchModule($intId=0,$strSearch="",$int){
        if($intId){
            $sqlWhere = " WHERE tm.id=".$intId;
        }
        if($strSearch != ""){
            if($int == 1){
                $sqlWhere = " WHERE tm.isactive='".$strSearch."'";
            } else if($int == 2){
                $sqlWhere = " WHERE tm.page_name='".$strSearch."'";
            }
        }
        $sql = "SELECT tm.*,tc.cname FROM tblmodule tm INNER JOIN tblcources tc on tc.id=tm.course_id ".$sqlWhere." ORDER BY tm.id DESC";
        $arrCources = $this->fnDBFetch($sql,$this->hdlDb);
        if(is_array($arrCources) && count($arrCources) > 0){
            return $arrCources;
        } else {
            return 0;
        }
    }    
    
    /**
    * @desc Function fnFetchAnswer - This function will fetch all the answer from tblquizzesoptions table.
    * @param integer $intId
    * @return array
    */
    function fnFetchAnswer($intId){
        $sql = "SELECT * FROM tblquizzesoptions WHERE quizzes_id=".$intId;
        $arrAnswer = $this->fnDBFetch($sql,$this->hdlDb);
        if(is_array($arrAnswer) && count($arrAnswer) > 0){
            return $arrAnswer;
        } else {
            return 0;
        }
    }
    
    /**
    * @desc Function fnCheckMoudleName - This function will check all the module names present in the tblmodule table.
    * @param string $strName, integer $intcId, integer $intId
    * @return array
    */
    function fnCheckMoudleName($strName, $intcId=0, $intId = 0){
        $sqlWhere="";
        if($intId){
            $sqlWhere .= " AND id!=".$intId;
        }
        if($intcId){
            $sqlWhere .= " AND course_id=".$intcId;
        }
        $sql = "SELECT COUNT(*) AS cnt FROM tblmodule WHERE mname='".$strName."' ".$sqlWhere;
        $arrModule = $this->fnDBFetch($sql,$this->hdlDb);
        if(is_array($arrModule) && count($arrModule) > 0){
            return $arrModule[0]["cnt"];
        } else {
            return 0;
        }
    }
    /**
    * @desc Function fnCheckFname - This function will check all the presenter file name already present in our database or not
    * @param string $strFname
    * @return integer
    */
    function fnCheckFname($strFname,$id=0){
        if($id){
            $sqlWhere = " AND id!=".$id;
        }
        $sql = "SELECT COUNT(*) AS cnt FROM tblmodule WHERE fname='".addslashes($strFname)."' ".$sqlWhere;
        $arrResult = $this->fnDBFetch($sql,$this->hdlDb);
        if(is_array($arrResult) && count($arrResult) > 0){
            return $arrResult[0]["cnt"];
        } else {
            return 0;
        }
    }
    
    /**
    * @desc Function fnCheckDescription - This function will check and remove all the blank space and other HTML tags
    * @param string $strDescription
    * @return string $strDescription
    */
    function fnCheckDescription($strDescription){
        return trim(strip_tags(str_replace('&nbsp;',"",$strDescription)));
    }
    
    /**
    * @desc Function fnCheckTechnique - This function will check if the teaching technique is already present or not
    * @param string $strTitle
    * @return integer
    */
    function fnCheckTechnique($strTitle,$intId = 0){
        if($intId){
            $sqlWhere = " AND id!=".$intId;
        }
        $sql = "SELECT COUNT(*) AS cnt FROM tbltechniques WHERE title='".$strTitle."'".$sqlWhere;
        $arrTech = $this->fnDBFetch($sql,$this->hdlDb);
        if(is_array($arrTech) && count($arrTech) > 0){
           return $arrTech[0]["cnt"];
        } else {
            return 0;
        }
    }
    
    /**
    * @desc Function fnFetchTechnique - This function will fetch all the teaching techniqye from the table and will display on the page.
    * @param string $strSearch, integer $intId
    * @return array
    */
    function fnFetchTechnique($intId=0,$strSearch=""){
        if($intId){
            $sqlWhere = " WHERE id=".$intId;
        }
        if($strSearch != ""){
            $sqlWhere = " WHERE isactive='".$strSearch."'";
        }
        $sql = "SELECT * FROM tbltechniques ".$sqlWhere." ORDER BY id DESC";
        $arrTech = $this->fnDBFetch($sql,$this->hdlDb);
        if(is_array($arrTech) && count($arrTech) > 0){
           return $arrTech;
        } else {
            return 0;
        }
    }
    
    /**
    * @desc Function fnFetchManageusers - This function will fetch all the news.
    * @param integer $intId,string $strSearch
    * @return array
    */
    function fnFetchManagenews($intId=0,$strSearch=""){
        if($intId){
            $strWhere = " WHERE id=".$intId;
        }
        if($strSearch != ""){
            $strWhere = " WHERE isActive='".$strSearch."'";
        }
        $sqlUpd = "SELECT * FROM tblnews $strWhere ORDER BY id DESC";
        $arrResult = $this->fnDBFetch($sqlUpd,$this->hdlDb);
        if(is_array($arrResult) && count($arrResult) > 0){
            return $arrResult;
        } else {
            $intCnt = 0;
            return $intCnt;
        }
    }
    
    /**
    * @desc Function fnCheckEmail - This function will check if email address already present or not.
    * @param integer $intId,string $strSearch
    * @return array
    */
    function fnCheckEmail($strEmail,$intUserId){
        $sqlUser = "SELECT COUNT(id) as CNT FROM tbluser WHERE isactive='1' AND email='".$strEmail."' AND  id!=".$intUserId;
        $arrUserResult = $this->fnDBFetch($sqlUser,$this->hdlDb);
        
        if(is_array($arrUserResult) && count($arrUserResult) > 0){
            return $arrUserResult[0]["CNT"];
        } else {
            return 0;
        }
    }
    
    /**
    * @desc Function fnCheckUser - This function will check the user.
    * @param string $strUname, integer $intId
    * @return array
    */
    function fnCheckUser($strUname='',$intId = 0){
        if($intId){
            $sqlCondition = " AND id !='".$intId."'";
        }
        $sqlUname = "SELECT COUNT(id) AS cnt FROM tbluser WHERE username='".$strUname."' $sqlCondition";
        $arrCheck = $this->fnDBFetch($sqlUname,$this->hdlDb);
        if(is_array($arrCheck) && count($arrCheck)>0){
            return $arrCheck;
        } else {
            return 0;
        }
    }
    
    /**
    * @desc Function fnFetchManageusers - This function will fetch all the users.
    * @param integer $intId,string $strSearch
    * @return array
    */
    function fnFetchManageusers($intId=0,$strSearch=""){
        if($intId){
            $strWhere = " WHERE id=".$intId;
        }
        if($strSearch != ""){
            $strWhere = " WHERE isactive='".$strSearch."'";
        }
        $sqlUpd = "SELECT * FROM tbluser $strWhere ORDER BY id DESC";
        $arrResult = $this->fnDBFetch($sqlUpd,$this->hdlDb);
        if(is_array($arrResult) && count($arrResult) > 0){
            return $arrResult;
        } else {
            $intCnt = 0;
            return $intCnt;
        }
    }
    
    /**
    * @desc Function fnCheckPassword - This function will check if the password exits or not.
    * @param integer $intId,string $strOldPass
    * @return integer
    */
    function fnCheckPassword($strOldPass,$intId){
        $sqlQuery = "SELECT COUNT(id) AS cnt FROM tbluser WHERE id='".$intId."' AND password='".md5($strOldPass)."'";
        $arrResult = $this->fnDBFetch($sqlQuery,$this->hdlDb);
        if(is_array($arrResult) && count($arrResult) > 0){
            return $arrResult[0]["cnt"];
        } else {
            $intCnt = 0;
            return $intCnt;
        }
    }
    
    /**
    * @desc Function fnCheckTitle - This function will check if the title is already present or not for the particular page or not
    * @param string $strTitle,$strPageName, integer $intId
    * @return integer
    */
    function fnCheckTitle($strTitle,$strPageName,$intId = 0){
        if($intId){
            $sqlWhere = " AND id!=".$intId;
        }
        $sql = "SELECT COUNT(*) AS cnt FROM tblpages WHERE page_title='".$strTitle."' AND page_name='".$strPageName."' ".$sqlWhere;
        $arrTech = $this->fnDBFetch($sql,$this->hdlDb);
        if(is_array($arrTech) && count($arrTech) > 0){
           return $arrTech[0]["cnt"];
        } else {
            return 0;
        }
    }
    
    /**
    * @desc Function fnGetPageName - This function will return appropriate page name
    * @param string $strPageName
    * @return string
    */
    function fnGetPageName($strPageName){
        switch($strPageName){
            case 'quick_review' :
                return 'Quick Review';
                break;
            case 'case_library' :
                return 'Case Library';
                break;
            case 'innovation' :
                return 'Innovation';
                break;
            case 'advisory' :
                return 'Advisory';
                break;
        }
    }
    
    /**
    * @desc Function fnFetchPage - This function will fetch all the pages.
    * @param integer $intId,string $strSearch
    * @return array
    */
    function fnFetchPage($intId=0,$strSearch=""){
        if($intId){
            $strWhere = " WHERE id=".$intId;
        }
        if($strSearch != ""){
            $strWhere = " WHERE isactive='".$strSearch."'";
        }
        $sqlUpd = "SELECT * FROM tblpages $strWhere ORDER BY id DESC";
        $arrResult = $this->fnDBFetch($sqlUpd,$this->hdlDb);
        if(is_array($arrResult) && count($arrResult) > 0){
            return $arrResult;
        } else {
            $intCnt = 0;
            return $intCnt;
        }
    }
    
    /**
    * @desc Function fnGetStats - This function will fetch all the Statistics from tblusertrackmain table.
    * @param NULL
    * @return array
    */
    function fnGetStats($ip = 0, $dt = 0){
        $sqlUpd = "SELECT * ,SUM(UNIX_TIMESTAMP(timein)) tin, SUM(UNIX_TIMESTAMP(timeout)) tout, date_format( MAX(dateofvisit), '%D %b %Y' ) dvisited FROM tblusertrackmain GROUP BY userid DESC";
        $arrResult = $this->fnDBFetch($sqlUpd,$this->hdlDb);
        if(is_array($arrResult) && count($arrResult) > 0){
            return $arrResult;
        } else {
            return 0;
        }
    }
    
    /**
    * @desc Function fnGetStatsUser - This function will fetch all the Statistics from tblusertrackmain table.
    * @param integer $id
    * @return array
    */
    function fnGetStatsUser($id){
        $sqlUpd = "SELECT *,date_format(dateofvisit,'%d %b %Y, %h:%i %p') dvisited FROM tblusertrackmain WHERE userid =".$id." ORDER BY `id` DESC";
        $arrResult = $this->fnDBFetch($sqlUpd,$this->hdlDb);
        if(is_array($arrResult) && count($arrResult) > 0){
            return $arrResult;
        } else {
            return 0;
        }
    }
    
    /**
    * @desc Function fnViewStats - This function will fetch all the Statistics from tblusertrack table.
    * @param integer $mid
    * @return array
    */
    function fnViewStats($mid){
        $sql = "SELECT *,date_format(datecreated,'%d %b %Y, %h:%i %p') dvisited FROM tblusertrack WHERE mid=".$mid." ORDER BY `id` DESC";
        $arrResult = $this->fnDBFetch($sql,$this->hdlDb);
        if(is_array($arrResult) && count($arrResult) > 0){
            return $arrResult;
        } else {
            return 0;
        }
    }
    
    /**
    * @desc Function fnGetUser - This function will Get the user name from users table
    * @param integer $id
    * @return string
    */
    function fnGetUser($id){
        $sqlUpd = "SELECT username FROM tbluser WHERE id='".$id."' ORDER BY id DESC";
        $arrResult = $this->fnDBFetch($sqlUpd,$this->hdlDb);
        if(is_array($arrResult) && count($arrResult) > 0){
            return $arrResult[0]["username"];
        } else {
            return 0;
        }
    }
  
    /**
    * @desc Function fnGetPagerArr - This function will Get the user name from users table
    * @param integer $id
    * @return string
    */
    function fnGetPagerArr($arrRecordSet,$arrExtra){
          
          if(is_array($arrExtra)){
              $arrExtra = $arrExtra;
          } else {
              $arrExtra = "";
          }
          $intRecPrePage = 10;
          $arrPagerParam = array(
                  'itemData' => $arrRecordSet,
                  'extraVars' => $arrExtra,
                  'perPage' => $intRecPrePage,
                  'delta' => 10, 
                  'urlVar' => 'pageid',
                  'linkClass' =>'maintext',
                  'append' => true,
                  'clearIfVoid' => false,
                  'useSessions' => true,
                  'closeSession' => true,
                  'fileName'  => '%d.htm'
                   );
          
        $objPager = & Pager::factory($arrPagerParam);
        $intTotalRecords = $objPager->numItems();
        $arrPagerData = $objPager->getPageData();
        $strPaginationLinks = $objPager->getLinks();
        
        $intPagerPageId = ($_REQUEST['pageid'] == 1 OR $_REQUEST['pageid'] == '') ? 1 : $_REQUEST['pageid'];
        $intStartRec = ($intPagerPageId == 1 ) ? 1 : ($intPagerPageId * $intRecPrePage) - ($intRecPrePage-1); 
        $intLastRec =($intTotalRecords > $intPagerPageId * $intRecPrePage) ? ($intPagerPageId * $intRecPrePage) : $intTotalRecords; 
                
        $strPaginationText = " ".$strPaginationLinks["all"]." Displaying <b>$intStartRec</b> - <b>$intLastRec</b> of <b>$intTotalRecords</b> for ".$intCnt." matches"; 
        if($arrRecordSet != 0){
            $arrPagerData["paging"] = $strPaginationText;
        }
        if(is_array($arrPagerData) && count($arrPagerData) > 0){
            return $arrPagerData;
        } else {
            return 0;
        }
   }
   
    /**
    * @desc Function sec2hms - This function will calculate seconds to hours, minutes and seconds
    * @param integer $sec, $padHours
    * @return string
    */  
    function sec2hms ($sec, $padHours = false) {
        
        $hms = "";                                  
        
        // there are 3600 seconds in an hour, so if we
        // divide total seconds by 3600 and throw away
        // the remainder, we've got the number of hours
        $hours = intval(intval($sec) / 3600);          
        
        // add to $hms, with a leading 0 if asked for  
        $hms .= ($padHours)? str_pad($hours, 2, "0", STR_PAD_LEFT). ':'   : $hours. ':';                                 
        // dividing the total seconds by 60 will give us
        // the number of minutes, but we're interested in
        // minutes past the hour: to get that, we need to
        // divide by 60 again and keep the remainder     
        $minutes = intval(($sec / 60) % 60);             
        
        // then add to $hms (with a leading 0 if needed) 
        $hms .= str_pad($minutes, 2, "0", STR_PAD_LEFT). ':';
        
        // seconds are simple - just divide the total        
        // seconds by 60 and keep the remainder              
        $seconds = intval($sec % 60);                        
        
        // add to $hms, again with a leading 0 if needed     
        $hms .= str_pad($seconds, 2, "0", STR_PAD_LEFT);     
        
        return $hms;                                         
    }  
    
    /**
    * @desc Function fnGetTimeStamp - This function will return time stamp for the passed date
    * @param date $strPost,$strCurr
    * @return array
    */
    function fnGetTimeStamp($strPost,$strCurr){
        $sql = "SELECT UNIX_TIMESTAMP('".$strPost."') as posteddate, UNIX_TIMESTAMP('".$strCurr."') as currdate";
        $arrResult = $this->fnDBFetch($sql,$this->hdlDb);
        if(is_array($arrResult) && count($arrResult) > 0){
            return $arrResult;
        } else {
            return 0;
        }
    }
    
    function fnCheckTitleAjax($cid,$strTitle){
        $sql = "SELECT COUNT(id) as cnt FROM tblQuizTitle WHERE course_id='".$cid."' AND quiz_title='".$strTitle."'";
        $arrResult = $this->fnDBFetch($sql,$this->hdlDb);
        if(is_array($arrResult) && count($arrResult) > 0){
            return $arrResult[0]["cnt"];
        } else {
            return 0;
        }
    }
    
    function fnInsTemp($cid,$strques){
        $sql = "INSERT INTO tblQuizTitle(id,course_id,quiz_title,isactive,dtcreated) VALUES('','".$cid."','".$strques."','1','".date("Y-m-d H:i:s")."')";
        mysql_query($sql);
        $_SESSION["InTlAsTiD"] = $this->fnGetLastInsertId();
        return $_SESSION["InTlAsTiD"];
    }
    
    function fnCheckQuestionQuiz($mid,$strques,$qid=0,$intedt,$qtid){
        if($qid){
            $sqlWhere = " AND id!=".$qid;
        }
        if($intedt){
            $sql = "SELECT COUNT(*) as cnt FROM tblQuizQuestion WHERE module_id='".$mid."' AND question='".$strques."' AND quiz_title_id='".$qtid."' ".$sqlWhere;
        } else {
            $sql = "SELECT COUNT(*) as cnt FROM tblQuizQuestion WHERE module_id='".$mid."' AND question='".$strques."' AND quiz_title_id='".$qtid."' ".$sqlWhere;
        }
        $arrResult = $this->fnDBFetch($sql,$this->hdlDb);
        if(is_array($arrResult) && count($arrResult) > 0){
            return $arrResult[0]["cnt"];
        } else {
            return 0;
        }
    }
    
    function fnInsertDuplicate($intId){
        $sqlQuizquestion = "INSERT INTO temp_quiz_question(id,quiz_title_id,module_id,question,option_id,isactive,dtcreated) SELECT * FROM tblQuizQuestion WHERE quiz_title_id=".$intId;
        //mysql_query($sqlQuizquestion) or die(mysql_error());
        
        $sql = "SELECT id FROM tblQuizQuestion WHERE quiz_title_id=".$intId;
        $arrResult = $this->fnDBFetch($sql,$this->hdlDb);
        if(is_array($arrResult) && count($arrResult) > 0){
            foreach($arrResult as $key => $value){
               $sqlQuizoption = "INSERT INTO temp_quiz_options(id,qid,options,isactive,dtcreated) SELECT * FROM tblQuizOption WHERE qid=".$value["id"];
               //mysql_query($sqlQuizoption); 
            }
        }
    }
    
    
    function fnInsQuestionQuiz($intLastId,$mid,$strquestion,$qid=0,$intedt=0){
        if($qid){
            if(!$intedt){
                //$sqlIns = "UPDATE temp_quiz_question SET question='".$strquestion."' WHERE id=".$qid
                $sqlIns = "UPDATE tblQuizQuestion SET question='".$strquestion."' WHERE id=".$qid;   
            } else {
                $sqlIns = "UPDATE tblQuizQuestion SET question='".$strquestion."' WHERE id=".$qid;   
            }
        } else {
            //$sqlIns = "INSERT INTO temp_quiz_question(id,quiz_title_id,module_id,question,isactive,dtcreated) VALUES('','".$intLastId."','".$mid."','".$strquestion."','1','".date("Y-m-d H:i:s")."')";
            $sqlIns = "INSERT INTO tblQuizQuestion(id,quiz_title_id,module_id,question,isactive,dtcreated) VALUES('','".$intLastId."','".$mid."','".$strquestion."','1','".date("Y-m-d H:i:s")."')";
        }
        mysql_query($sqlIns);
    }
    
    function fnFetchQuestionQuiz($intLastId,$intLive=0){                              
        if(!$intLive){
            //$sql = "SELECT * FROM temp_quiz_question WHERE quiz_title_id=".$intLastId;       
            $sql = "SELECT * FROM tblQuizQuestion WHERE quiz_title_id=".$intLastId;       
        } else {
            $sql = "SELECT * FROM tblQuizQuestion WHERE quiz_title_id=".$intLastId;
        }
        
        $arrResult = $this->fnDBFetch($sql,$this->hdlDb);
        
        $strTab = '';
        $strTab .= "<table width='90%' border='0'>";
        $strTab .= '<tr>';
        $strTab .= '<td width="50%">';
        $strTab .= '<b>Question</b>';
        $strTab .= '</td>';
        $strTab .= '<td>';
        $strTab .= '<b>Add options</b>';
        $strTab .= '</td>';
        $strTab .= '<td>';
        $strTab .= '<b>Remove</b>';
        $strTab .= '</td>';
        $strTab .= '</tr>';
        if(is_array($arrResult) && count($arrResult) > 0){
            foreach($arrResult as $key => $value){
                
                //$sqlCntOpt = "SELECT COUNT(*) AS cnt FROM temp_quiz_options WHERE qid=".$value["id"];
                $sqlCntOpt = "SELECT COUNT(*) AS cnt FROM tblQuizOption WHERE qid=".$value["id"];
                $arrCntRes = $this->fnDBFetch($sqlCntOpt,$this->hdlDb);
                
                $strFname = "frm".$value["id"];
                $strDiv = "div".$value["id"];
                $strAction = "removequestion.php?id=".$value["id"];
                $strAction1 = "showoptions.php?id=".$value["id"];
                $strHowMany = "howmany".$value["id"];
                $editQuestion = "addquestion.php?eid=1&amp;qqid=".$value["id"]."&amp;mid=".$value["module_id"]."&amp;qtid=".$value["quiz_title_id"];
                
                $strTab .= '<tr>';
                $strTab .= '<td colspan="3">';
                $strTab .= '<form name="'.$strFname.'" id="'.$strFname.'" method="post">';
                $strTab .= '<table width="100%" border="0">';
                $strTab .= '<tr>';
                $strTab .= '<td width="50%">';
                $strTab .= '<table width="100%" border="0">';
                $strTab .= '<tr>';
                $strTab .= '<td>';
                $strTab .= '<div id="quz'.$value["id"].'" style="display:block;"><a href="javascript:showquestion(1,'.$value["id"].');">'.$value["question"].'</a></div>';
                $strTab .= '<div id="quz1'.$value["id"].'" style="display:none;"><textarea cols="25" rows="1" name="question" id="question">'.$value["question"].'</textarea><input type="button" name="submit" value="Save" onclick="return fnAjaxCall(\'qtitle_e_message\',\''.$editQuestion.'\',\''.$strFname.'\')" /> <input type="button" name="Cancel" value="Cancel" onclick="showquestion(2,'.$value["id"].');" /></div>';
                $strTab .= '</td>';
                $strTab .= '</tr>';
                $strTab .= '<tr>';
                $strTab .= '<td>';
                $strTab .= '<a href="javascript:popup_option(\'popUpDiv2\','.$value["id"].',0);">Show options</a>';
                $strTab .= '</td>';
                $strTab .= '</tr>';
                $strTab .= '</table>';
                $strTab .= '</td>';
                $strTab .= '<td>';
                if($arrCntRes[0]["cnt"] != 10){
                    $strTab .= '<input type="text" name="'.$strHowMany.'" id="'.$strHowMany.'" size="5" value="2" /><input type="button" name="howmany" value="Add options" onclick="popup_howmany(\'popUpDiv1\','.$value["id"].','.$arrCntRes[0]["cnt"].');" />';
                } else {
                    $strTab .= 'Maximum limit for options is 10';
                }
                $strTab .= '</td>';
                $strTab .= '<td>';
                $strTab .= '<a href="#" onclick="fnAjaxCall(\'added_question\',\''.$strAction.'\',\''.$strFname.'\')">remove</a>';
                $strTab .= '</td>';
                $strTab .= '</tr>';                
                $strTab .= '</table>';
                $strTab .= '</form>';
                $strTab .= '</td>';
                $strTab .= '</tr>';
            }
        } else {
            $strTab .= '<tr><td colspan="3" align="center" class="errorblock">No question added</td></tr>';
        }
        $strTab .= '</table>';
        return $strTab;
    }
    
    function fnDeleteQuestion($intId){
        /*$sql = "DELETE FROM temp_quiz_question WHERE id=".$intId;
        mysql_query($sql);
        
        $sql1 = "DELETE FROM temp_quiz_options WHERE qid=".$intId;
        mysql_query($sql1);*/
        
        $sql = "DELETE FROM tblQuizQuestion WHERE id=".$intId;
        mysql_query($sql);
        
        $sql1 = "DELETE FROM tblQuizOption WHERE qid=".$intId;
        mysql_query($sql1);
        
    }
    
    function fnDeleteOptions($intOpt){
        /*$sql = "DELETE FROM temp_quiz_options WHERE id=".$intOpt;
        mysql_query($sql);*/
        
        $sql = "DELETE FROM tblQuizOption WHERE id=".$intOpt;
        mysql_query($sql);
    }
    
    function fnFetchAnswerTemp($intQid){
        //$sql = "SELECT * FROM temp_quiz_options WHERE qid='".$intQid."' ORDER BY `id` DESC";
        $sql = "SELECT o. * , IF( o.id = q.option_id, q.option_id, 0 ) optid FROM tblQuizOption o LEFT JOIN tblQuizQuestion q ON q.id = o.qid WHERE qid='".$intQid."' ORDER BY `id` DESC";
        $arrResult = $this->fnDBFetch($sql,$this->hdlDb);
        
        $strFrm = "frm".$intQid;
        
        $strTab = '';
        $strTab .= "<table width='90%' border='0' align='center'>";
        $strTab .= '<tr>';
        $strTab .= '<td width="80%" colspan="2" align="center" class="errorblock"><div id="error_option"></div></td>';
        $strTab .= '</tr>';
        $strTab .= '<tr>';
        $strTab .= '<td width="80%"><b>Options</b></td>';
        $strTab .= '<td><b>Remove</b></td>';
        $strTab .= '</tr>';
        if(is_array($arrResult) && count($arrResult) > 0){
            $strTab .= "<tr><td colspan='2'><form name='Amol' id='Amol' method='post'>";        
            $strTab .= '</form>';
            $strTab .= '</td></tr>';
            foreach($arrResult as $key => $value){
                $valId = $value["id"]."~".$intQid;
                $strAction = "removeoptions.php?id=".$valId;
                $strSaveAction = "modifyoption.php?id=".$valId;
                $strForm = "form1".$value["id"];
                $strCorrectOption = "correctoption.php?id=".$valId;
                $strCheck = "";
                
                if($value["id"] == $value["optid"]){
                    $strCheck = "checked='checked'";
                } else {
                    $strCheck = "";
                }
                
                $strTab .= "<tr><td colspan='2'><form name='".$strForm."' id='".$strForm."' method='post'>";
                $strTab .= "<table width='100%' border='0' align='center'>";
                $strTab .= '<tr><td valign="top"><input type="radio" name="correctopt" id="correctopt" '.$strCheck.' onclick="fnAjaxCall(\'show_options\',\''.$strCorrectOption.'\',\''.$strForm.'\');"></td><td width="80%" valign="top">';
                $strTab .= '<div id="ans'.$value["id"].'" style="display:block;"><a href="javascript:showanswer(1,'.$value["id"].');">'.$value["options"]."</a></div>";
                $strTab .= '<div id="ans1'.$value["id"].'" style="display:none;"><textarea rows="1" cols="40" name="options'.$value["id"].'" id="options'.$value["id"].'">'.$value["options"].'</textarea><input type="button" name="save" value="Save" onclick="fnAjaxCall(\'error_option\',\''.$strSaveAction.'\',\''.$strForm.'\')"><input type="button" name="Cancel" value="Cancel" onclick="showanswer(2,'.$value["id"].');"></div>';
                $strTab .= '</td><td valign="top">';
                $strTab .= '<a href="#" onclick="fnAjaxCall_removequestion(\'show_options\',\''.$strAction.'\',\''.$strForm.'\')">remove</a>';
                $strTab .= '</td></tr>';
                $strTab .= '</table>';
                $strTab .= '</form>';
                $strTab .= '</td></tr>';        
            }
        } else {
            $strTab .= '<tr><td colspan="2" align="center" class="errorblock">No options added</td></tr>';
        }
        $strTab .= '</table>';
        return $strTab;
    }
    
    function fnCheckQuiztitle($cid,$quiztitle,$id=0){
        if($id){
            $sqlWhere = " AND id=".$id;
        }
        $sql = "SELECT COUNT(*) AS cnt FROM tblQuizTitle WHERE course_id=".$cid." AND quiz_title='".$quiztitle."' ".$sqlWhere;
        $arrResult = $this->fnDBFetch($sql,$this->hdlDb);
        if(is_array($arrResult) && count($arrResult) > 0){
            return $arrResult[0]["cnt"];
        } else {
            return 0;
        }
    }
    
    function fnGetQuizId($intTitleId){
        $sql = "SELECT GROUP_CONCAT(id) as quesId FROM tblQuizQuestion WHERE quiz_title_id=".$intTitleId;
        $arrResult = $this->fnDBFetch($sql,$this->hdlDb);
        if(is_array($arrResult) && count($arrResult) > 0){
            return $arrResult;
        } else {
            return 0;
        }
    }
    
    function fnCheckOption($id,$qid,$opt){
        $sql = "SELECT COUNT(*) as cnt FROM tblQuizOption WHERE id!='".$id."' AND qid='".$qid."' AND options='".$opt."'";
        $arrResult = $this->fnDBFetch($sql,$this->hdlDb);
        if(is_array($arrResult) && count($arrResult) > 0){
            return $arrResult[0]["cnt"];
        } else {
            return 0;
        }
    }
    
    function fnUpdateOption($id,$qid,$strAns){
        $sql = "UPDATE tblQuizOption SET options='".$strAns."' WHERE id='".$id."' AND qid='".$qid."'";
        mysql_query($sql);
    }
    
    function fnCorrectOption($id,$qid){
        $sql = "UPDATE tblQuizQuestion SET option_id='".$id."' WHERE id=".$qid;
        mysql_query($sql);
    }
    
    function fnGetQuizTitle($qtid,$cid){
        $sql = "SELECT quiz_title FROM tblQuizTitle WHERE course_id='".$cid."' AND id='".$qtid."'";
        $arrResult = $this->fnDBFetch($sql,$this->hdlDb);
        if(is_array($arrResult) && count($arrResult) > 0){
            return $arrResult[0]["quiz_title"];
        } else {
            return "--";
        }
    }
    
}
?>