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

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "Update_Course")) {
  $updateSQL = sprintf("UPDATE Courses SET course_number=%s, `section`=%s, name=%s, max_enrollment=%s WHERE ID=%s",
                       GetSQLValueString($_POST['Course_number'], "text"),
                       GetSQLValueString($_POST['section'], "text"),
                       GetSQLValueString($_POST['name'], "text"),
                       GetSQLValueString($_POST['max_enrollment'], "int"),
                       GetSQLValueString($_POST['ID'], "int"));

  mysql_select_db($database_fiu_grading_system, $fiu_grading_system);
  $Result1 = mysql_query($updateSQL, $fiu_grading_system) or die(mysql_error());

  $updateGoTo = "Edit_Courses.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$colname_rs_courseInfo = "-1";
if (isset($_GET['ID'])) {
  $colname_rs_courseInfo = $_GET['ID'];
}
mysql_select_db($database_fiu_grading_system, $fiu_grading_system);
$query_rs_courseInfo = sprintf("SELECT ID, course_number, `section`, name, max_enrollment FROM Courses WHERE ID = %s", GetSQLValueString($colname_rs_courseInfo, "int"));
$rs_courseInfo = mysql_query($query_rs_courseInfo, $fiu_grading_system) or die(mysql_error());
$row_rs_courseInfo = mysql_fetch_assoc($rs_courseInfo);
$totalRows_rs_courseInfo = mysql_num_rows($rs_courseInfo);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Admin Session: Update Course - FIU Online Grading</title>
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
<link href="../SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
</head>

<body>
<?php include("_admin_Includes/Header.php"); ?>
<?php include("_admin_Includes/sideBar.php"); ?>

<div class="span9">
<div class="hero-unit">
<form action="<?php echo $editFormAction; ?>" method="POST" name="Update_Course" class="form-horizontal">
<legend><h1> Update a Course Information </h1></legend>

<div class="control-group">
 <label class="control-label" for="course_Number"> Course Number</label>
 <div class="controls"><span id="sprytextfield1">
 <input name="Course_number" type="text" id="Course_number" value="<?php echo $row_rs_courseInfo['course_number']; ?>" placeholder=" Insert a Course Number" />
 <span class="textfieldRequiredMsg">A value is required.</span><span class="textfieldInvalidFormatMsg">Invalid format. Three Uppercase alphabetic characters and three Numeric values. Example: COP3333</span></span></div>
</div>

<div class="control-group">
 <label class="control-label" for="name"> Course Name</label>
 <div class="controls"><span id="sprytextfield2">
   <input name="name" type="text" id="name" value="<?php echo $row_rs_courseInfo['name']; ?>" placeholder=" Insert a Course name" />
   <span class="textfieldRequiredMsg">A value is required.</span></span></div>
</div>

<div class="control-group">
 <label class="control-label" for="section">Course Section</label>
 <div class="controls"><span id="sprytextfield3">
 <input name="section" type="text" id="section" value="<?php echo $row_rs_courseInfo['section']; ?>" placeholder=" Insert a Course section" />
 <span class="textfieldRequiredMsg">A value is required.</span><span class="textfieldInvalidFormatMsg">Invalid format. Any Uppercase alphabetic characters followed by two numeric value Example U21.</span></span></div>
</div>
<div class="control-group">
 <label class="control-label" for="max_enrollment">Course Max Enrollment</label>
 <div class="controls"><span id="sprytextfield4">
 <input name="max_enrollment" type="text" value="<?php echo $row_rs_courseInfo['max_enrollment']; ?>" id="max_enrollment" placeholder=" Insert the Max amount of student" />
 <span class="textfieldRequiredMsg">A value is required.</span><span class="textfieldInvalidFormatMsg">Invalid format.</span><span class="textfieldMinValueMsg">The entered value is less than the minimum required.</span><span class="textfieldMaxValueMsg">The entered value is greater than the maximum allowed.</span></span></div>
</div>
<div class="controls">
  <p>
    <input type="submit" name="submit" id="submit" value="Submit" class="btn btn-primary"/> 
     <a href="Edit_Courses.php"><button name="Cancel" type="button" id="cancel" class="btn btn-danger"> Cancel </button></a>
  </p> 
</div>
<input type="hidden" name="MM_update" value="Update_Course" />
</div>
  <input name="ID" type="hidden" id="ID" value="<?php echo $row_rs_courseInfo['ID']; ?>"/>
</form>

</div>
</div>
<script type="text/javascript">
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1", "custom", {validateOn:["blur"], pattern:"AAA0000"});
var sprytextfield2 = new Spry.Widget.ValidationTextField("sprytextfield2", "none", {validateOn:["blur"]});
var sprytextfield3 = new Spry.Widget.ValidationTextField("sprytextfield3", "custom", {validateOn:["blur"], pattern:"A00"});
var sprytextfield4 = new Spry.Widget.ValidationTextField("sprytextfield4", "integer", {validateOn:["blur"], minValue:1, maxValue:999});
</script>
</body>
</html>
<?php
mysql_free_result($rs_courseInfo);
?>
