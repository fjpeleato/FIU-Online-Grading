<div class="container-fluid">
      <div class="row-fluid">
        <div class="span3">
          <div class="well sidebar-nav">
            <ul class="nav nav-list">
              <li class="nav-header">My Courses</li>
              <li>
			  <?php
   if(mysql_num_rows($Recordset1) > 0)
   {
     mysql_data_seek($Recordset1, 0);
   while ($row_Recordset1 = mysql_fetch_assoc($Recordset1))  { ?>
              <table><tr><a href="studentCourseView.php?course_ID=<?php echo $row_Recordset1['ID'];?>"><?php echo $row_Recordset1['name']; ?></a></tr></table>
<?php }  }?>
              </li>
<li class="nav-header">Manage Account</li>
              <li><a href="studentChangePw.php">Change Password</a></li>
            </ul>
          </div><!--/.well -->
        </div><!--/span-->
