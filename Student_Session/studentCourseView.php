<?php require_once('../Connections/fiu_grading_system.php'); ?>
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

$colname_Recordset2 = "1";
if (isset($_GET['course_ID'])) {
  $colname_Recordset2 = $_GET['course_ID'];
}
$username_Recordset2 = "-1";
if (isset($_SESSION['MM_Username'])) {
  $username_Recordset2 = $_SESSION['MM_Username'];
}
mysql_select_db($database_fiu_grading_system, $fiu_grading_system);
$query_Recordset2 = sprintf("SELECT Activities.name, Activities.max_points, Grades.points FROM Activities, Grades WHERE course_ID = %s AND Activities.ID = Grades.activity_ID AND Grades.student_ID IN (SELECT Users.ID  FROM Users WHERE Users.user_name = %s)", GetSQLValueString($colname_Recordset2, "int"),GetSQLValueString($username_Recordset2, "text"));
$Recordset2 = mysql_query($query_Recordset2, $fiu_grading_system) or die(mysql_error());
$row_Recordset2 = mysql_fetch_assoc($Recordset2);
$totalRows_Recordset2 = mysql_num_rows($Recordset2);

$colname_rs_courseName = "-1";
if (isset($_GET['course_ID'])) {
  $colname_rs_courseName = $_GET['course_ID'];
}
mysql_select_db($database_fiu_grading_system, $fiu_grading_system);
$query_rs_courseName = sprintf("SELECT course_number, `section`, name FROM Courses WHERE ID = %s", GetSQLValueString($colname_rs_courseName, "int"));
$rs_courseName = mysql_query($query_rs_courseName, $fiu_grading_system) or die(mysql_error());
$row_rs_courseName = mysql_fetch_assoc($rs_courseName);
$totalRows_rs_courseName = mysql_num_rows($rs_courseName);

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

$colname_rs_UserData = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_rs_UserData = $_SESSION['MM_Username'];
}
mysql_select_db($database_fiu_grading_system, $fiu_grading_system);
$query_rs_UserData = sprintf("SELECT ID, first_name, last_name FROM Users WHERE user_name = %s", GetSQLValueString($colname_rs_UserData, "text"));
$rs_UserData = mysql_query($query_rs_UserData, $fiu_grading_system) or die(mysql_error());
$row_rs_UserData = mysql_fetch_assoc($rs_UserData);
$totalRows_rs_UserData = mysql_num_rows($rs_UserData);

$colname_rs_total_class_avg = "1";
if (isset($_GET['course_ID'])) {
  $colname_rs_total_class_avg = $_GET['course_ID'];
}
mysql_select_db($database_fiu_grading_system, $fiu_grading_system);
$query_rs_total_class_avg = sprintf("SELECT SUM(Activities.max_points), SUM(Grades.points) FROM Activities, Grades WHERE course_ID = %s  AND Activities.ID = Grades.activity_ID ORDER BY Activities.name", GetSQLValueString($colname_rs_total_class_avg, "int"));
$rs_total_class_avg = mysql_query($query_rs_total_class_avg, $fiu_grading_system) or die(mysql_error());
$row_rs_total_class_avg = mysql_fetch_assoc($rs_total_class_avg);
$totalRows_rs_total_class_avg = mysql_num_rows($rs_total_class_avg);

$c_ID_Recordset3 = "3";
if (isset($_GET['course_ID'])) {
  $c_ID_Recordset3 = $_GET['course_ID'];
}
$usrname_Recordset3 = "rzoro";
if (isset($_SESSION['MM_Username'])) {
  $usrname_Recordset3 = $_SESSION['MM_Username'];
}

$query_Recordset3 = sprintf("SELECT Activities.course_ID, Activities.name, Activities.max_points, Grades.points, courses_avg.classMax, courses_avg.classPoints FROM Activities, Grades, courses_avg WHERE Activities.course_ID = %s AND courses_avg.course_ID = %s AND Activities.name = courses_avg.name AND Activities.ID = Grades.activity_ID AND  Grades.student_ID IN (SELECT Users.ID  FROM Users WHERE Users.user_name = %s)", GetSQLValueString($c_ID_Recordset3, "int"),GetSQLValueString($c_ID_Recordset3, "int"),GetSQLValueString($usrname_Recordset3, "text"));
$Recordset3 = mysql_query($query_Recordset3, $fiu_grading_system) or die(mysql_error());
$totalRows_Recordset3 = mysql_num_rows($Recordset3);

$colname_Recordset1 = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_Recordset1 = $_SESSION['MM_Username'];
}

$query_Recordset1 = sprintf("SELECT Courses.course_number, Courses.name, Courses.`section`, Users.first_name, Users.last_name, Courses.ID FROM Courses, Users WHERE Courses.ID IN (SELECT CourseEnrollment.course_ID FROM CourseEnrollment WHERE CourseEnrollment.student_ID IN (SELECT Users.ID  FROM Users WHERE user_name = %s) ) AND Courses.professor_ID =Users.ID", GetSQLValueString($colname_Recordset1, "text"));
$Recordset1 = mysql_query($query_Recordset1, $fiu_grading_system) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);

//activities query
$query_activityInfo = sprintf("SELECT * FROM Activities WHERE course_ID = %d", GetSQLValueString($_GET["course_ID"], "int"));
$_ActivityInfo = mysql_query($query_activityInfo, $fiu_grading_system) or die(mysql_error());

$query_gradesInfo = sprintf("SELECT * FROM Grades WHERE activity_ID IN (SELECT ID FROM Activities WHERE course_ID = %d) AND student_ID = (SELECT Users.ID  FROM Users WHERE Users.user_name = %s)", GetSQLValueString($_GET["course_ID"], "int"), GetSQLValueString($_SESSION['MM_Username'], "text"));
$_GradesInfo = mysql_query($query_gradesInfo, $fiu_grading_system) or die(mysql_error());

$query_avgData = sprintf("SELECT SUM(x.avg) FROM (SELECT AVG(points) AS avg FROM Grades WHERE activity_ID IN (SELECT ID FROM Activities WHERE course_ID = %d) GROUP BY activity_ID) x", GetSQLValueString($_GET["course_ID"], "int"));
$_AvgData = mysql_query($query_avgData, $fiu_grading_system) or die(mysql_error());
$row_AvgData= mysql_fetch_assoc($_AvgData);

$query_maxData = sprintf("SELECT SUM(max_points) FROM Activities WHERE course_ID = %d", GetSQLValueString($_GET["course_ID"], "int"));
$_MaxData = mysql_query($query_maxData, $fiu_grading_system) or die(mysql_error());
$row_MaxData= mysql_fetch_assoc($_MaxData);

$query_avgIndividualData = sprintf("SELECT SUM(x.avg) FROM (SELECT AVG(points) AS avg FROM Grades WHERE activity_ID IN (SELECT ID FROM Activities WHERE course_ID = %d) GROUP BY activity_ID) x", GetSQLValueString($_GET["course_ID"], "int"));
$_AvgIndividuaData = mysql_query($query_avgIndividualData, $fiu_grading_system) or die(mysql_error());
$row_AvgIndividuaData= mysql_fetch_assoc($_AvgIndividuaData);
    
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta content="text/html; charset=UTF-8" http-equiv="Content-Type" />
<title>Student Session: View Course - FIU Online Grading</title>
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
</style>
</head>

<body>
<?php include("_student_Includes/Header.php"); ?>
<?php include("_student_Includes/sideBar.php"); ?>
<div class="span9">
	<div class="hero-unit">
		<h2> <?php echo $row_rs_courseName['course_number']; ?>- <?php echo $row_rs_courseName['name']; ?> (<?php echo $row_rs_courseName['section']; ?>)</h2>
		<br>
		<table class="table table-stripped">
			<tr>
				<th>Activity</th>
				<th>Grade</th>
				<th>Letter grade</th>
			</tr>
            <?php 
  if(mysql_num_rows($_ActivityInfo) > 0)
  {
    while ($row_Activity = mysql_fetch_assoc($_ActivityInfo)) 
    {
      if(mysql_num_rows($_GradesInfo) > 0)
      {
        //get correct grade for student
        mysql_data_seek($_GradesInfo, 0);
        $row_gradesInfo = mysql_fetch_assoc($_GradesInfo);
        //find the corresponding grade for the current activity
        while($row_gradesInfo["activity_ID"] != $row_Activity["ID"])
        {
          $row_gradesInfo = mysql_fetch_assoc($_GradesInfo);
          if(!$row_gradesInfo)
            break;
        }
      }
?>
  <tr>
    <td><?php echo $row_Activity['name']; ?></td>
    <td><?php 
      if(isset($row_gradesInfo['points']))
         echo $row_gradesInfo['points'], " / ", $row_Activity['max_points'];
      else
         echo " - / ", $row_Activity['max_points'];
 ?>
</td>
    <td><?php 
      if($row_Activity['max_points'] != 0)
        $scorea_A =  $row_gradesInfo['points'] * 100/ $row_Activity['max_points']; 
      else
        $scorea_A =  0;
	
				if( $scorea_A  < 50 )
					echo "F";
				else if( $scorea_A < 52 )
					echo "D-";
				else if( $scorea_A  < 56 )
					echo "D";
				else if( $scorea_A  < 59 )
					echo "D+";
				else if($scorea_A < 63)
					echo "C-";
				else if( $scorea_A  < 73 )
					echo "C";
				else if($scorea_A  < 79 )
					echo "C+";
				else if( $scorea_A  < 82 )
					echo "B-";
				else if( $scorea_A < 86 )
					echo "B";
				else if( $scorea_A < 89 )
					echo "B+";
				else if($scorea_A  < 92 )
					echo "A-";
				else 
					echo "A";
			
			?></td>
    
  </tr>
<?php }
  } ?>
			<tr>
		    <th>Current total</th>
			<td><?php echo $row_rs_sum['SUM(Grades.points)']; ?> / <?php echo $row_MaxData['SUM(max_points)']; ?> </th>
			<th>
            
			<?php 
      if($row_MaxData['SUM(max_points)'] != 0)
        $scorea_B =  $row_rs_sum['SUM(Grades.points)'] * 100/ $row_MaxData['SUM(max_points)'];

      else
        $scorea_B =  0;

				if($scorea_B < 50 )
					echo "F";
				else if( $scorea_B < 52 )
					echo "D-";
				else if( $scorea_B < 56 )
					echo "D";
				else if( $scorea_B< 59 )
					echo "D+";
				else if($scorea_B< 63)
					echo "C-";
				else if( $scorea_B < 73 )
					echo "C";
				else if( $scorea_B < 79 )
					echo "C+";
				else if($scorea_B< 82 )
					echo "B-";
				else if( $scorea_B< 86 )
					echo "B";
				else if( $scorea_B< 89 )
					echo "B+";
				else if( $scorea_B < 92 )
					echo "A-";
				else 
					echo "A";
			
			?></th>
			</tr>
		</table>
	</div>
</div>
</div>
</body>

</html>
<?php
        mysql_free_result($_MaxData);
        mysql_free_result($_AvgData);

mysql_free_result($_ActivityInfo);

mysql_free_result($_GradesInfo);

mysql_free_result($Recordset2);

mysql_free_result($rs_courseName);

mysql_free_result($rs_sum);

mysql_free_result($rs_UserData);

mysql_free_result($rs_total_class_avg);

mysql_free_result($Recordset3);

mysql_free_result($Recordset1);
?>
