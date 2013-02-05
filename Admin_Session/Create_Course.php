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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "Create_course")) {
  $insertSQL = sprintf("INSERT INTO Courses (professor_ID, course_number, `section`, name, max_enrollment) VALUES (%s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['professor_ID'], "int"),
                       GetSQLValueString($_POST['Course_Number'], "text"),
                       GetSQLValueString($_POST['section'], "text"),
                       GetSQLValueString($_POST['name'], "text"),
                       GetSQLValueString($_POST['max_enrollment'], "int"));

  mysql_select_db($database_fiu_grading_system, $fiu_grading_system);
  $Result1 = mysql_query($insertSQL, $fiu_grading_system) or die(mysql_error());

  $insertGoTo = "Edit_Courses.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

mysql_select_db($database_fiu_grading_system, $fiu_grading_system);
$query_rs_Teachers = "SELECT ID, first_name, last_name FROM Users WHERE type = 1";
$rs_Teachers = mysql_query($query_rs_Teachers, $fiu_grading_system) or die(mysql_error());
$totalRows_rs_Teachers = mysql_num_rows($rs_Teachers);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Admin Session: Create Course - FIU Online Grading</title>

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


<script src="../SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<link href="../SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
</head>

<body>

<?php include("_admin_Includes/Header.php"); ?>
<?php include("_admin_Includes/sideBar.php"); ?>
        
<div class="span9">
<div class="hero-unit">
            
<form action="<?php echo $editFormAction; ?>" method="POST" name="Create_course" class="form-horizontal">
         <legend><h1> Create a Course</h1></legend>
  
         <div class="control-group">
 <label class="control-label" for="Course_Number">Course Number</label>
 <div class="controls"><span id="sprytextfield1">
 <input name="Course_Number" type="text" id="Course_Number" placeholder=" Insert a Course name" />
 <span class="textfieldRequiredMsg">A value is required.</span><span class="textfieldInvalidFormatMsg">Invalid format. Three Uppercase alphabetic characters and three Numeric values. EX, COP3333 </span></span></div>
</div>
       
         <div class="control-group">
 <label class="control-label" for="name">Course name</label>
 <div class="controls"><span id="sprytextfield4">
   <input name="name" type="text" id="name" placeholder=" Insert a Course name" />
   <span class="textfieldRequiredMsg">A value is required.</span></span></div>
</div>

<div class="control-group">
 <label class="control-label" for="max_enrollment">Course Max Enrollment</label>
 <div class="controls"><span id="sprytextfield2">
 <input name="max_enrollment" type="text" id="max_enrollment" placeholder=" Insert the Max amount of student" />
 <span class="textfieldRequiredMsg">A value is required.</span><span class="textfieldInvalidFormatMsg">Invalid format.</span><span class="textfieldMinValueMsg">The entered value is less than the minimum required.</span><span class="textfieldMaxValueMsg">The entered value is greater than the maximum allowed.</span></span></div>
</div>

<div class="control-group">
 <label class="control-label" for="section">Course Section</label>
 <div class="controls"><span id="sprytextfield3">
 <input name="section" type="text" id="section" placeholder=" Insert a Course section" />
 <span class="textfieldRequiredMsg">A value is required.</span><span class="textfieldInvalidFormatMsg">Invalid format. A value is required.Invalid format. Any Uppercase alphabetic characters followed by two numeric value Example U21.</span></span></div>
</div>


 <div class="control-group">
 <label class="control-label" for="course_Number">Select a Professor</label>
 <div class="controls">
 
      <select name="professor_ID">
   <?php  while ($row_rs_Teachers = mysql_fetch_assoc($rs_Teachers)){ ?>
       <option id="professor_ID" value="<?php echo $row_rs_Teachers['ID']; ?>"> <?php echo $row_rs_Teachers['first_name']; ?> <?php echo $row_rs_Teachers['last_name']; ?></option>
     <?php } ?>
      </select>
      <a href="create_Prof.php">
        Need to create a professor?
        </a>

 </div>
</div>
<div class="control-group">
<div class="controls">
        <input type="submit" name="submit" id="submit" value="Submit" class="btn btn-primary"/> 
        <a href="adminSession.php">
        <button name="Cancel" type="button" id="cancel" class="btn btn-danger"> Cancel </button>
        </a>
        </div>
      </div>
<input type="hidden" name="MM_insert" value="Create_course" />
 </form>
</div>
    </div>
<script type="text/javascript">
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1", "custom", {pattern:"AAA0000", validateOn:["blur"]});
var sprytextfield2 = new Spry.Widget.ValidationTextField("sprytextfield2", "integer", {minValue:1, maxValue:999, validateOn:["blur"]});
var sprytextfield3 = new Spry.Widget.ValidationTextField("sprytextfield3", "custom", {pattern:"A00", validateOn:["blur"]});
var sprytextfield4 = new Spry.Widget.ValidationTextField("sprytextfield4", "none", {validateOn:["blur"]});
</script>
</body>
</html>
<?php
mysql_free_result($rs_Teachers);
?>
