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
$query_Recordset1 = "SELECT ID, first_name, last_name FROM Users WHERE type = 2 ORDER BY first_name ASC";
$Recordset1 = mysql_query($query_Recordset1, $fiu_grading_system) or die(mysql_error());
$totalRows_Recordset1 = mysql_num_rows($Recordset1);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Admin Session: Manage Student Accounts - FIU Online Grading</title>
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
		padding-left: 40px;
		padding-right: 40px;
	  	padding: 9px;
		 }
    </style>
</head>

<body>

<?php include("_admin_Includes/Header.php"); ?>
<?php include("_admin_Includes/sideBar.php"); ?>

<div class="span9">
<div class="hero-unit">
<h1> Manage Student Accounts </h1>

<table class="table table-hover">
<tr>
<th>First Name</th>
<th>Last Name</th>
<th>Student ID</th>
<th></th>
<th></th>
</tr>
  <?php while ($row_Recordset1 = mysql_fetch_assoc($Recordset1)) { ?>
<tr>
    <td><?php echo $row_Recordset1['first_name']; ?></td>
    <td><?php echo $row_Recordset1['last_name']; ?></td>
    <td>FIU-1231-<?php echo $row_Recordset1['ID']; ?></td>
    <td><a href="update_student.php?ID=<?php echo $row_Recordset1['ID']; ?>">
      <button class="btn btn-small btn-primary" type="button"> EDIT</button>
    </a></td>
    <td><a href="delete_student.php?ID=<?php echo $row_Recordset1['ID']; ?>">
    <button class="btn btn-small btn-danger" type="button"> DELETE</button>
      </a>
      </td> 
</tr>
<?php }  ?>
</table>
<p> <a href="create_student.php">Create a new student</a></p>
 
</body>
</html>
<?php
mysql_free_result($Recordset1);
?>
