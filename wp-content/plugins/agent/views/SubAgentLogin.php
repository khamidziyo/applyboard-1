<?php

function subAgentLogin()
{
    ?>
       <div class="container-fluid">
    <form name="sub_agent_login" id="sub_agent_login">
    <p>Email
    <input type="email" name="email" id="email" required email>
    </p>
    <p>Password
    <input type="password" name="password" id="password" required>
    </p>
    <input type="submit" name="sign_in" id="sign_in_btn" class="btn btn-primary" value="Sign In" >
    </form>
    <a href="<?=base_url?>forgot-password?type=subagent" class="btn btn-primary">Forgot Password</a>

    </div>
    <script src="<?=constant('agent_asset_url') . "/js/SubAgentLogin.js"?>"></script>
    <?php
}

add_shortcode('sub_agent_login', 'subAgentLogin')
?>