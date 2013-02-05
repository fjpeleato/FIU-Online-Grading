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

$colname_Recordset1 = "1";
if (isset($_GET['course_ID'])) {
  $colname_Recordset1 = $_GET['course_ID'];
}
mysql_select_db($database_fiu_grading_system, $fiu_grading_system);
$query_Recordset1 = sprintf("SELECT Users.ID, Users.first_name, Users.last_name FROM Users WHERE Users.type = 2 AND Users.ID NOT IN (SELECT CourseEnrollment.student_ID FROM CourseEnrollment WHERE course_ID = %s)", GetSQLValueString($colname_Recordset1, "int"));
$Recordset1 = mysql_query($query_Recordset1, $fiu_grading_system) or die(mysql_error());
$totalRows_Recordset1 = mysql_num_rows($Recordset1);

$username  = "-1";
if (isset($_SESSION['MM_Username'])) {
  $username = $_SESSION['MM_Username'];
}
mysql_select_db($database_fiu_grading_system, $fiu_grading_system);
$query_UserData = sprintf("SELECT ID, first_name, last_name FROM Users WHERE user_name = %s", GetSQLValueString($username, "text"));
$_UserData = mysql_query($query_UserData, $fiu_grading_system) or die(mysql_error());
$row_UserData = mysql_fetch_assoc($_UserData);

//course information query
$query_courseInfo = sprintf("SELECT course_number, name, section, ID FROM Courses WHERE professor_ID = %d", GetSQLValueString($row_UserData['ID'], "int"));
$_CourseInfo = mysql_query($query_courseInfo, $fiu_grading_system) or die(mysql_error());

//activities query
$query_activityInfo = sprintf("SELECT * FROM Activities WHERE course_ID = %d", GetSQLValueString($_GET["course_ID"], "int"));
$_ActivityInfo = mysql_query($query_activityInfo, $fiu_grading_system) or die(mysql_error());
$row_ActivityInfo = mysql_fetch_assoc($_ActivityInfo);

//student query
$query_studentInfo = sprintf("SELECT * FROM Users WHERE ID IN (SELECT student_ID FROM CourseEnrollment WHERE course_ID = %d)", GetSQLValueString($_GET["course_ID"], "int"));
$_StudentInfo = mysql_query($query_studentInfo, $fiu_grading_system) or die(mysql_error());

//Non-enrolled students query
$query_studentInfo2 = sprintf("SELECT first_name, last_name FROM Users WHERE Users.type = 2 AND ID NOT IN (SELECT CourseEnrollment.student_ID FROM CourseEnrollment WHERE course_ID = %d)", GetSQLValueString($_GET["course_ID"], "int"));
$_StudentInfo2 = mysql_query($query_studentInfo, $fiu_grading_system) or die(mysql_error());
$row_StudentInfo2 = mysql_fetch_assoc($_StudentInfo2);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

  <head>
  <meta content="text/html; charset=UTF-8" http-equiv="Content-Type" />
  <title>Professor Session: View Course - FIU Online Grading</title>
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
<script src="../assets/js/jquery-1.8.2.js" type="text/javascript"></script>
<script src="../assets/js/bootstrap-dropdown.js" type="text/javascript"></script>
</head>

<body>

<?php include("_prof_Includes/header.php"); ?>
<?php include("_prof_Includes/side_bar.php"); ?>
<div class="span9">
  <div class="hero-unit">
  <?php
       if(mysql_num_rows($_CourseInfo) > 0)
       {
         //reset _CourseInfo pointer
         mysql_data_seek($_CourseInfo, 0);
         $row_CourseInfo = mysql_fetch_assoc($_CourseInfo);
         //search through course info until we find info on the current course
         while ($row_CourseInfo['ID'] != $_GET["course_ID"])
         {
           $row_CourseInfo = mysql_fetch_assoc($_CourseInfo);
         }
       }
  ?>
  <h2> <?php echo $row_CourseInfo['course_number'], " - ", $row_CourseInfo['name'], " (", $row_CourseInfo['section'], ")"; ?></h2>
  
  <br><a href="profNewActivity.php?course_ID=<?php echo $_GET["course_ID"]; ?>"><button class="btn btn-small btn-primary" type="button">New activity
  </button></a>

<div class="btn-group">  
  <a class="btn btn-small btn-danger dropdown-toggle" data-toggle="dropdown" href="#">
  Remove Activity <span class="caret"></span></a>
  <ul aria-labelledby="dropdownMenu" class="dropdown-menu" role="menu">
    <?php
         //print out all activities
         if(mysql_num_rows($_ActivityInfo) > 0)
         {
           //reset activity info seek pointer
           mysql_data_seek($_ActivityInfo, 0);
           while ($row_ActivityInfo = mysql_fetch_assoc($_ActivityInfo)) {
  ?>
		   <li><a href="profRemoveAct.php?activity_ID=<?php echo $row_ActivityInfo["ID"], "&course_ID=", $_GET["course_ID"]; ?>" tabindex="-1"><?php echo  $row_ActivityInfo['name']; ?></a></li>
<?php }}?>
  </ul>
  </div>
  
  <div class="btn-group">
  <a class="btn btn-small btn-primary dropdown-toggle" data-toggle="dropdown" href="#">
  Edit grades <span class="caret"></span></a>
  <ul aria-labelledby="dropdownMenu" class="dropdown-menu" role="menu">
  <?php
         //print out all activities
         if(mysql_num_rows($_ActivityInfo) > 0)
         {
           //reset activity info seek pointer
           mysql_data_seek($_ActivityInfo, 0);
           while ($row_ActivityInfo = mysql_fetch_assoc($_ActivityInfo)) {
  ?>
		   <li><a href="profEditGrades.php?activity_ID=<?php echo $row_ActivityInfo["ID"], "&course_ID=", $_GET["course_ID"]; ?>" tabindex="-1"><?php echo  $row_ActivityInfo['name']; ?></a></li>
<?php }}?>
  </ul>
  </div>
  
  <div class="btn-group">
  <a class="btn btn-small btn-primary dropdown-toggle" data-toggle="dropdown" href="#">
  Add student <span class="caret"></span></a>
  <ul aria-labelledby="dropdownMenu" class="dropdown-menu" role="menu">

    <?php while ($row_Recordset1 = mysql_fetch_assoc($Recordset1)){ ?>
      <li><a href="profStudentOp.php?student_ID=<?php echo $row_Recordset1['ID'], "&course_ID=", $_GET["course_ID"], "&op=1"; ?>" tabindex="-1">
	  <?php echo $row_Recordset1['first_name'], " " ,$row_Recordset1["last_name"] ?></a></li>
      <?php }  ?>
  </ul>
  </div>
  
  <div class="btn-group">
  <a class="btn btn-small btn-danger dropdown-toggle" data-toggle="dropdown" href="#">
  Remove student <span class="caret"></span></a>
  <ul aria-labelledby="dropdownMenu" class="dropdown-menu" role="menu">
    <?php
	if(mysql_num_rows($_StudentInfo) > 0) 
	{
		mysql_data_seek($_StudentInfo, 0);
		while($row_StudentInfo = mysql_fetch_assoc($_StudentInfo))
		{
			?>
			<li><a href="profStudentOp.php?student_ID=<?php echo $row_StudentInfo['ID'], "&course_ID=", $_GET["course_ID"], "&op=2"; ?>" tabindex="-1"><?php echo $row_StudentInfo["first_name"], " ", $row_StudentInfo["last_name"]; ?></a></li>
			<?php
		}
	}
	?>
  </ul>
  </div>
  
  <br>
  <table class="table table-hover">
  <tr>
  <th>Student name</th>
  <th>ID</th>
  <?php
         //print out all activities
         if(mysql_num_rows($_ActivityInfo) > 0)
         {
           //reset activity info seek pointer
           mysql_data_seek($_ActivityInfo, 0);
           while ($row_ActivityInfo = mysql_fetch_assoc($_ActivityInfo)) {
  ?>
           <th><?php echo  $row_ActivityInfo['name']; ?></th>
<?php }}?>
  <th>Total</th>
  <th>Letter Grade</th>
  </tr>
  <?php
   //print out grades for all students
  if(mysql_num_rows($_StudentInfo) > 0) 
  {
    mysql_data_seek($_StudentInfo, 0);

    //max points query
    $query_activityTotalInfo = sprintf("SELECT SUM(max_points) FROM Activities WHERE course_ID = %d", GetSQLValueString($_GET["course_ID"], "int"));
    $_ActivityTotalInfo = mysql_query($query_activityTotalInfo, $fiu_grading_system) or die(mysql_error());
    $row_activityTotalInfo = mysql_fetch_assoc($_ActivityTotalInfo);

    while($row_StudentInfo = mysql_fetch_assoc($_StudentInfo))
    {
  ?>
    <tr>
      <td><?php echo $row_StudentInfo["first_name"], " ", $row_StudentInfo["last_name"] ?></td>
      <td><?php echo $row_StudentInfo["ID"] ?></td>
      <?php
        //grades query
        $query_gradesInfo = sprintf("SELECT * FROM Grades WHERE student_ID = %d AND activity_ID IN (SELECT ID FROM Activities WHERE course_ID = %d)", GetSQLValueString($row_StudentInfo["ID"], "int"), GetSQLValueString($_GET["course_ID"], "int"));
        $_GradesInfo = mysql_query($query_gradesInfo, $fiu_grading_system) or die(mysql_error());
        
        //print out grades for specific student
        $activity_count = mysql_num_rows($_ActivityInfo);
        $counter = 0;

        if(mysql_num_rows($_ActivityInfo) > 0)
        {
   
        mysql_data_seek($_ActivityInfo, 0);
        while ($counter < $activity_count)
        {
          $row_ActivityInfo = mysql_fetch_assoc($_ActivityInfo);
      ?>
              <td><?php 
          if(mysql_num_rows($_GradesInfo) > 0)
          {
          //get correct grade for student
          mysql_data_seek($_GradesInfo, 0);
          $row_gradesInfo = mysql_fetch_assoc($_GradesInfo);
          //find the corresponding grade for the current activity
          while($row_gradesInfo["activity_ID"] != $row_ActivityInfo["ID"])
          {
            $row_gradesInfo = mysql_fetch_assoc($_GradesInfo);
              if(!$row_gradesInfo)
                break;
          }
                 //print out the grade if it exists
                 if($row_gradesInfo)
                   echo $row_gradesInfo["points"], " / ", $row_ActivityInfo["max_points"];
                                          }?></td>
      <?php
          $counter++;
        }
        }
        //free gradesInfo results
        mysql_free_result($_GradesInfo);
        
        //query for the total points of current student
        $query_gradeTotalInfo = sprintf("SELECT SUM(points) FROM Grades WHERE student_ID = %d AND activity_ID IN (SELECT ID FROM Activities WHERE course_ID = %d)", GetSQLValueString($row_StudentInfo["ID"], "int"), GetSQLValueString($_GET["course_ID"], "int"));
        $_GradeTotalInfo = mysql_query($query_gradeTotalInfo, $fiu_grading_system) or die(mysql_error());
        $row_gradeTotalInfo = mysql_fetch_assoc($_GradeTotalInfo);
      ?>
        <td> <?php
           if(isset($row_gradeTotalInfo["SUM(points)"]))
             echo $row_gradeTotalInfo["SUM(points)"];
           else
             echo " - ";
        
        echo " / ", $row_activityTotalInfo["SUM(max_points)"];  ?> </td>
      <td><?php
            //print out student's letter grade
            if($row_activityTotalInfo["SUM(max_points)"] != 0)
                $scorea_A = $row_gradeTotalInfo["SUM(points)"] * 100/ $row_activityTotalInfo["SUM(max_points)"]; 
            else
                $scorea_A = 0;                                                                                                    
	
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
  <?php
        mysql_free_result($_GradeTotalInfo);
    }

    mysql_free_result($_ActivityTotalInfo);
  }
  ?>
  <th>Current Average</th>
  <th>-</th>
  <?php

  
   //query for assignment averages
  $query_assignmentAVGInfo = sprintf("SELECT AVG(points), activity_ID FROM Grades WHERE activity_ID IN (SELECT ID FROM Activities WHERE course_ID = %d) GROUP BY activity_ID", GetSQLValueString($_GET["course_ID"], "int"));
  $_AssignmentAVGInfo = mysql_query($query_assignmentAVGInfo, $fiu_grading_system) or die(mysql_error());
  $row_AssignmentAVGInfo = mysql_fetch_assoc($_AssignmentAVGInfo);
  $sum_averages = 0;
  $sum_totals = 0;
  //print out averages for each assignment
  if(mysql_num_rows($_ActivityInfo) > 0)
  {
    mysql_data_seek($_ActivityInfo, 0);
    while ($row_ActivityInfo = mysql_fetch_assoc($_ActivityInfo))
    {
      //reset 
      
  if(mysql_num_rows($_AssignmentAVGInfo) > 0)
  {
    mysql_data_seek($_AssignmentAVGInfo, 0);
    $row_AssignmentAVGInfo = mysql_fetch_assoc($_AssignmentAVGInfo);
    //find the corresponding average grade for the current activity
    while($row_AssignmentAVGInfo["activity_ID"] != $row_ActivityInfo["ID"])
    {
      $row_AssignmentAVGInfo = mysql_fetch_assoc($_AssignmentAVGInfo);
      if(!$row_AssignmentAVGInfo)
        break;
    }
  }
  ?>
    <th><?php echo (int)$row_AssignmentAVGInfo["AVG(points)"], " / ", $row_ActivityInfo["max_points"]; ?> </th>
  <?php
//keep a sum of the averages and totals
$sum_averages +=  (int)$row_AssignmentAVGInfo["AVG(points)"];
$sum_totals +=  (int)$row_ActivityInfo["max_points"];
  }
  
  mysql_free_result($_AssignmentAVGInfo);
  ?>
<th><?php echo $sum_averages, " / ", $sum_totals; ?></th>
  <th><?php
        //print out letter grade of the average
        $scorea_A = $sum_averages * 100/ $sum_totals; 
	
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
  }
 ?></th>
  </tr>
  <tr>
  <td>  </td>
  </tr>
  </table>
  </div>
  </div>
  </div>
</div>

  </body>

  </html>
<?php
mysql_free_result($Recordset1);

  mysql_free_result($_StudentInfo);
  mysql_free_result($_ActivityInfo);
  mysql_free_result($_UserData);
  mysql_free_result($_CourseInfo);
?>
