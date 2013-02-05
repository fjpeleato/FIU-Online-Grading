<?php require_once('../Connections/fiu_grading_system.php'); ?>

<head>
<meta http-equiv="refresh" content="1;url=profCourseView.php?course_ID=<?php echo $_GET["course_ID"]; ?>">
</head>

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

	$removeSQL = sprintf("DELETE FROM Activities WHERE ID=%s",
		GetSQLValueString($_GET["activity_ID"], "int"));

	mysql_select_db($database_fiu_grading_system, $fiu_grading_system);
	$Result1 = mysql_query($removeSQL, $fiu_grading_system) or die(mysql_error());
	
	$gradesSQL = sprintf("DELETE FROM Grades WHERE activity_ID=%s",
		GetSQLValueString($_GET["activity_ID"], "int"));

	mysql_select_db($database_fiu_grading_system, $fiu_grading_system);
	$Result2 = mysql_query($gradesSQL, $fiu_grading_system) or die(mysql_error());
?>