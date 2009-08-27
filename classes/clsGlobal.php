<?php
/**
* @version clsGlobal 2009-07-15 $
* @copyright Copyright (C) ircs.com. All rights reserved.
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
    
    function fnDBUpdate($strSql) {
  //fire a SQL on default connection
  $arrResult =& $this->hdlDb->query($strSql);
   if (DB::isError ($arrResult))    {
    print $arrResult->getDebugInfo() ."<br>";
    die ("Failed: " . $arrResult->getMessage () . "\n");
   }
 }

 /*    Params:
  @strSql : Sql Query String
  @hdlDb  : Object of Database Connection
  Desc    : This function is used to query the connection handle pased as a parameter.     */
  function fnDBUpdateCon($strSql,$hdlConfDB) {   // fire a SQL on default connection
   $arrResult =& $this->hdlDb->query($strSql);
    if (DB::isError ($arrResult)) {
      print $arrResult->getDebugInfo() ."<br>";
      die ("Failed: " . $arrResult->getMessage () . "\n");
    }
  }

  /* Params:
       @strSql : SQL Query String
     Desc : This function is used to query the default database. */
  function fnDBFetch($strSql,$hdlDb) {
   
   $arrResult =&  $hdlDb->getAll($strSql,DB_FETCHMODE_ASSOC);
   
   if (DB::isError ($arrResult)) {
     print $arrResult->getDebugInfo() ."<br>";
     die ("Failed: " . $arrResult->getMessage () . "\n");
   }
   //return the result set
    return $arrResult;
  }

 /*    Params:
    @strSql : SQL Query String
    @hdlDb  : Object of Database Connection
    Desc : This function is used to query the connection handle pased as a parameter. */
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
    function fnGetHeader($strName)
    {
        $hdlTpl1 =& new HTML_Template_Sigma( DIR_TEMPLATE , DIR_TEMPLATE."/prepared");
        $hdlTpl1->loadTemplateFile("header.htm",TRUE,TRUE);    
        $hdlTpl1->setVariable("admin_heading","&nbsp;");
        if($_SESSION["UID"] && $_SESSION["UNAME"]){
            $hdlTpl1->setVariable("uname",ucfirst($_SESSION["UNAME"]));
            $hdlTpl1->parse("login");
        } else {
            $hdlTpl1->setVariable("nospace","");
            $hdlTpl1->parse("nologin");
        }
        $hdlTpl1->setVariable("site_name",SITE_NAME);
        $hdlTpl1->parse("__HEADER__");     
        return $hdlTpl1->get();
    }
    
    /**
    * @desc Function fnGetFooter - This function will get footer from the template (tpl) folder
    * @param NULL
    * @return HTML
    */
    function fnGetFooter()
    {
        $hdlTpl1 =& new HTML_Template_Sigma( DIR_TEMPLATE , DIR_TEMPLATE."/prepared");
        $hdlTpl1->loadTemplateFile("footer.htm",TRUE,TRUE);    
        $hdlTpl1->setVariable("tdate",date("Y")); 
        $hdlTpl1->setVariable("site_name",SITE_NAME);
        $hdlTpl1->parse("__FOOTER__");
        return $hdlTpl1->get();
    }
    
    /**
    * @desc Function fnMetaTag - This function will get metatag from the template (tpl) folder
    * @param NULL
    * @return HTML
    */
    function fnMetaTag()
    {
        $hdlTpl1 =& new HTML_Template_Sigma( DIR_TEMPLATE , DIR_TEMPLATE."/prepared");
        $hdlTpl1->loadTemplateFile("metatag.htm",TRUE,TRUE);
        $hdlTpl1->setVariable("site_name",SITE_NAME);
        $hdlTpl1->setVariable("space","&nbsp;"); 
        $hdlTpl1->parse("__METATAG__");
        return $hdlTpl1->get();
    } 
    
    /**
    * @desc Function fnMenu - This function will get metatag from the template (tpl) folder
    * @param string $strName
    * @return HTML
    */
    function fnMenu($strName = "",$arrMpage = "")
    {
        $hdlTpl1 =& new HTML_Template_Sigma( DIR_TEMPLATE , DIR_TEMPLATE."/prepared");
        $hdlTpl1->loadTemplateFile("menu.htm",TRUE,TRUE); 
        if(is_array($arrMpage) && count($arrMpage) > 0){
            foreach($arrMpage as $key => $value){
                if($value == $strName){  
                    $hdlTpl1->setVariable($strName,"menuon");    
                } else {
                    $hdlTpl1->setVariable($value,"headerButton");    
                }
            }
        }
        $hdlTpl1->setVariable("site_name",SITE_NAME);    
        $hdlTpl1->parse("__MENU__");
        return $hdlTpl1->get();
    }
    
    /**
    * @desc Function fnGetSearch - This function will get search from the template (tpl) folder
    * @param string $strSearch, $strPagename, $strAction
    * @return HTML
    */
    function fnGetSearch($strSearch,$strPagename,$actionname,$strname)
    {
        $hdlTpl1 =& new HTML_Template_Sigma( DIR_TEMPLATE , DIR_TEMPLATE."/prepared");
        $hdlTpl1->loadTemplateFile("search.htm",TRUE,TRUE);  
        $hdlTpl1->setVariable("page_name",$strPagename);//Assigns Page title
        $hdlTpl1->setVariable("action_name",$actionname);//Assigns form action
        $arrSearchCourse = $this->fnFetchCourse(0,0,$strname);
        $stroption = "";
        $stroption .= "<select name='cid' style='width:170px;'><option value='0'>All Courses</option>";
        if(is_array($arrSearchCourse) && count($arrSearchCourse) > 0){
            foreach($arrSearchCourse as $key => $value){
                if($_POST["cid"] == $value["id"]){
                    $strSel = "selected='selected'";
                } else {
                    $strSel = "";
                }
                $stroption .= "<option value='".$value["id"]."' ".$strSel.">".$value["cname"]."</option>";
            }
            
        } 
        $stroption .= "</select>";   
        $hdlTpl1->setVariable("search1",$strSearch);//Assigns search
        $hdlTpl1->setVariable("courses",$stroption);//Assigns courses
        $hdlTpl1->parse("__SEARCH__");     
        return $hdlTpl1->get();
    }
    
    /**
    * @desc Function fnGetMenuName - This function will make the link as selected
    * @param string $strPname
    * @return HTML
    */
    function fnGetMenuName($strPname) {
        switch($strPname){
            case 'home' :
            case 'curriculum' :
            case 'quick_review' : 
            case 'quizzes' :
            case 'case_library' :
            case 'innovations' :
            case 'advisory' :
                return 1;
                break;
            
        }
    } 
    
    /**
    * @desc Function fnCheckUserPass - This function will check user name and password 
    * @param string $strUname, $strPass
    * @return array
    */
    function fnCheckUserPass($strUname, $strPass) {
        $sqlUser = "SELECT *,COUNT(*) AS cnt FROM tbluser WHERE username='".$strUname."' AND password='".md5($strPass)."' AND isactive='1' GROUP BY id";
        $arrCheckUser = $this->fnDBFetch($sqlUser,$this->hdlDb);
        if(is_array($arrCheckUser) && count($arrCheckUser) > 0){
            return $arrCheckUser;
        } else {
            return 0 ;
        }
    }                        
    
    /**
    * @desc Function fnFetchNews - This function will fetch all the news from the news table
    * @param integer $intLimit, charater $strChar
    * @return array
    */
    function fnFetchNews($intLimit = 0,$strChar = "") {
        $sqlLimit = "";
        if($intLimit){
            $sqlLimit = " LIMIT 0 , $intLimit";
        }
        if($strChar!= ""){
            $sqlWhereCond = " AND newstitle like '".$strChar."%' ";
        }
        $sqlNews = "SELECT *,DATE_FORMAT(dtcreated,'%m/%d/%y') as dcreated FROM tblnews WHERE isactive = '1' ".$sqlWhereCond." ORDER BY `id` DESC ".$sqlLimit;
        $arrNews = $this->fnDBFetch($sqlNews,$this->hdlDb);
        if(is_array($arrNews) && count($arrNews) > 0){
            return $arrNews;
        } else {
            return 0 ;
        }
    }
    
    /**
    * @desc Function fnGetData - This function will divide the array into chumks and will return the chunk array
    * @param array $arrRecordSet, character $char, integer $intPageRecords
    * @return array
    */
    function fnGetData($arrRecordSet,$char="",$intPageRecords){
        if($char != ""){
            $arrExtra = array("char"=>$char);
            $pathForPaging = $char."/news";
        } else {
            $arrExtra = "";
        }
        $arrPagerParam = array(
                  'itemData' => $arrRecordSet,
                  'extraVars' => $arrExtra,
                  'perPage' => $intPageRecords,
                  'delta' => 10, 
                  'urlVar' => 'pageid',
                  'linkClass' =>'maintext',
                  'append' => false,
                  'path'      => $pathForPaging,
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
        $intStartRec = ($intPagerPageId == 1 ) ? 1 : ($intPagerPageId * $intPageRecords) - ($intPageRecords-1); 
        $intLastRec =($intTotalRecords > $intPagerPageId * $intPageRecords) ? ($intPagerPageId * $intPageRecords) : $intTotalRecords; 
                
        $strPaginationText = " ".$strPaginationLinks["all"]." Displaying <b>$intStartRec</b> - <b>$intLastRec</b> of <b>$intTotalRecords</b> for ".$intCnt." matches"; 
        
        if(is_array($arrPagerData) && count($arrPagerData) > 0){
            $arrPagerData["paging"] = $strPaginationText;
            return $arrPagerData;
        }
    }
    
    /**
    * @desc Function fnFetchNewsLeft - This function will fetch all the news from news table
    * @param NULL
    * @return array
    */
    function fnFetchNewsLeft(){
        $sqlNews = "SELECT YEAR( dtcreated ) AS datecreate FROM tblnews WHERE isactive = '1' GROUP BY `datecreate` ORDER BY `datecreate` DESC";
        $arrNewsResult = $this->fnDBFetch($sqlNews,$this->hdlDb);
        if(is_array($arrNewsResult) && count($arrNewsResult) > 0){
            return $arrNewsResult;
        } else {
            return 0;
        }
    }
    
    /**
    * @desc Function fnGetLeftMonth - This function will fetch all months from the news table
    * @param integer $id
    * @return array
    */
    function fnGetLeftMonth($id){
       $sqlNews = "SELECT id,MONTH(dtCreated) as intMonthId,YEAR(dtCreated) as intYear,CONCAT( DATE_FORMAT( dtCreated, '%M' ) , ' ', YEAR( dtCreated ) ) AS mon,COUNT(dtCreated) as totcnt FROM tblnews WHERE isactive = '1' AND YEAR( dtcreated ) = ".$id." GROUP BY mon ORDER BY `intMonthId` ASC";
        $arrNewsResult = $this->fnDBFetch($sqlNews,$this->hdlDb);
        if(is_array($arrNewsResult) && count($arrNewsResult) > 0){
            return $arrNewsResult;
        } else {
            return 0;
        } 
    }
    
    /**
    * @desc Function fnFetchNewsDetails - This function will fetch news for the passed id
    * @param integer $id
    * @return array
    */
    function fnFetchNewsDetails($intId){
        $sqlNews = "SELECT *,date_format(dtcreated,'%D %b, %Y %H %i %p') as datecreated FROM tblnews WHERE isactive='1' AND id=".$intId." ORDER BY id DESC";
        $arrNewsResult = $this->fnDBFetch($sqlNews,$this->hdlDb);
        if(is_array($arrNewsResult) && count($arrNewsResult) > 0){
            return $arrNewsResult;
        } else {
            return 0;
        }
    }
    
    /**
    * @desc Function fnFetchNewsMonYear - This function will fetch news for the passed year and month
    * @param integer $intMon,$intYear
    * @return array
    */
    function fnFetchNewsMonYear($intMon,$intYear){
        $sqlNews = "SELECT *,date_format(dtcreated,'%D %b, %Y %H %i %p') as datecreated FROM tblnews WHERE isactive='1' AND YEAR(dtcreated)=".$intYear." AND MONTH(dtcreated)=".$intMon."  ORDER BY id DESC";
        $arrNewsResult = $this->fnDBFetch($sqlNews,$this->hdlDb);
        if(is_array($arrNewsResult) && count($arrNewsResult) > 0){
            return $arrNewsResult;
        } else {
            return 0;
        }
    }
    
    /**
    * @desc Function fnGetWebTechniques - This function will fetch web based teaching techniques
    * @param NULL
    * @return array
    */
    function fnGetWebTechniques(){
        $sql = "SELECT * FROM tbltechniques WHERE isactive='1' ORDER BY torder ASC";
        $arrWebResult = $this->fnDBFetch($sql,$this->hdlDb);
        if(is_array($arrWebResult) && count($arrWebResult) > 0){
            return $arrWebResult;
        } else {
            return 0;
        }
    }
    
    /**
    * @desc Function fnGetPages - This function will fetch all the pages from the page table
    * @param string $strPage
    * @return array
    */
    function fnGetPages($strPage){
        $sql = "SELECT * FROM tblpages WHERE isactive='1' AND page_name='".$strPage."' ORDER BY isorder ASC";
        $arrWebResult = $this->fnDBFetch($sql,$this->hdlDb);
        if(is_array($arrWebResult) && count($arrWebResult) > 0){
            return $arrWebResult;
        } else {
            return 0;
        }
    }
    
    /**
    * @desc Function fnFetchCourse - This function will fetch all the course from the course table
    * @param integer $intId, $intCid
    * @return array
    */
    function fnFetchCourse($intId = 0,$intCid = 0,$strPagename = ""){
        
        if($intId){
            $sqlWhere = " AND c.id=".$intId;
        }
        if($intCid){
            $sqlWhere = " AND c.id=".$intCid;
        }
        if($strPagename != ""){
            $innerJoin = "INNER JOIN tblmodule m ON m.course_id = c.id AND m.page_name = '".$strPagename."'";
            $sqlWhere1 = " GROUP BY m.course_id";
        }
        $sql = "SELECT c.* FROM tblcources c ".$innerJoin." WHERE c.isactive='1' ".$sqlWhere.$sqlWhere1." ORDER BY c.id ASC";
        $arrCourse = $this->fnDBFetch($sql,$this->hdlDb);
        if(is_array($arrCourse) && count($arrCourse) > 0){
            return $arrCourse;
        } else {
            return 0;
        }
    }
    
    function fnGetCourseId($mid){
        $sql = "SELECT course_id FROM tblmodule WHERE id=".$mid;
        $arrResult = $this->fnDBFetch($sql,$this->hdlDb);
        if(is_array($arrResult) && count($arrResult) > 0){
            return $arrResult[0]["course_id"];
        } else {
            return 0;
        }
    }
    
    /**
    * @desc Function fnGetMiddlePage - This function will get the commonmiddlepage and will display page wise.
    * @param integer $intId, $intCid
    * @return HTML
    */
    function fnGetMiddlePage($intId,$intCid,$strSearchTxt="",$strPagename){
        $hdlTpl1 =& new HTML_Template_Sigma( DIR_TEMPLATE , DIR_TEMPLATE."/prepared");
        $hdlTpl1->loadTemplateFile("commonmiddlepage.htm",TRUE,TRUE);  
        
        $arrCourse = $this->fnFetchCourse($intId,$intCid,$strPagename);
        if(is_array($arrCourse) && count($arrCourse) > 0){
            foreach($arrCourse as $key => $value){
                $hdlTpl1->setVariable("cource_name",$value["cname"]);//Assigns course name
                $arrModule = $this->fnFetchModule($value["id"],$strSearchTxt,$strPagename);
                foreach($arrModule as $m => $v){
                    if($v["img_path"]){
                        $hdlTpl1->setVariable("img1",$v["img_path"]);//Assigns module image
                        $hdlTpl1->parse("module_image");
                    }
                    $hdlTpl1->setVariable("mname",$v["mname"]);//Assigns module name
                    $hdlTpl1->setVariable("mid",$v["id"]);//Assigns module id
                    $hdlTpl1->setVariable("bywhome",$v["by_whom"]);//Assigns bywhome
                    //$hdlTpl1->setVariable("desc",substr(strip_tags($v["mdescription"]),0,100));//Assigns module description
                    $hdlTpl1->setVariable("desc",$v["mdescription"]);//Assigns module description
                    $hdlTpl1->parse("module");
                }
                $hdlTpl1->parse("curriculum");
            }
        }
        return $hdlTpl1->get();
    }
    
    /**
    * @desc Function fnGetModuleDesc - This function will fetch module details from module table and will return an array.
    * @param $mid
    * @return array
    */    
    function fnGetModuleDesc($mid){
        $sql = "SELECT m.*,c.cname FROM tblmodule m INNER JOIN tblcources c ON course_id=c.id WHERE m.isactive='1' AND m.id=".$mid;
        $arrModule = $this->fnDBFetch($sql,$this->hdlDb);
        if(is_array($arrModule) && count($arrModule) > 0){
            return $arrModule;
        } else {
            return 0;
        }
    }
    
    /**
    * @desc Function fnFetchModule - This function will fetch all the modules from module table for particular course id
    * @param integer $intId, string $strSearchTxt, $strPagename
    * @return array
    */
    function fnFetchModule($intId,$strSearchTxt = "",$strPagename){
        if($strSearchTxt != ""){
            $sqlWhere = " AND (mname like '%".$strSearchTxt."%' OR mdescription like '%".$strSearchTxt."%') ";
        }
        $sql = "SELECT * FROM tblmodule WHERE isactive='1' AND page_name='".$strPagename."' AND course_id=".$intId." ".$sqlWhere." ORDER BY morder  ASC";
        $arrModule = $this->fnDBFetch($sql,$this->hdlDb);
        if(is_array($arrModule) && count($arrModule) > 0){
            return $arrModule;
        } else {
            return 0;
        }
    }
    
    function fnGetQuizTitle($cid){
        $sql = "SELECT * FROM tblQuizTitle WHERE to_show='1' AND isactive='1' AND course_id='".$cid."' ORDER BY id DESC";
        $arrResult = $this->fnDBFetch($sql,$this->hdlDb);
        if(is_array($arrResult) && count($arrResult) > 0){
            return $arrResult;
        } else {
            return 0;
        }
    }
    
    function fnGetQuizQuestion($qid){
        $sql = "SELECT * FROM tblQuizQuestion WHERE quiz_title_id=".$qid;
        $arrResult = $this->fnDBFetch($sql,$this->hdlDb);
        if(is_array($arrResult) && count($arrResult) > 0){
            return $arrResult;
        } else {
            return 0;
        }
    }
    
    /**
    * @desc Function fnGetQuizes - This function will fetch all the quizzes from the quiz table
    * @param integer $intCid
    * @return array
    */
    function fnGetQuizes($intCid){
        $sql = "SELECT q. * FROM tblquizzes q INNER JOIN tblmodule m ON q.module_id = m.id AND m.course_id=".$intCid." WHERE q.isactive = '1' ORDER BY q.id ASC";
        $arrQuestion = $this->fnDBFetch($sql,$this->hdlDb);
        if(is_array($arrQuestion) && count($arrQuestion) > 0){
            return $arrQuestion;
        } else {
            return 0;
        }
    }
    
    /**
    * @desc Function fnFetchOption - This function will fetch all the options from option table
    * @param integer $intOpid
    * @return array
    */
    function fnFetchOption($intOpid){
       $sql = "SELECT * FROM tblQuizOption WHERE qid=".$intOpid;
       $arrResult = $this->fnDBFetch($sql,$this->hdlDb);
       if(is_array($arrResult) && count($arrResult) > 0){
            return $arrResult;
       } else {
            return 0;
       } 
    }
    
    /**
    * @desc Function fnGetUser - This function will fetch user from user table
    * @param integer $uId
    * @return array
    */
    function fnGetUser($uId){
       $sql = "SELECT * FROM tbluser WHERE id=".$uId;
       $arrUser = $this->fnDBFetch($sql,$this->hdlDb);
       if(is_array($arrUser) && count($arrUser) > 0){
            return $arrUser;
       } else {
            return 0;
       } 
    }
    
    /**
    * @desc Function fnCheckUserOldPass - This function will check old password in users table
    * @param string $strOldPass, integet $id
    * @return integer
    */
    function fnCheckUserOldPass($strOldPass,$id){
        $sql = "SELECT COUNT(id) as cnt FROM tbluser WHERE password='".md5($strOldPass)."' AND id=".$id;
        $arrUser = $this->fnDBFetch($sql,$this->hdlDb);
        if(is_array($arrUser) && count($arrUser) > 0){
            return $arrUser[0]["cnt"];
        } else {
            return 0;
        } 
    }
    
    /**
    * @desc Function fnGetResult - This function will get result for the given quiz
    * @param integet $cid
    * @return array
    */
    function fnGetResult($cid,$uid){
        //$sql = "SELECT * FROM tblquizzesanswer WHERE course_id=".$cid." AND userid=".$uid." AND quiz_date='".date("Y-m-d")."' AND unique_id='".$_SESSION["StRuNiQuE"]."'";
        $sql = "SELECT * FROM tblQuizAnswer WHERE module_id=".$cid." AND userid=".$uid." AND quiz_date='".date("Y-m-d")."' AND attempted='".$_SESSION["StRuNiQuE"]."'";
        $arrAns = $this->fnDBFetch($sql,$this->hdlDb);
        if(is_array($arrAns) && count($arrAns) > 0){
            return $arrAns;
        } else {
            return 0;
        } 
    }
    
    /**
    * @desc Function fnGetQuizResult - This function fetch all the records from the quiz answer table and will give the precent for the particular course 
    * @param integer $cId,$uid
    * @return string
    */
    function fnGetQuizResult($module_id,$user_id,$strUnique){
        
        $sql = "SELECT qa.*,qq.option_id,SUM(IF(qa.option_id=qq.option_id,1,0)) as correct, GROUP_CONCAT( IF( qa.option_id != qq.option_id, qa.qid,0) ) question FROM tblQuizAnswer qa LEFT JOIN tblQuizQuestion qq ON qq.id=qa.qid WHERE qa.module_id ='".$module_id."' AND qa.userid ='".$user_id."' AND qa.attempted='".$strUnique."' GROUP BY qa.userid";
        $arrAns = $this->fnDBFetch($sql,$this->hdlDb);
        if(is_array($arrAns) && COUNT($arrAns) >0){
            return $arrAns;
        } else {
            return 0;
        }
    }
    
    function fnFetchQuizResult($cid,$qtid,$uid){
        if($cid){
            $sqlWhere = "course_id='".$cid."' AND ";
            $sqlGroup = "course_id";
        } else if($qtid){
            $sqlWhere = "quiz_title_id='".$qtid."' AND ";
            $sqlGroup = "quiz_title_id";
        }
        $sql = "SELECT GROUP_CONCAT(correct_percent) as percent,GROUP_CONCAT(date) as takendate FROM tblQuizPercent WHERE ".$sqlWhere." user_id='".$uid."' GROUP BY ".$sqlGroup;
        $arrResult = $this->fnDBFetch($sql,$this->hdlDb);
        if(is_array($arrResult) && COUNT($arrResult) >0){
            return $arrResult;
        } else {
            return 0;
        }
    }
    
    function fnStoreCorrectPercent($course_id,$quiztitle_id,$module_id,$user_id,$strUnique){
        
        $arrCorrect = $this->fnGetQuizResult($module_id,$user_id,$strUnique);
        
        $intCorrect = $arrCorrect[0]["correct"];
        
        $intHowMany = $this->fnCountQuestion($quiztitle_id,$module_id);
        
        $correct_percent = (($intCorrect/$intHowMany)*100);
        
        $sql = "INSERT INTO tblQuizPercent(id,course_id,quiz_title_id,module_id,correct_percent,correct_ans,out_of,user_id,date) VALUES('','".$course_id."','".$quiztitle_id."','".$module_id."','".$correct_percent."','".$intCorrect."','".$intHowMany."','".$user_id."','".date("Y-m-d")."')";
        mysql_query($sql) or die(mysql_error());
        
    }
    
   /**
    * @desc Function fnGetAnsDate - This function will fetch the date for the unique id passed
    * @param array $arrAns
    * @return integer
    */
    function fnGetAnsDate($strUniqueId){
        $sql = "SELECT quiz_date FROM tblquizzesanswer WHERE unique_id='".$strUniqueId."'";
        $arrDate = $this->fnDBFetch($sql,$this->hdlDb);
        if(is_array($arrDate) && count($arrDate) > 0){
            return $arrDate[0]["quiz_date"];
        } else {
            return 0;
        }
    }
    
    function fnListQuestion($strQuestion,$mid){
        if($strQuestion != ""){
            $sqlWhere = "id IN (".$strQuestion.") AND";
        }
        $sql = "SELECT id,module_id,question FROM tblQuizQuestion WHERE ".$sqlWhere." module_id=".$mid;
        $arrList = $this->fnDBFetch($sql,$this->hdlDb);
        if(is_array($arrList) && count($arrList) > 0){
            return $arrList;
        } else {
            return 0;
        }
    }
    
    /**
    * @desc Function fnGetCorrectAns - This function will check the correct answer for the qiven quiz
    * @param array $arrAns
    * @return integer
    */
    function fnGetCorrectAns($arrAns){ 
        foreach($arrAns as $key => $value){
            $intQuestion .= $value["question_id"].",";
            $intOption .= $value["option_id"].",";
        }
        $intQuestion = ereg_replace(',$','',$intQuestion);
        $intOption = ereg_replace(',$','',$intOption);
        
        $sql = "SELECT option_id FROM tblquizzes WHERE id in (".$intQuestion.")";
        $arropt = $this->fnDBFetch($sql,$this->hdlDb);
        
        $arrOption = explode(",",$intOption);
        $intCorrect = 0;
        for($i=0;$i<count($arrOption);$i++){
            if(in_array($arropt[$i]["option_id"],$arrOption)){
                $intCorrect++;
            }    
        }
        return $intCorrect;
    }
    
    /**
    * @desc Function fnCountQuestion - This function will count number of question from the quizzes table
    * @param integer $inCid, $intUid
    * @return integer
    */    
    function fnCountQuestion($qtid,$cid){
        //$sql = "SELECT COUNT(*) as cnt FROM tblquizzes WHERE module_id=".$cid;
        $sql = "SELECT COUNT(*) as cnt FROM tblQuizQuestion WHERE quiz_title_id='".$qtid."' AND module_id=".$cid;
        $arrCountQuestion = $this->fnDBFetch($sql,$this->hdlDb);
        if(is_array($arrCountQuestion) && count($arrCountQuestion) > 0){
            return $arrCountQuestion[0]["cnt"];
        } else {
            return 0;
        }
    }                                           
    
    /**
    * @desc Function fnCheckQuizExist - This function will check if quiz is taken for the particular cource for the particular user.
    * @param integer $inCid, $intUid
    * @return integer
    */
    function fnCheckQuizExist($inCid=0,$intMid=0, $intUid){
        if($inCid){
            $sqlWhere = "course_id=".$inCid." AND ";
        } else if($intMid){
            $sqlWhere = "quiz_title_id =".$intMid." AND ";
        }
        $sql = "SELECT COUNT(*) as cnt FROM tblQuizAnswer WHERE ".$sqlWhere." userid=".$intUid;
        $arrAns = $this->fnDBFetch($sql,$this->hdlDb);
        if(is_array($arrAns) && count($arrAns) > 0){
            return $arrAns[0]["cnt"];
        }
    }
    
    /**
    * @desc Function fnCheckEmail - This function will check if email address already present or not.
    * @param integer $intId,string $strSearch
    * @return array
    */
    function fnCheckEmail($strEmail){
        $sqlUser = "SELECT COUNT(id) as CNT FROM tbluser WHERE isactive='1' AND email='".$strEmail."'";
        $arrUserResult = $this->fnDBFetch($sqlUser,$this->hdlDb);
        
        if(is_array($arrUserResult) && count($arrUserResult) > 0){
            return $arrUserResult[0]["CNT"];
        } else {
            return 0;
        }
    }
    
    /**
    * @desc Function fnGetUser - This function will Fetch the details for the particular user.
    * @param string $strEmail
    * @return array
    */
    function fnGetUserEmail($strEmail){
       $sqlUser = "SELECT * FROM tbluser WHERE isactive='1' AND email='".$strEmail."'";
       $arrUserResult = $this->fnDBFetch($sqlUser,$this->hdlDb);
        
       if(is_array($arrUserResult) && count($arrUserResult) > 0){
            return $arrUserResult;
       } else {
            return 0;
       } 
    }
    
    /**
    * @desc Function generatePassword - This function will generate random password for user.
    * @param integer $length, $strength
    * @return string
    */
    function generatePassword($length=9, $strength=0) {
        $vowels = 'aeuy';
        $consonants = 'bdghjmnpqrstvz';
        if ($strength & 1) {
            $consonants .= 'BDGHJLMNPQRSTVWXZ';
        }
        if ($strength & 2) {
            $vowels .= "AEUY";
        }
        if ($strength & 4) {
            $consonants .= '23456789';
        }
        if ($strength & 8) {
            $consonants .= '@#$%';
        }
     
        $password = '';
        $alt = time() % 2;
        for ($i = 0; $i < $length; $i++) {
            if ($alt == 1) {
                $password .= $consonants[(rand() % strlen($consonants))];
                $alt = 0;
            } else {
                $password .= $vowels[(rand() % strlen($vowels))];
                $alt = 1;
            }
        }
        return $password;
    }
    
    /**
    * @desc Function fnTrackUser - This function will track user for his activities
    * @param NULL
    * @return NULL
    */
    function fnTrackUser($strPage = "", $intMainId = 0, $intCid = 0, $intQtid = 0){
        $long = ip2long($_SERVER["REMOTE_ADDR"]);
        $sqlCity = "SELECT * FORM ip_group_city WHERE ip_start='".$long."'";
        $rs = mysql_query($sqlCity);
        $arrIpGroupCity = mysql_fetch_array($rs);
        
        if($_SESSION["LaStId"] && $_SESSION["PaGeNaMe"] != $strPage){
            $sqlUpd = "UPDATE tblusertrack SET timeout='".date("Y-m-d H:i:s")."' WHERE id=".$_SESSION["LaStId"];
            mysql_query($sqlUpd);
        }
        
        if($_SESSION["PaGeNaMe"] != $strPage){
            $sql = "INSERT INTO tblusertrack(id,mid,ipaddress,pagename,userid,ccode,cname,rname,city,zipcode,course_id,quiz_title_id,datecreated,timein) VALUES('','".$intMainId."','".$_SERVER["REMOTE_ADDR"]."','".$strPage."','".$_SESSION["UID"]."','".$arrIpGroupCity["country_code"]."','".$_SERVER["GEOIP_COUNTRY_NAME"]."','".$arrIpGroupCity["region_name"]."','".$arrIpGroupCity["city"]."','".$arrIpGroupCity["zipcode"]."','".$intCid."','".$intQtid."','".date("Y-m-d H:i:s")."','".date("Y-m-d H:i:s")."')";
             mysql_query($sql) or die(mysql_error());
             $intLastId = $this->fnGetLastInsertId();
             $_SESSION["LaStId"] = $intLastId;
             $_SESSION["PaGeNaMe"] = $strPage;
             
             $timeIn30Minutes = mktime(date("H"), date("i") + SESSION_TIME_OUT, date("s"),date("m"),date("d"),date("Y"));
             $intTime = date("Y-m-d H:i:s",$timeIn30Minutes);
             
             $sqlUpd = "UPDATE tblusertrackmain SET timeout='".$intTime."' WHERE id=".$_SESSION["MaInId"]." AND userid=".$_SESSION["UID"];
            mysql_query($sqlUpd);
             
        }
    }
    
    /**
    * @desc Function fnUserTrackMain - This function will insert main tracking record to database.
    * @param integer $uid
    * @return NULL
    */
    function fnUserTrackMain($uid){
        if($_SESSION["MaInId"]){
            $sqlUpd = "UPDATE tblusertrackmain SET timeout='".date("Y-m-d H:i:s")."' WHERE id=".$_SESSION["MaInId"]." AND userid=".$uid;
            mysql_query($sqlUpd); 
        } else {
            $long = ip2long($_SERVER["REMOTE_ADDR"]);
        
            $sqlCity = "SELECT * FORM ip_group_city WHERE ip_start='".$long."'";
            $rs = mysql_query($sqlCity);
            $arrIpGroupCity = mysql_fetch_array($rs);
            
            $sqlutrack = "SELECT ut.id,ut.mid,um.timeout FROM tblusertrack ut INNER JOIN tblusertrackmain um on um.id=ut.mid WHERE ut.timeout='00:00:00'";
            $arrUserResult = $this->fnDBFetch($sqlutrack,$this->hdlDb);
            if(is_array($arrUserResult) && count($arrUserResult) > 0) {
                foreach($arrUserResult as $key => $value){
                    $sqlupd = "UPDATE tblusertrack SET timeout='".$value["timeout"]."' WHERE id=".$value["id"]." AND mid=".$value["mid"];       
                    mysql_query($sqlupd) or die(mysql_error());
                }
            }    
             
            $sqlIns = "INSERT INTO tblusertrackmain(id,ipaddress,userid,ccode,cname,rname,city,zipcode,dateofvisit,timein) VALUES('','".$_SERVER["REMOTE_ADDR"]."','".$uid."','".$arrIpGroupCity["country_code"]."','".$_SERVER["GEOIP_COUNTRY_NAME"]."','".$arrIpGroupCity["region_name"]."','".$arrIpGroupCity["city"]."','".$arrIpGroupCity["zipcode"]."','".date("Y-m-d H:i:s")."','".date("Y-m-d H:i:s")."')";
            mysql_query($sqlIns);
            $intLastId = $this->fnGetLastInsertId();
            $_SESSION["MaInId"] = $intLastId;
        }
    }   
    
    /**
    * @desc Function fnFetchUserDetails - This function will fetch / update the particular user
    * @param integer $uid,$updid
    * @return array / NULL
    */
    function fnFetchUserDetails($uid,$updid = 0){
        if($updid){
            $sql = "UPDATE tbluser SET last_logged_in='".date("Y-m-d H:i:s")."' WHERE id=".$uid;
            mysql_query($sql);
        } else {
            $sql = "SELECT * FROM tbluser WHERE id=".$uid."  AND isactive='1'";
            $arrUserDetails = $this->fnDBFetch($sql,$this->hdlDb);
            return $arrUserDetails;
        }
    }
    
    /**
    * @desc Function fnUserCheckTemp - This function will check the tempory user and will logged out him if his time is expirs
    * @param integer $uid
    * @return integer
    */
    function fnUserCheckTemp($uid){
        $arrUser = $this->fnFetchUserDetails($uid);
        if($arrUser[0]["date"] !=0 || $arrUser[0]["noofhrs"] != 0) {
            if($arrUser[0]["date"] != 0){
                $sql = "SELECT UNIX_TIMESTAMP('".$arrUser[0]["date"]."') as futuredate, UNIX_TIMESTAMP() as dtcurrent";
            } else if($arrUser[0]["noofhrs"] != 0){
                $sql = "SELECT UNIX_TIMESTAMP(DATE_ADD('".$arrUser[0]["last_logged_in"]."', INTERVAL ".$arrUser[0]["noofhrs"]." ".$arrUser[0]["howmany"].")) as futuredate, UNIX_TIMESTAMP() as dtcurrent";
            }
            
            $arrFutureDate = $this->fnDBFetch($sql,$this->hdlDb);
            if($arrFutureDate[0]["dtcurrent"] > $arrFutureDate[0]["futuredate"]){
                return 1;
            }
        }                                          
    }
    
    /**
    * @desc Function fnInActiveUser - This function will reset all the session variable and will update the active status for the particular user.
    * @param integer $uid
    * @return NULL
    */
    function fnInActiveUser($uid){
        
        $sql = "UPDATE tbluser SET isactive='0' WHERE id=".$uid;
        mysql_query($sql);
        
        $_SESSION["UID"] = "";//Empty session value
        $_SESSION["UNAME"] = "";//Empty session value
        $_SESSION["LaStId"] = "";//Empty session value
        $_SESSION["PaGeNaMe"] = "";//Empty session value   
        $_SESSION["MaInId"] = "";//Empty session value
        
        unset($_SESSION["UID"]);//unset session
        unset($_SESSION["UNAME"]);//unset session
        unset($_SESSION["LaStId"]);//unset session
        unset($_SESSION["PaGeNaMe"]);//unset session
        unset($_SESSION["MaInId"]);//unset session
    }
}
?>