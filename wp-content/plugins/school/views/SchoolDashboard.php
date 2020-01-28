<?php

function schoolDashboard()
{
    ?>
        <a href="http://localhost/wordpress/wordpress/index.php/school-profile/">Profile</a></br>
        <a href="http://localhost/wordpress/wordpress/index.php/notification-detail/">Applications</a></br>
        <a href="http://localhost/wordpress/wordpress/index.php/notification-detail/">Notifications</a></br>

        <h2><center>List Of Applications received by students</center></h2>
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