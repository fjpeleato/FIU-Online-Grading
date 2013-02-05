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
$query_rs_Prof = "SELECT ID, first_name, last_name FROM Users WHERE type = 1";
$rs_Prof = mysql_query($query_rs_Prof, $fiu_grading_system) or die(mysql_error());
$totalRows_rs_Prof = mysql_num_rows($rs_Prof);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Admin Session: Manage Accounts - FIU Online Grading</title>
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
<h1> Manage Professor Accounts </h1>

<table class="table table-hover">
    <tr>
	<th>First Name</th>
      <th>Last Name</th>
      <th></th>
      <th></th>
    </tr>
<?php while ($row_rs_Prof = mysql_fetch_assoc($rs_Prof)){ ?>
<tr>
    	
    	<td><?php echo $row_rs_Prof['first_name']; ?></td>
   	    <td><?php echo $row_rs_Prof['last_name']; ?></td>
        
  <td><a href="update_prof.php?ID=<?php echo $row_rs_Prof['ID']; ?>"><button class="btn btn-small btn-primary" type="button"> EDIT </button></a></td>
    	  <td><a href="delete_Professor.php?ID=<?php echo $row_rs_Prof['ID']; ?>">
    	    <button class="btn btn-small btn-danger" type="button"> DELETE </button>
  </a></td>
    	  <?php } ?>
</tr>        
</table>
<p> <a href="create_Prof.php">Create a new professor</a></p>
</body>
</html>
<?php
mysql_free_result($rs_Prof);
?>
