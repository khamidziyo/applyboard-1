<?php

function schoolDashboard()
{
    ?>
        <a href="<?=base_url?>school-profile/">Profile</a></br>
        <a href="<?=base_url?>add-course/">Add Course</a></br>
        <a href="<?=base_url?>view-all-course/">View All Course</a></br>
        
        <a href="<?=base_url?>messages">My Messages</a></br>
        
        <a href="<?=base_url?>notification-detail/">Applications</a></br>
        <a href="<?=base_url?>notification-detail/">Notifications</a></br>

        <h2><center>List Of Applications submitted by students</center></h2>
        <table id="applications_table" border="2px">
        <thead>
        <th>Id</th>
        <th>User Name</th>
        <th>Course Name</th>
        <th>Status</th>
        <th>Submitted On</th>
        <th>Action</th>
        </thead>
        </table>
        <script src = '<?=school_asset_url?>js/SchoolDashboard.js'></script>

    <?php
}

add_shortcode('school_dashboard', 'schoolDashboard');
?>