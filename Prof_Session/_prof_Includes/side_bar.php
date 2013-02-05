<div class="container-fluid">
   <div class="row-fluid">
   <div class="span3">
   <div class="well sidebar-nav">
   <ul class="nav nav-list">
   <li class="nav-header">My Courses</li>
    <li>
<?php
   
  //reset _CourseInfo pointer

       if(mysql_num_rows($_CourseInfo) > 0)
       {
  mysql_data_seek($_CourseInfo, 0);
while ($row_CourseInfo = mysql_fetch_assoc($_CourseInfo)) { ?>
                   <table><tr><a href="profCourseView.php?course_ID=<?php echo $row_CourseInfo['ID'];?>"><?php echo $row_CourseInfo['name'], " (", $row_CourseInfo['section'], ")"; ?></a></tr></table>
<?php }} ?>
   <li class="nav-header">Management</li>
   <li><a href="request_course.php">Request a Course</a></li>
   <li><a href="edit_profile.php">Edit Profie</a></li>
   </ul>
   </div><!--/.well -->
   </div><!--/span-->