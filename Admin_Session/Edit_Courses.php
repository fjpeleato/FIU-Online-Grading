<?php include("_admin_Includes/logoutAndValidation.php") ?>
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

mysql_select_db($database_fiu_grading_system, $fiu_grading_system);
$query_rs_List_of_Courses = "SELECT Courses.ID, Courses.course_number, Courses.section, Courses.name, Users.first_name, Users.last_name
FROM Courses, Users
Where Courses.professor_ID = Users.ID";
$rs_List_of_Courses = mysql_query($query_rs_List_of_Courses, $fiu_grading_system) or die(mysql_error());
$totalRows_rs_List_of_Courses = mysql_num_rows($rs_List_of_Courses);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Admin Session: Edit Courses - FIU Online Grading</title>
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


<?php include("_admin_Includes/Header.php"); ?>
<?php include("_admin_Includes/sideBar.php"); ?>

<div class="span9">
<div class="hero-unit">
<h1> Edit Courses </h1>
<p>&nbsp;</p>


  <table class="table table-hover">
    <tr>
      
      <th>Course Number</th>
      <th>Course Name</th>
      <th>Course Section</th>
      <th>Professor</th>
      <th></th>
      <th></th>
    <tr>
    <tr>
    <?php while ($row_rs_List_of_Courses = mysql_fetch_assoc($rs_List_of_Courses)){ ?>
      <td><?php echo $row_rs_List_of_Courses['course_number']; ?></td>
      <td><?php echo $row_rs_List_of_Courses['name']; ?></td>
      <td><?php echo $row_rs_List_of_Courses['section']; ?></td>
      <td><?php echo $row_rs_List_of_Courses['first_name']; ?> <?php echo $row_rs_List_of_Courses['last_name']; ?></td>
<td> <a href="Update_Course_form.php?ID=<?php echo $row_rs_List_of_Courses['ID']; ?>">
        <button class="btn btn-small btn-primary" type="button"> EDIT</button></a></td>
        <td> <a href="Course_delete_Record.php?ID=<?php echo $row_rs_List_of_Courses['ID']; ?>">
          <button class="btn btn-small btn-danger" type="button"> DELETE</button>
        </a></td>
    </a></tr>
    <?php } ?>
  </table>
  <p> <a href="Create_Course.php">Insert a New Course</a></p>
</body>
</html>
<?php
mysql_free_result($rs_List_of_Courses);
?>
