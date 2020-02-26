<?php
function staffMembers()
{
    ?>
    <table id="staff_table" style="border:2px solid black">
    <thead>
    <th>Id</th>
    <th>Name</th>
    <th>Email</th>
    <th>Image</th>
    <th>Created On</th>
    <th>Status</th>
    <th>Action</th>
    </thead>
    </table>

    <script src="<?=staff_asset_url?>js/StaffMember.js"></script>
    <?php
}

add_shortcode('view_staff_members', 'staffMembers');
?>