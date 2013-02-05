<?php include("_admin_Includes/logoutAndValidation.php") ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Admin Session - FIU Online Grading</title>

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
    </style>


</head>

<body>
<?php include("_admin_Includes/Header.php"); ?>
<?php include("_admin_Includes/sideBar.php"); ?>
        
         <div class="span9">
          <div class="hero-unit">
            <h2>Welcome, <?php echo $_SESSION['MM_Username'] ?>!</h2>
            <p>Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui</p>
            
          </div>
    

</body>
</html>
