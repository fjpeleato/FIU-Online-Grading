<?php require_once('../Connections/fiu_grading_system.php'); ?>
<?php
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

$cID = $row_CourseInfo['ID'];

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "new_activity")) {
  $insertSQL = sprintf("INSERT INTO Activities (course_ID, name, max_points) VALUES (%s, %s, %s)",
                       GetSQLValueString($_GET['course_ID'], "int"),
                       GetSQLValueString($_POST['name'], "text"),
                       GetSQLValueString($_POST['maxGrade'], "int"));

  mysql_select_db($database_fiu_grading_system, $fiu_grading_system);
  $Result1 = mysql_query($insertSQL, $fiu_grading_system) or die(mysql_error());
  mysql_free_result($Result1);

  $insertGoTo = sprintf("profCourseView.php?course_ID=%d", $row_CourseInfo['ID']);
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta content="text/html; charset=UTF-8" http-equiv="Content-Type" />
<title>Professor Session: New Activity - FIU Online Grading</title>
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
<script src="../SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<link href="../SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
</head>

<body>
<?php include("_prof_Includes/header.php"); ?>
<?php include("_prof_Includes/side_bar.php"); ?>

		<div class="span9">
        	<div class="hero-unit"> 
			<h1>New Activity</h1>
			<br>
			<form action="<?php echo $editFormAction; ?>" method="POST" name="new_activity" class="form-horizontal">
				<div class="control-group">
					<label class="control-label" for="name">Activity Name</label>
					<div class="controls"><span id="sprytextfield1">
                    <input id="name" placeholder="Activity Name" type="text" name="name" />
                    <span class="textfieldRequiredMsg">A value is required.</span></span></div>
				</div>
				<div class="control-group">
					<label class="control-label" for="maxGrade">Max Possible Grade</label>
					<div class="controls"><span id="sprytextfield2">
                    <input id="maxGrade" placeholder="Max Grade" type="text" name="maxGrade" />
                    <span class="textfieldRequiredMsg">A value is required.</span><span class="textfieldInvalidFormatMsg">Invalid format.</span><span class="textfieldMinValueMsg">The entered value is less than the minimum required.</span><span class="textfieldMaxValueMsg">The entered value is greater than the maximum allowed.</span></span></div>
				</div>
				<div class="control-group">
					<div class="controls">
						<button class="btn btn-primary" type="submit">Submit</button>
						<a href="profCourseView.php?course_ID=<?php echo $_GET['course_ID']?>"><button name="Cancel" type="button" id="cancel" class="btn btn-danger"> Cancel </button></a>
					</div>
				</div>
				<input type="hidden" name="course_ID" value="<?php echo $cID; ?>" />
				<input type="hidden" name="MM_insert" value="new_activity" />
			</form>
		</div>
	</div>
</div>
</div>
<script type="text/javascript">
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1", "none", {validateOn:["blur"]});
var sprytextfield2 = new Spry.Widget.ValidationTextField("sprytextfield2", "integer", {validateOn:["blur"], minValue:1, maxValue:999});
</script>
</body>

</html>
<?php
  mysql_free_result($_UserData);
  mysql_free_result($_CourseInfo);
?>
