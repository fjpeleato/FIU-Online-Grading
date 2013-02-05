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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "change_pw")) {
  $updateSQL = sprintf("UPDATE Users SET password_hash=%s WHERE ID=%s",
                       GetSQLValueString($_POST['reNewPass'], "text"),
                       GetSQLValueString($_POST['username'], "int"));

  mysql_select_db($database_fiu_grading_system, $fiu_grading_system);
  $Result1 = mysql_query($updateSQL, $fiu_grading_system) or die(mysql_error());

  $updateGoTo = "studentSession.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
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

$colname_Recordset1 = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_Recordset1 = $_SESSION['MM_Username'];
}
mysql_select_db($database_fiu_grading_system, $fiu_grading_system);
$query_Recordset1 = sprintf("SELECT Courses.course_number, Courses.name, Courses.`section`, Users.first_name, Users.last_name, Courses.ID FROM Courses, Users WHERE Courses.ID IN (SELECT CourseEnrollment.course_ID FROM CourseEnrollment WHERE CourseEnrollment.student_ID IN (SELECT Users.ID  FROM Users WHERE user_name = %s) ) AND Courses.professor_ID =Users.ID", GetSQLValueString($colname_Recordset1, "text"));
$Recordset1 = mysql_query($query_Recordset1, $fiu_grading_system) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta content="text/html; charset=UTF-8" http-equiv="Content-Type" />
<title>Student Session: Change Password - FIU Online Grading</title>
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
<script src="../SpryAssets/SpryValidationConfirm.js" type="text/javascript"></script>
<script src="../SpryAssets/SpryValidationPassword.js" type="text/javascript"></script>
<link href="../SpryAssets/SpryValidationConfirm.css" rel="stylesheet" type="text/css" />
<link href="../SpryAssets/SpryValidationPassword.css" rel="stylesheet" type="text/css" />
</head>

<body>

<?php include("_student_Includes/Header.php"); ?>
<?php include("_student_Includes/sideBar.php"); ?>
<div class="span9">
	<div class="hero-unit">
		<h1>Change Password</h1>
		<br>
	  <form action="<?php echo $editFormAction; ?>" method="POST" class="form-horizontal" name="change_pw">
<div class="control-group">
				<label class="control-label" for="newPass">New Password</label>
				<div class="controls"><span id="sprypassword1">
                <input id="newPass" name="newPass" placeholder="New Password" type="password" />
                <span class="passwordRequiredMsg">A value is required.</span><span class="passwordMinCharsMsg">Minimum of 8 number of characters needed.</span><span class="passwordInvalidStrengthMsg">The password doesn't meet the specified strength. At least one cap. letter and one number needed.</span></span></div>
			</div>
		<div class="control-group">
    <label class="control-label" for="reNewPass">Retype New Password</label>
				<div class="controls"><span id="spryconfirm2">
				  <input id="reNewPass" name="reNewPass" placeholder="Retype New Password" type="password" />
			    <span class="confirmRequiredMsg">A value is required.</span><span class="confirmInvalidMsg">The values don't match.</span></span></div>
		  </div>
  <div class="controls">
  <p>
    <input type="submit" name="submit" id="submit" value="Submit" class="btn btn-primary"/> 
     <a href="studentSession.php"><button name="Cancel" type="button" id="cancel" class="btn btn-danger"> Cancel </button></a>
  </p>
  <p>&nbsp;</p>
  </div>
            <input name="username" type="hidden" value="<?php echo $row_rs_UserData['ID']; ?>" />
	      <input type="hidden" name="MM_update" value="change_pw" />
        </form>
	</div>
</div>
</div>
<script type="text/javascript">
var spryconfirm2 = new Spry.Widget.ValidationConfirm("spryconfirm2", "newPass", {validateOn:["blur"]});
var sprypassword1 = new Spry.Widget.ValidationPassword("sprypassword1", {minChars:8, minUpperAlphaChars:1, minNumbers:1, validateOn:["blur"]});
</script>
</body>

</html>
<?php
mysql_free_result($rs_UserData);

mysql_free_result($Recordset1);
?>
