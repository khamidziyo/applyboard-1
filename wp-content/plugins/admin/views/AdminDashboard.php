<?php

function adminDashboard()
{
    ?>
    <div class="container-fluid">
    <a href="<?=base_url?>admin-profile/"><h4>Profile</h4></a>
    <a href="<?=base_url?>add-agent/"><h4>Add Agent</h4></a>
    <a href="<?=base_url?>view-agents/"><h4>View Agents</h4></a>

    <a href="<?=base_url?>add-school/"><h4>Add School</h4></a>
    <a href="<?=base_url?>view-all-schools/"><h4>View Schools</h4></a>
    <a href="<?=base_url?>view-courses-by-admin/"><h4>View Courses</h4></a>
    <a><h4>View Students</h4></a>
    <a><h4>Payments</h4></a>
    </div>
<?php
}

add_shortcode('admin_dashboard', 'adminDashboard');
?>