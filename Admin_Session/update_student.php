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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "Update_student")) {
  $updateSQL = sprintf("UPDATE Users SET password_hash=%s, user_name=%s, first_name=%s, last_name=%s WHERE ID=%s",
                       GetSQLValueString($_POST['password_hash'], "text"),
                       GetSQLValueString($_POST['user_name'], "text"),
                       GetSQLValueString($_POST['first_name'], "text"),
                       GetSQLValueString($_POST['last_name'], "text"),
                       GetSQLValueString($_POST['ID'], "int"));

  mysql_select_db($database_fiu_grading_system, $fiu_grading_system);
  $Result1 = mysql_query($updateSQL, $fiu_grading_system) or die(mysql_error());

  $updateGoTo = "Student_Manage_Accounts.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$colname_rs_student = "-1";
if (isset($_GET['ID'])) {
  $colname_rs_student = $_GET['ID'];
}
mysql_select_db($database_fiu_grading_system, $fiu_grading_system);
$query_rs_student = sprintf("SELECT ID, password_hash, user_name, first_name, last_name FROM Users WHERE ID = %s", GetSQLValueString($colname_rs_student, "int"));
$rs_student = mysql_query($query_rs_student, $fiu_grading_system) or die(mysql_error());
$row_rs_student = mysql_fetch_assoc($rs_student);
$totalRows_rs_student = mysql_num_rows($rs_student);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Admin Session: Update Student - FIU Online Grading</title>
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
<script src="../SpryAssets/SpryValidationPassword.js" type="text/javascript"></script>
<link href="../SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
<link href="../SpryAssets/SpryValidationPassword.css" rel="stylesheet" type="text/css" />
</head>

<body>
<?php include("_admin_Includes/Header.php"); ?>
<?php include("_admin_Includes/sideBar.php"); ?>

<div class="span9">
<div class="hero-unit">
<form action="<?php echo $editFormAction; ?>"  method="POST" name="Update_student" class="form-horizontal">
<legend><h1> Update a Student</h1></legend>
<br />
<div class="control-group">
 <label class="control-label" for="first_name">First Name</label>
 <div class="controls"><span id="sprytextfield1">
   <input name="first_name" type="text" id="first_name" value="<?php echo $row_rs_student['first_name']; ?>" placeholder=" First Name" />
   <span class="textfieldRequiredMsg">A value is required.</span></span></div>
</div>

<div class="control-group">
 <label class="control-label" for="last_name">Last Name</label>
 <div class="controls"><span id="sprytextfield2">
   <input name="last_name" type="text" id="last_name" value="<?php echo $row_rs_student['last_name']; ?>" placeholder="Last Name" />
   <span class="textfieldRequiredMsg">A value is required.</span></span></div>
</div>

<div class="control-group">
 <label class="control-label" for="user_name">User Name</label>
 <div class="controls"><span id="sprytextfield3">
   <input name="user_name" type="text" id="user_name" value="<?php echo $row_rs_student['user_name']; ?>" placeholder=" User Name" />
   <span class="textfieldRequiredMsg">A value is required.</span></span></div>
</div>
<div class="control-group">
 <label class="control-label" for="password_hash"> Password </label>
 <div class="controls"><span id="sprypassword1">
 <input name="password_hash" type="password" id="password" value="<?php echo $row_rs_student['password_hash']; ?>" placeholder="password" />
 <span class="passwordRequiredMsg">A value is required.</span><span class="passwordMinCharsMsg">Minimum number of characters not met.</span><span class="passwordMaxCharsMsg">Exceeded maximum number of characters.</span></span></div>

<label></label>
<div class="controls">
  <p>
    <input type="submit" name="submit" id="submit" value="Submit" class="btn btn-primary"/> 
     <a href="Student_Manage_Accounts.php">
     <button name="Cancel" type="button" id="cancel" class="btn btn-danger"> Cancel </button>
     </a> </p>
     <input name="ID" type="hidden" value="<?php echo $row_rs_student['ID']; ?>" />
</div>
<input type="hidden" name="MM_update" value="Update_Course" />
<input type="hidden" name="MM_update" value="Update_student" />
</div>
</form>
<script type="text/javascript">
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1", "none", {validateOn:["blur"]});
var sprytextfield2 = new Spry.Widget.ValidationTextField("sprytextfield2", "none", {validateOn:["blur"]});
var sprytextfield3 = new Spry.Widget.ValidationTextField("sprytextfield3", "none", {validateOn:["blur"]});
var sprypassword1 = new Spry.Widget.ValidationPassword("sprypassword1", {validateOn:["blur"], minChars:4, maxChars:16});
</script>
</body>
</html>
<?php
mysql_free_result($rs_student);
?>
