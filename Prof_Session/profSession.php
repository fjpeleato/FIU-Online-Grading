<?php require_once('../Connections/fiu_grading_system.php'); ?>
<?php //sesion code
//initialize the session
if (!isset($_SESSION)) {
  session_start();
}

// ** Logout the current user. **
$logoutAction = $_SERVER['PHP_SELF']."?doLogout=true";
if ((isset($_SERVER['QUERY_STRING'])) && ($_SERVER['QUERY_STRING'] != "")){
  $logoutAction .="&". htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_GET['doLogout'])) &&($_GET['doLogout']=="true")){
  //to fully log out a visitor we need to clear the session varialbles
  $_SESSION['MM_Username'] = NULL;
  $_SESSION['MM_UserGroup'] = NULL;
  $_SESSION['PrevUrl'] = NULL;
  unset($_SESSION['MM_Username']);
  unset($_SESSION['MM_UserGroup']);
  unset($_SESSION['PrevUrl']);
	
  $logoutGoTo = "../login.php";
  if ($logoutGoTo) {
    header("Location: $logoutGoTo");
    exit;
  }
}
?>
<?php //session-check code
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "1";
$MM_donotCheckaccess = "false";

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 
  // For security, start by assuming the visitor is NOT authorized. 
  $isValid = False; 

  // When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
  // Therefore, we know that a user is NOT logged in if that Session variable is blank. 
  if (!empty($UserName)) { 
    // Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 
    // Parse the strings into arrays. 
    $arrUsers = Explode(",", $strUsers); 
    $arrGroups = Explode(",", $strGroups); 
    if (in_array($UserName, $arrUsers)) { 
      $isValid = true; 
    } 
    // Or, you may restrict access to only certain users based on their username. 
    if (in_array($UserGroup, $arrGroups)) { 
      $isValid = true; 
    } 
    if (($strUsers == "") && false) { 
      $isValid = true; 
    } 
  } 
  return $isValid; 
}

$MM_restrictGoTo = "../login.php";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {   
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($_SERVER['QUERY_STRING']) && strlen($_SERVER['QUERY_STRING']) > 0) 
    $MM_referrer .= "?" . $_SERVER['QUERY_STRING'];
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;
}
?>
<?php
if (!function_exists("GetSQLValueString")) {
  function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
  {
    if (PHP_VERSION < 6) {
      $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
    }

    $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

    switch ($theType) {
      case "text":
        $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
        break;    
      case "long":
      case "int":
        $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
      case "double":
        $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
        break;
      case "date":
        $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
        break;
      case "defined":
        $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
        break;
    }
    return $theValue;
  }
}

$username  = "-1";
if (isset($_SESSION['MM_Username'])) {
  $username = $_SESSION['MM_Username'];
}
mysql_select_db($database_fiu_grading_system, $fiu_grading_system);
$query_UserData = sprintf("SELECT ID, first_name, last_name FROM Users WHERE user_name = %s", GetSQLValueString($username, "text"));
$_UserData = mysql_query($query_UserData, $fiu_grading_system) or die(mysql_error());
$row_UserData = mysql_fetch_assoc($_UserData);
$totalRows_UserData = mysql_num_rows($_UserData);

//course information query
$query_courseInfo = sprintf("SELECT course_number, name, section, ID FROM Courses WHERE professor_ID = %d", GetSQLValueString($row_UserData['ID'], "int"));
$_CourseInfo = mysql_query($query_courseInfo, $fiu_grading_system) or die(mysql_error());
$row_CourseInfo = mysql_fetch_assoc($_CourseInfo);
$totalRows_courseInfo = mysql_num_rows($_CourseInfo);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">



  <head>

  <meta content="text/html; charset=UTF-8" http-equiv="Content-Type" />

  <title>Professor Session: My Courses - FIU Online Grading</title>
  <!-- Le styles -->
  <link href="../assets/css/bootstrap.css" rel="stylesheet" type="text/css">
  <style type="text/css">
  body {
  padding-top: 60px;
  padding-bottom: 40px;
}
.sidebar-nav {
padding: 9px 0;
 }
.hero-unit {
		
padding: 9px;
  padding-left: 40px;
  padding-right: 40px;
 }
</style>
</head>

<body>

<?php include("_prof_Includes/header.php"); ?>
<?php include("_prof_Includes/side_bar.php"); ?>


<div class="span9">
  <div class="hero-unit">
<?php
  //reset _CourseInfo pointer
  mysql_data_seek($_CourseInfo, 0);

if(mysql_num_rows($_CourseInfo) > 0)
{
  while ($row_CourseInfo = mysql_fetch_assoc($_CourseInfo)) 
  { ?>
    <a href="profCourseView.php?course_ID=<?php echo $row_CourseInfo['ID'];?>">
      <h3> <?php echo $row_CourseInfo['course_number'], 
      " - ", 
      $row_CourseInfo['name'],
      " (", 
      $row_CourseInfo['section'], 
      ")"; 

    //average and max points queries
    $query_avgData = sprintf("SELECT SUM(x.avg) FROM (SELECT AVG(points) AS avg FROM Grades WHERE activity_ID IN (SELECT ID FROM Activities WHERE course_ID = %d) GROUP BY activity_ID) x", GetSQLValueString($row_CourseInfo['ID'], "int"));
    $_AvgData = mysql_query($query_avgData, $fiu_grading_system) or die(mysql_error());
    $row_AvgData= mysql_fetch_assoc($_AvgData);
    
    $query_maxData = sprintf("SELECT SUM(max_points) FROM Activities WHERE course_ID = %d", GetSQLValueString($row_CourseInfo['ID'], "int"));
    $_MaxData = mysql_query($query_maxData, $fiu_grading_system) or die(mysql_error());
    $row_MaxData= mysql_fetch_assoc($_MaxData);

?>
    </h3>
  </a>
        <h4>Current average score: <?php echo (int)$row_AvgData["SUM(x.avg)"], " / ", (int)$row_MaxData["SUM(max_points)"]; ?>  </h4>
<?php 
        mysql_free_result($_MaxData);
        mysql_free_result($_AvgData);
  }
} ?>
        <br>
           
           </div>
           
           </div>
           </div>
           
           </div>



           </body>



           </html>

<?php
  mysql_free_result($_UserData);
  mysql_free_result($_CourseInfo);
?>

