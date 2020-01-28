<?php

function allNotifications()
{
    ?>
<span id="notification_detail">
</span>

<script src = '<?=school_asset_url?>js/SchoolNotification.js'></script>
<?php
}
add_shortcode('notification_detail', 'allNotifications')
?>