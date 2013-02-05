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
$_CourseInfo  = mysql_query($query_courseInfo, $fiu_grading_system) or die(mysql_error());
$row_courseInfo = mysql_fetch_assoc($_CourseInfo);

//activity information query
$query_activityInfo = sprintf("SELECT * FROM Activities WHERE ID = %d", GetSQLValueString($_GET["activity_ID"], "int"));
$_ActivityInfo = mysql_query($query_activityInfo, $fiu_grading_system) or die(mysql_error());
$row_ActivityInfo = mysql_fetch_assoc($_ActivityInfo);

//student query
$query_studentInfo = sprintf("SELECT * FROM Users WHERE ID IN (SELECT student_ID FROM CourseEnrollment WHERE course_ID = %d)", GetSQLValueString($_GET["course_ID"], "int"));
$_StudentInfo = mysql_query($query_studentInfo, $fiu_grading_system) or die(mysql_error());

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "edit_grades")) {

  if(mysql_num_rows($_StudentInfo) > 0) 
  {
    mysql_data_seek($_StudentInfo, 0);

    while($row_StudentInfo = mysql_fetch_assoc($_StudentInfo))
    {
      if($_POST[$row_StudentInfo['ID']] == "")
        continue;

      //check if the grade exists
      $checkQuery = sprintf("SELECT points FROM Grades WHERE student_ID = %d AND activity_ID = %d;",
                           GetSQLValueString($row_StudentInfo['ID'], "int"),
                           GetSQLValueString($_GET['activity_ID'], "int"));

      $check = mysql_query($checkQuery) or die(mysql_error());

      //update if the grade exists, insert otherwise
      if(mysql_num_rows($check) > 0)
      {                          
        $insertSQL = sprintf("UPDATE Grades SET points = %d WHERE student_ID = %d AND activity_ID = %d;",
                             GetSQLValueString($_POST[$row_StudentInfo['ID']], "int"),
                             GetSQLValueString($row_StudentInfo['ID'], "int"),
                             GetSQLValueString($_GET['activity_ID'], "int"));

        $result = mysql_query($insertSQL, $fiu_grading_system) or die(mysql_error());
      }
      else
      {
        $insertSQL = sprintf("INSERT INTO Grades (points, student_ID, activity_ID) VALUES (%d, %d, %d);",
                             GetSQLValueString($_POST[$row_StudentInfo['ID']], "int"),
                             GetSQLValueString($row_StudentInfo['ID'], "int"),
                             GetSQLValueString($_GET['activity_ID'], "int"));

        $result = mysql_query($insertSQL, $fiu_grading_system) or die(mysql_error());
      }

      mysql_free_result($check);
      mysql_free_result($result);


      $insertGoTo = sprintf("profCourseView.php?course_ID=%d", $row_CourseInfo['ID']);
      if (isset($_SERVER['QUERY_STRING'])) {
        $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
        $insertGoTo .= $_SERVER['QUERY_STRING'];
      }
      header(sprintf("Location: %s", $insertGoTo));
    }
  }
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">



<head>

<meta content="text/html; charset=UTF-8" http-equiv="Content-Type" />

<title>Professor Session: Edit Grades- FIU Online Grading</title>

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
<?php include("_prof_Includes/header.php"); ?>
<?php include("_prof_Includes/side_bar.php"); ?>

		<div class="span9">
		<div class="hero-unit"> 

			<h1>Edit Grades</h1>
			<br>
      <h3><?php echo $row_ActivityInfo["name"] ?></h3>
		  
			<h5>Max Possible Grade: <?php echo $row_ActivityInfo["max_points"] ?></h5>
	 
			<br>
			
			<form action="<?php echo $editFormAction; ?>" method="POST" name="edit_grades" class="form-horizontal">
						    				
			<table class="table table-hover">

				<tr>

					<th>Student name</th>
					
					<th>ID</th>

					<th>Grade</th>
					
					<th></th>

				</tr>

<?php 
  if(mysql_num_rows($_StudentInfo) > 0) 
  {
mysql_data_seek($_StudentInfo, 0);

$spry = 1;
   while($row_StudentInfo = mysql_fetch_assoc($_StudentInfo))
   {
   //Grades query
	$query_grades = sprintf("SELECT * FROM Grades WHERE student_ID = %d AND activity_ID=%d",
		GetSQLValueString($row_StudentInfo["ID"], "int"),
		GetSQLValueString($_GET["activity_ID"], "int"));
	$_gradeInfo = mysql_query($query_grades, $fiu_grading_system) or die(mysql_error());
	$row_gradeInfo = mysql_fetch_assoc($_gradeInfo);
?>

				<tr>

        <td><?php echo $row_StudentInfo["first_name"], " ",  $row_StudentInfo["last_name"]  ?></td>

					<td><?php echo $row_StudentInfo["ID"] ?></td>
					
			<td><span id="sprytextfield<?php echo $spry ?>">
              <input type="text" name="<?php echo $row_StudentInfo["ID"] ?>" id="<?php echo $row_StudentInfo["ID"] ?>" placeholder="Grade" value="<?php echo $row_gradeInfo['points'] ?>"/>
           <span class="textfieldRequiredMsg">A value is required.</span><span class="textfieldInvalidFormatMsg">Invalid format.</span><span class="textfieldMinValueMsg">The entered value is less than the minimum required.</span><span class="textfieldMaxValueMsg">The entered value is greater than the maximum allowed.</span></span></td>

				</tr>				
<?php 
	$spry++;
  }
}?>

			</table>
			
			<button type="submit" class="btn btn-primary btn-large">Submit</button>
			<a href="profCourseView.php?course_ID=<?php echo $_GET['course_ID']?>"><button name="Cancel" type="button" id="cancel" class="btn btn-danger btn-large"> Cancel </button></a>

			
        <input type="hidden" name="course_ID" value="<?php echo $row_CourseInfo['ID']; ?>" />
				<input type="hidden" name="MM_insert" value="edit_grades" />
			</form>

		</div>

	</div>
</div>
</div>
<?php $maxGrade = $row_ActivityInfo["max_points"];?>
<script type="text/javascript">
	<?php
		while ($spry > 0) {
	?>
	var sprytextfield<?php echo $spry ?> = new Spry.Widget.ValidationTextField("sprytextfield<?php echo $spry ?>", "integer", {minValue:0, maxValue:<?php echo $maxGrade;?>, validateOn:["blur"]});
	<?php
		$spry--;
		}
	?>
</script>
</body>

</html>
<?php
  mysql_free_result($_StudentInfo);
  mysql_free_result($_ActivityInfo);
  mysql_free_result($_UserData);
  mysql_free_result($_CourseInfo);
?>