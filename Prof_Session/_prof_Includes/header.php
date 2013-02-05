<div class="navbar navbar-inverse navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container-fluid">
          <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>
          <a class="brand" href="profSession.php">FIU Online Grading</a>
          <div class="nav-collapse collapse">
            <p class="navbar-text pull-right">
   Logged in as <?php echo $row_UserData['first_name'], " ", $row_UserData['last_name']; ?> <a href="<?php echo $logoutAction ?>">Log out</a>
            </p>
            <ul class="nav">
              <li class="active"><a href="profSession.php">Home</a></li>
              <li><a href="#about">About</a></li>
              <li><a href="#contact">Contact</a></li>
            </ul>
          </div><!--/.nav-collapse -->
        </div>
      </div>
</div>