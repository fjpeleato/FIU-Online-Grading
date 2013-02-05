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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "create_professor")) {
  $insertSQL = sprintf("INSERT INTO Users (password_hash, user_name, first_name, last_name, type) VALUES (%s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['password'], "text"),
                       GetSQLValueString($_POST['userName'], "text"),
                       GetSQLValueString($_POST['FName'], "text"),
                       GetSQLValueString($_POST['LName'], "text"),
                       GetSQLValueString($_POST['type'], "int"));

  mysql_select_db($database_fiu_grading_system, $fiu_grading_system);
  $Result1 = mysql_query($insertSQL, $fiu_grading_system) or die(mysql_error());

  $insertGoTo = "Prof_Manage_Accounts.php";
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
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Admin Session: Create Professor - FIU Online Grading</title>

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
<form action="<?php echo $editFormAction; ?>" method="POST" name="create_professor" class="form-horizontal">
<legend><h1> Create a Professor</h1></legend>
<br />
<div class="control-group">
 <label class="control-label" for="FName">First Name</label>
 <div class="controls"><span id="sprytextfield1">
   <input name="FName" type="text" id="FName" placeholder=" First Name" />
   <span class="textfieldRequiredMsg">A value is required.</span></span></div>
</div>

<div class="control-group">
 <label class="control-label" for="LName">Last Name</label>
 <div class="controls"><span id="sprytextfield2">
   <input name="LName" type="text" id="LName" placeholder="Last Name" />
   <span class="textfieldRequiredMsg">A value is required.</span></span></div>
</div>

<div class="control-group">
 <label class="control-label" for="userName">User Name</label>
 <div class="controls"><span id="sprytextfield3">
   <input name="userName" type="text" id="userName"  placeholder=" User Name" />
   <span class="textfieldRequiredMsg">A value is required.</span></span></div>
</div>
<div class="control-group">
 <label class="control-label" for="password"> Password </label>
 <div class="controls"><span id="sprypassword1">
 <input name="password" type="password" id="password" placeholder="Create a password" />
 <span class="passwordRequiredMsg">A value is required.</span><span class="passwordMinCharsMsg">Minimum number of characters not met.</span><span class="passwordMaxCharsMsg">Exceeded maximum number of characters.</span></span></div>
</div>

<label></label>
<div class="controls">
  <p>
    <input type="submit" name="submit" id="submit" value="Submit" class="btn btn-primary"/> 
    <a href="Prof_Manage_Accounts.php">
     <button name="Cancel" type="button" id="cancel" class="btn btn-danger"> Cancel </button></a>
  </p>
</div>
<input name="type" type="hidden" value="1" />
<input type="hidden" name="MM_insert" value="create_professor" />
</div>
</form>

</div>
</div>
<script type="text/javascript">
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1", "none", {validateOn:["blur"]});
var sprytextfield2 = new Spry.Widget.ValidationTextField("sprytextfield2", "none", {validateOn:["blur"]});
var sprytextfield3 = new Spry.Widget.ValidationTextField("sprytextfield3", "none", {validateOn:["blur"]});
var sprypassword1 = new Spry.Widget.ValidationPassword("sprypassword1", {validateOn:["blur"], minChars:4, maxChars:16});
</script>
</body>
</html>