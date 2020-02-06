<?php
function viewSubAgents()
{
    ?>
    <div class="container">
    <table id="view_sub_agent" border="2px solid black">
    <thead>
    <th>Id</th>
    <th>Email</th>
    <th>Status</th>
    <th>Created On</th>
    <th>Action</th>
    </thead>
    </table>
    </div>
<script src="<?=agent_asset_url?>js/SubAgent.js"></script>
<?php
}

add_shortcode('view_sub_agents', 'viewSubAgents');
