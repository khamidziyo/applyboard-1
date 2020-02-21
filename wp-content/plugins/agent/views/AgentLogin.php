<?php

function agentLogin()
{
    ?>
     <div class="container-fluid">
    <form name="agent_login_form" id="agent_login_form">
    <p>Email
    <input type="text" name="email" id="email" required email>
    </p>
    <p>Password
    <input type="password" name="password" id="password" required>
    </p>
    <input type="submit" name="sign_in" id="sign_in" class="btn btn-primary" value="Sign In" >
      <img src="<?=content_url('plugins/admin/assets/images/loading.gif')?>" id="load_img" width="200px" height="200px" style="display:none">
    </form><br>

    <a href="http://localhost/wordpress/wordpress/index.php/forgot-password/" class="btn btn-primary">Forgot Password</a>

    </div>
    <script src="<?=constant('agent_asset_url') . "/js/AgentLogin.js"?>"></script>

    <?php
}

add_shortcode('agent_login', 'agentLogin');
