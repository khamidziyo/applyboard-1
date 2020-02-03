<?php

function viewAgents()
{
    ?>
    <div class="container">
    <table id="view_agent_table" border="2px solid black">
    <thead>
    <th>Id</th>
    <th>Created By</th>
    <th>Name</th>
    <th>Email</th>
    <th>Contact Number</th>
    <th>Address</th>
    <th>Image</th>
    <th>Action</th>
    </thead>
    </table>
    </div>
     <script src="<?=agent_asset_url?>js/ViewAgent.js"></script>
<?php
}

add_shortcode('view_agents', 'viewAgents');
?>