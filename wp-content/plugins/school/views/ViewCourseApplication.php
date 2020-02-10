<?php
function viewApplicationSchool()
{
    ?>
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

        <script src = '<?=school_asset_url?>js/SchoolApplication.js'></script>
    <?php
}

add_shortcode('view_applications_school', 'viewApplicationSchool')
?>
