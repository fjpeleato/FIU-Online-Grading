<?php include("_student_Includes/logout_UserValidation.php"); ?>
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

$colname_rs_UserData = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_rs_UserData = $_SESSION['MM_Username'];
}
mysql_select_db($database_fiu_grading_system, $fiu_grading_system);
$query_rs_UserData = sprintf("SELECT ID, first_name, last_name FROM Users WHERE user_name = %s", GetSQLValueString($colname_rs_UserData, "text"));
$rs_UserData = mysql_query($query_rs_UserData, $fiu_grading_system) or die(mysql_error());
$row_rs_UserData = mysql_fetch_assoc($rs_UserData);
$totalRows_rs_UserData = mysql_num_rows($rs_UserData);

$colname_rs_courseInfo = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_rs_courseInfo = $_SESSION['MM_Username'];
}
mysql_select_db($database_fiu_grading_system, $fiu_grading_system);
$query_rs_courseInfo = sprintf("SELECT Courses.course_number, Courses.name, Courses.`section`, Users.first_name, Users.last_name, Courses.ID FROM Courses, Users WHERE Courses.ID IN (SELECT CourseEnrollment.course_ID FROM CourseEnrollment WHERE CourseEnrollment.student_ID IN (SELECT Users.ID  FROM Users WHERE user_name = %s) ) AND Courses.professor_ID =Users.ID", GetSQLValueString($colname_rs_courseInfo, "text"));
$rs_courseInfo = mysql_query($query_rs_courseInfo, $fiu_grading_system) or die(mysql_error());
$row_rs_courseInfo = mysql_fetch_assoc($rs_courseInfo);
$totalRows_rs_courseInfo = mysql_num_rows($rs_courseInfo);

$colname_Recordset1 = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_Recordset1 = $_SESSION['MM_Username'];
}
mysql_select_db($database_fiu_grading_system, $fiu_grading_system);
$query_Recordset1 = sprintf("SELECT Courses.course_number, Courses.name, Courses.`section`, Users.first_name, Users.last_name, Courses.ID FROM Courses, Users WHERE Courses.ID IN (SELECT CourseEnrollment.course_ID FROM CourseEnrollment WHERE CourseEnrollment.student_ID IN (SELECT Users.ID  FROM Users WHERE user_name = %s) ) AND Courses.professor_ID =Users.ID", GetSQLValueString($colname_Recordset1, "text"));
$Recordset1 = mysql_query($query_Recordset1, $fiu_grading_system) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);

$colname_rs_sum = "-1";
if (isset($_GET['course_ID'])) {
  $colname_rs_sum = $_GET['course_ID'];
}
$username_rs_sum = "-1";
if (isset($_SESSION['MM_Username'])) {
  $username_rs_sum = $_SESSION['MM_Username'];
}
mysql_select_db($database_fiu_grading_system, $fiu_grading_system);
$query_rs_sum = sprintf("SELECT SUM(Grades.points),SUM( Activities.max_points) FROM Activities, Grades WHERE course_ID = %s AND Activities.ID = Grades.activity_ID AND Grades.student_ID IN (SELECT Users.ID  FROM Users WHERE Users.user_name = %s)", GetSQLValueString($colname_rs_sum, "int"),GetSQLValueString($username_rs_sum, "text"));
$rs_sum = mysql_query($query_rs_sum, $fiu_grading_system) or die(mysql_error());
$row_rs_sum = mysql_fetch_assoc($rs_sum);
$totalRows_rs_sum = mysql_num_rows($rs_sum);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta content="text/html; charset=UTF-8" http-equiv="Content-Type" />
<title>Student Session: My Courses - FIU Online Grading</title>
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
	padding-left:40px;
	padding-right:40px;
}
}
</style>
</head>

<body>
<?php include("_student_Includes/Header.php"); ?>
<?php include("_student_Includes/sideBar.php"); ?>

<div class="span9">
	<div class="hero-unit">
    <legend><h1>My Courses</h1></legend>
    <?php 
    if($totalRows_rs_courseInfo > 0)
    {
      mysql_data_seek($rs_courseInfo, 0);
    while ($row_rs_courseInfo = mysql_fetch_assoc($rs_courseInfo)){ ?>
    <table class="table table-hover">
  	  <tr class="info"><h4><a href="studentCourseView.php?course_ID=<?php echo $row_rs_courseInfo['ID']; ?>"><?php echo $row_rs_courseInfo['course_number']; ?> - <?php echo $row_rs_courseInfo['name']; ?> (<?php echo $row_rs_courseInfo['section']; ?>)</a></h4></tr>
      <tr><h5>Professor: <?php echo $row_rs_courseInfo['first_name']; ?> <?php echo $row_rs_courseInfo['last_name']; ?> </h5></tr>
      <tr></tr>
      <tr></tr>
      <?php } 
    }?>
    </table>	
	</div>
</div>
</div>



</body>

</html>
<?php
mysql_free_result($rs_UserData);

mysql_free_result($rs_courseInfo);

mysql_free_result($Recordset1);

mysql_free_result($rs_sum);
?>
