

<!DOCTYPE html>
<html lang="en">

<head>


	<title</title>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">

	  <link rel="stylesheet" href="<?=get_template_directory_uri();?>/style.css">
		<link rel="stylesheet" href="<?=content_url('themes/applyboard/css/bootstrap.min.css')?>">
		<link rel="stylesheet" href="<?=content_url('themes/applyboard/css/font-awesome.min.css')?>">
		<link rel="stylesheet" href="<?=content_url('themes/applyboard/css/jquery.dataTables.min.css')?>">
		<link rel="stylesheet" href="<?=content_url('themes/applyboard/css/jquery-ui.css')?>">


		<script src="<?=content_url("themes/applyboard/js/jquery.js")?>"></script>
		<script src="<?=content_url("themes/applyboard/js/bootstrap.min.js")?>"></script>
		<script src="<?=content_url("themes/applyboard/js/sweetalert.js")?>"></script>

		<script src="<?=content_url("themes/applyboard/js/datatable.min.js")?>"></script>
		<script src="<?=content_url("themes/applyboard/js/server_url.js")?>"></script>

    	<script type="text/javascript" src="<?=content_url("plugins/common/token.js")?>"></script>
		<script src="<?=content_url("themes/applyboard/js/jquery.sticky.js")?>"></script>
		<script src="<?=content_url("themes/applyboard/js/slick.js")?>"></script>
		<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>


	 <?php wp_head();?>
</head>


<body>

	<div container="headerAfterLogin" id="after_login" style="display:none"><br><br>

	<div   id="user_notification" border="5px solid black" width="100px"></div>

	<span style="float:right">
	
	<p id="notif_count"></p>
	
	<img src="<?=content_url("themes/applyboard/images/notification.png")?>" id="notification" height="20px" width="20px">

	<span id="user_email"></span>
    <span id="user_image"></span>
    <input type="button" class="btn btn-danger" id="logout" value="Logout"  name="logout">
    </span>
	</div>
	<!-- HEADER SECTION-->


	<!-- <header id="header-sticky" class="site-header">
		<div class="header_meta2" id="">
			<nav class="navbar navbar-default">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar2">
<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
				<?php if (function_exists('the_custom_logo')) {
    the_custom_logo();
}?>
				</div>
				<div id="navbar2" class="navbar-collapse collapse">

						 <?php
//  wp_nav_menu(array('menu'=>'Menu','menu_class'=>'nav navbar-nav navbar-right'));
?>

				</div>
			</nav>
		</div>
	 <?php if (is_front_page()): ?>
	<style>.blog-list .headingi  {
    display: none;
}.main-div .ip {style="display:none"
    display: none;
}
		</style>

		  <?php endif;?>
	</header> -->

	<body>
		<div class="wrapper">


<script>
if(localStorage.getItem('data')!=null){
	includeJs();
}

function includeJs(){
	var myscript = document.createElement('script');
	myscript.setAttribute('src',"<?=content_url("themes/applyboard/js/header.js")?>")
	document.head.appendChild(myscript);

}
</script>