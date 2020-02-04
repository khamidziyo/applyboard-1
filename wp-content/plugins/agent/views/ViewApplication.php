<?php
function viewApplication()
{
    ?>
    <table id="view_application_table" border="2px solid black">
    <thead>
    <th>Id</th>
    <th>Student Name</th>
    <th>Created By</th>
    <th>School Name</th>
    <th>Course Name</th>
    <th>Submitted On</th>
    <th>Status</th>
    <th>Action</th>
    </thead>
    </table>

    <script src="<?=agent_asset_url?>js/ViewApplication.js"></script>

   <?php
}

add_shortcode('view_application', 'viewApplication')
?>
