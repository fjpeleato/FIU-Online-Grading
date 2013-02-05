<?php require_once('Connections/fiu_grading_system.php'); ?>
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

$colname_Recordset1 = "-1";
if (isset($_POST['userName'])) {
  $colname_Recordset1 = $_POST['userName'];
}
$password_Recordset1 = "-1";
if (isset($_POST['password'])) {
  $password_Recordset1 = $_POST['password'];
}
mysql_select_db($database_fiu_grading_system, $fiu_grading_system);
$query_Recordset1 = sprintf("SELECT type FROM Users WHERE user_name = %s  AND Users.password_hash = %s", GetSQLValueString($colname_Recordset1, "text"),GetSQLValueString($password_Recordset1, "text"));
$Recordset1 = mysql_query($query_Recordset1, $fiu_grading_system) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);
?>
<?php
// *** Validate request to login to this site.
if (!isset($_SESSION)) {
  session_start();
}

$loginFormAction = $_SERVER['PHP_SELF'];
if (isset($_GET['accesscheck'])) {
  $_SESSION['PrevUrl'] = $_GET['accesscheck'];
}

if (isset($_POST['userName'])) {
  $loginUsername=$_POST['userName'];
  $password=$_POST['password'];
  $MM_fldUserAuthorization = "type";
  $MM_redirectLoginSuccess = "Student_Session/studentSession.php";
  $MM_redirectLoginFailed = "login.php";
  $MM_redirecttoReferrer = true;
  mysql_select_db($database_fiu_grading_system, $fiu_grading_system);
  	
  $LoginRS__query=sprintf("SELECT user_name, password_hash, type FROM Users WHERE user_name=%s AND password_hash=%s",
                          GetSQLValueString($loginUsername, "text"), GetSQLValueString($password, "text")); 
   
  $LoginRS = mysql_query($LoginRS__query, $fiu_grading_system) or die(mysql_error());
  $loginFoundUser = mysql_num_rows($LoginRS);
  if ($loginFoundUser) {
    
    $loginStrGroup  = mysql_result($LoginRS,0,'type');
    
    if (PHP_VERSION >= 5.1) {session_regenerate_id(true);} else {session_regenerate_id();}
    //declare two session variables and assign them
    $_SESSION['MM_Username'] = $loginUsername;
    $_SESSION['MM_UserGroup'] = $loginStrGroup;	      

    if (isset($_SESSION['PrevUrl']) && true) {
      $MM_redirectLoginSuccess = $_SESSION['PrevUrl'];	
    }
    if ($row_Recordset1['type'] === '0') {
      header("Location: Admin_Session/adminSession.php");
    } else if ($row_Recordset1['type'] === '1') {
      header("Location: Prof_Session/profSession.php");
    } else {
      header("Location: " . $MM_redirectLoginSuccess );
    }
  }
  else {
    header("Location: ". $MM_redirectLoginFailed );
  }
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
  <meta charset="utf-8">
  <title>FIU Online Grading</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="">
  <meta name="author" content="">

  <!-- Le styles -->
  <link href="assets/css/bootstrap.css" rel="stylesheet" type="text/css">
  <style type="text/css">
  body {
  padding-top: 60px;
  padding-bottom: 40px;
}
</style>
<link href="assets/css/bootstrap-responsive.min.css" rel="stylesheet" type="text/css">
  <script src="SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
  <script src="SpryAssets/SpryValidationPassword.js" type="text/javascript"></script>
  <link href="SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css">
  <link href="SpryAssets/SpryValidationPassword.css" rel="stylesheet" type="text/css">
  <body>

  <div class="navbar navbar-inverse navbar-fixed-top">
  <div class="navbar-inner">
  <div class="container">
  <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
  <span class="icon-bar"></span>
  <span class="icon-bar"></span>
  <span class="icon-bar"></span>
  </a>
  <a class="brand" href="login.php">FIU ONLINE GRADING</a>
  <div class="nav-collapse collapse">
  <ul class="nav">
  </ul>
            
  <form ACTION="<?php echo $loginFormAction; ?>" method="POST" name="loginForm" class="navbar-form pull-right" >
  <span id="sprytextfield1">
  <input name="userName" type="text" class="span2" id="userName" maxlength="20" placeholder="User Name" />
  <span class="textfieldRequiredMsg">Username is required.</span></span><span id="sprypassword1">
  <input name="password" type="password" class="span2" id="password"/ placeholder="Password">
  <span class="passwordRequiredMsg">A Password is required.</span></span>
  <input type="submit" name="submit" id="submit" value="Submit" class="btn btn-primary"/> 
  </form>
  </div><!--/.nav-collapse -->
  </div>
  </div>

      <!-- Carousel
    ================================================== -->
    <div id="myCarousel" class="carousel slide">
      <div class="carousel-inner">
        <div class="item active">
          <img src="assets/img/banner1.jpg" width="100%" alt="">
          <div class="container">
            <div class="carousel-caption">
              <h1>WELCOME TO OUR FIU ONLINE GRADING SYSTEM</h1>
            </div>
          </div>
        </div>
        <div class="item">
          <img src="assets/img/banner2.jpg" width="100%" alt="">
          <div class="container">
            <div class="carousel-caption">
              <h1>Software Eng. Project.</h1>
              <p class="lead">This system will allow professors to maintain an online grade books for the courses they are
teaching and will allow students to review the grades they have received in the courses they
are taking. Professors can only enter grades for the courses they are teaching and students
can only view their own grades.</p>
            </div>
          </div>
        </div>
        </div>
      <a class="left carousel-control" href="#myCarousel" data-slide="prev">&lsaquo;</a>
      <a class="right carousel-control" href="#myCarousel" data-slide="next">&rsaquo;</a>
    </div><!-- /.carousel -->


  <footer>
  <p>&copy; Software Engineer Project - Carlos Corvaia, Francisco Peleato & Ronald Fenelus.</p>
  </footer>

  </div> <!-- /container -->

  <!-- Le javascript
  ================================================== -->
              <!-- Placed at the end of the document so the pages load faster -->
              <script src="http://code.jquery.com/jquery-latest.js"></script>
              <script src="assets/js/bootstrap.min.js"></script>
              <script type="text/javascript">
              var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1", "none", {validateOn:["blur"]});
var sprypassword1 = new Spry.Widget.ValidationPassword("sprypassword1", {validateOn:["blur"], minAlphaChars:0, minNumbers:0, minUpperAlphaChars:0});
</script>
</body>
</html>
<?php
mysql_free_result($Recordset1);
?>
<script src="assets/js/jquery-1.8.2.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
<script>
      !function ($) {
        $(function(){
          // carousel demo
          $('#myCarousel').carousel()
        })
      }(window.jQuery)
    </script>