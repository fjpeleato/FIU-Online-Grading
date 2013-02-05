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
$query_rs_courseInfo = "SELECT course_number, `section`, name, Users.first_name, Users.last_name, CourseRequests.ID FROM CourseRequests, Users WHERE Users.ID = CourseRequests.professor_ID";
$rs_courseInfo = mysql_query($query_rs_courseInfo, $fiu_grading_system) or die(mysql_error());
$totalRows_rs_courseInfo = mysql_num_rows($rs_courseInfo);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Admin Session: Approve Course - FIU Online Grading</title>
<script src="http://code.jquery.com/jquery-latest.js"></script>
<script src="../assets/js/bootstrap.min.js"></script>
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
	  .hero-unit{
		  padding:9px;
	  }
    </style>

</head>

<body>

<?php include("_admin_Includes/Header.php"); ?>
<?php include("_admin_Includes/sideBar.php"); ?>

<div class="span9">
<div class="hero-unit">
<legend><h1> Approve a Course</h1></legend>
<table class="table table-hover">
 <tr>
 <th>Professor</th>
 <th>Course Number</th>
 <th>Course Name</th>
 <th>Course Section</th>
 <th></th>
 <th></th>
 </tr>
 <tr>
   <?php while ($row_rs_courseInfo = mysql_fetch_assoc($rs_courseInfo)){ ?>
  <td><?php echo $row_rs_courseInfo['first_name']; ?> <?php echo $row_rs_courseInfo['last_name']; ?></td>
     <td><?php echo $row_rs_courseInfo['course_number']; ?>  </td>
     <td><?php echo $row_rs_courseInfo['name']; ?>  </td>
     <td><?php echo $row_rs_courseInfo['section']; ?>  </td>
     <td><a href="ApproveCourse_Check.php?ID=<?php echo $row_rs_courseInfo['ID']; ?>" >
     <button class="btn btn-small btn-primary" type="button"> Approve </button></a></td>
    <td><a href="deny_delete_record.php?ID=<?php echo $row_rs_courseInfo['ID']; ?>" >
    <button class="btn btn-small btn-danger">Deny!</button></a>
</td>
 </tr>	
  <?php } ?>
 </table>

</div>
</div>




</body>
</html>
<?php
mysql_free_result($rs_courseInfo);
?>
