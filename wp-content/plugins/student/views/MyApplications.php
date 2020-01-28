<?php
function studentApplications(){
    ?>
    <table id="student_application" border="2px">
    <thead>
    <th>Id</th>
    <th>School</th>
    <th>Course</th>
    <th>Status</th>
    <th>Submitted On</th>
    <th>Action</th>
    </thead>
    </table>
    <script src = '<?=student_asset_url?>/js/StudentApplication.js'></script>
    <?php
}

add_shortcode('student_applications','studentApplications');
?>