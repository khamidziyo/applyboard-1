<?php

function viewSchools(){
    ?>
    <div class="container-fluid">

<table id="school_table" border="2px">
<thead>
<th>Id</th>
<th>Name</th>
<th>Email</th>
<th>Address</th>
<th>Number</th>
<th>Country</th>
<th>Postal Code</th>
<th>Profile Image</th>
<th>Action</th>
</thead>
<tbody>
</tbody>
</table>
</div>

<script src="<?=admin_asset_url?>js/ViewSchool.js"></script>
    <?php
}

add_shortcode('view_all_schools','viewSchools');
?>