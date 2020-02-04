<?php

function viewStudents()
{
    ?>


   <table id="view_student_table" border="2px solid black">
   <thead>
   <th>Id</th>
   <th>First Name</th>
   <th>Last Name</th>
   <th>Email</th>
   <th>Date Of Birth</th>
   <th>Nationality</th>
   <th>Gender</th>
   <th>Total Applications</th>
   <th>Image</th>
   <th>Action</th>

   </thead>
   </table>

   <script src="<?=agent_asset_url?>js/ViewStudent.js"></script>
   <?php
}

add_shortcode('view_students', 'viewStudents')
?>
