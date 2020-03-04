

<!-- <!DOCTYPE html> -->

<html lang="en">

<head>
	<title></title>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">

	  	<link rel="stylesheet" href="<?=get_template_directory_uri();?>/style.css">
		<link rel="stylesheet" href="<?=content_url('themes/applyboard/css/bootstrap.min.css')?>">
		<link rel="stylesheet" href="<?=content_url('themes/applyboard/css/jquery.dataTables.min.css')?>">
		<link rel="stylesheet" href="<?=content_url('themes/applyboard/css/jquery-ui.css')?>">
		<link rel="stylesheet" href="<?=content_url('themes/applyboard/css/jquery.multiselect.css')?>">
		<link rel="stylesheet" href="<?=content_url('themes/applyboard/css/multi-select.css')?>">
		<link rel="stylesheet" href="<?=content_url('themes/applyboard/css/jquery.fancybox.css')?>">
		<link rel="stylesheet" href="<?=content_url('themes/applyboard/css/bootstrap-select.min.css')?>">

		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

		<link rel="stylesheet" href="<?=content_url('themes/applyboard/css/placeholder-loading.min.css')?>">

		<script src="<?=content_url("themes/applyboard/js/jquery.js")?>"></script>
		<script src="<?=content_url("themes/applyboard/js/bootstrap.min.js")?>"></script>
		<script src="<?=content_url("themes/applyboard/js/sweetalert.js")?>"></script>

		<script src="<?=content_url("themes/applyboard/js/datatable.min.js")?>"></script>
		<script src="<?=content_url("themes/applyboard/js/server_url.js")?>"></script>
		<script type="text/javascript" src="<?=content_url("themes/applyboard/js/jquery.multiselect.js")?>"></script>
		<script type="text/javascript" src="<?=content_url("themes/applyboard/js/jquery.multi-select.js")?>"></script>


    	<script type="text/javascript" src="<?=content_url("plugins/common/token.js")?>"></script>
		<script type="text/javascript" src="<?=content_url("plugins/common/sweetalert.js")?>"></script>
		<script type="text/javascript" src="<?=content_url("plugins/common/constant.js")?>"></script>

		<script src="<?=content_url("themes/applyboard/js/jquery.sticky.js")?>"></script>
		<script src="<?=content_url("themes/applyboard/js/slick.js")?>"></script>
		<script src="<?=content_url("themes/applyboard/js/jquery.fancybox.js")?>"></script>
		<script src="<?=content_url("themes/applyboard/js/bootstrap-select.min.js")?>"></script>


		<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
		<script src="https://js.pusher.com/5.0/pusher.min.js"></script>
		<script type="text/javascript" src="https://cdn.rawgit.com/oauth-io/oauth-js/c5af4519/dist/oauth.js"></script>


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

	<div class="container">

<span id="sidebar"></span>

<div class = 'modal fade' id = 'sub_login_modal'>
    <div class = 'modal-dialog'>
    <div class = 'modal-content'>
    <div class = 'modal-header'>
    <button type = 'button' class = 'close' data-dismiss = 'modal'>&times;
    </button>
    <h2>Create Sub Agent Profile</h2>
    </div>

    <div class = 'modal-body'>
    <!-- Circles which indicates the steps of the form: -->
    <div class="container" style="width:500px">

    <form name="sub_agent_form" id="sub_agent_form">

    <div class="form-group">
    <label for="email">Permission:</label>
    <input type="checkbox" name="permission" value="1">
    </div>


    <div class="form-group">
      <label for="email">Email:</label>
      <input type="email" class="form-control" id="email" placeholder="Enter email" name="email" required>
    </div>


    <div class="form-group">
      <label for="password">Password:</label>
      <input type="password" class="form-control" id="password" placeholder="Enter password" name="password" required>
    </div>


    <div class="form-group">
      <label for="con_password">Confirm Password:</label>
      <input type="password" class="form-control" id="con_password" placeholder="Enter email" name="con_password" required>
    </div>

    <input type="hidden" name="val" value="addSubAgent">
    <input type="submit" class="btn btn-success" value="Create profile" id="sub_agent">
    </form>

    </div>
    <div class = 'modal-footer'>

    <button type = 'button' class = 'btn btn-default' data-dismiss = 'modal'>Close</button>
    </div>
    </div>
    </div>
    </div>
    </div>
	</div>
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

		<div class="wrapper">


<script>
if(localStorage.getItem('data')!=null){
	var html="";

	var local_data=JSON.parse(localStorage.getItem('data'));
	switch(local_data.role){

		// if the logged in user is student...
		case '1':
			html+="<a href='<?=base_url?>student-dashboard/'>Home</a><br>";
			html+="<a href='<?=base_url?>student-profile/'>Profile</a><br><a href='<?=base_url?>messages/'>My Messages</a><br>";
			html+="<a href='<?=base_url?>student-applications/'>View Applications</a><br>";
			html+="<a href='<?=base_url?>eligible-programs/'>View Eligible Programs</a><br><br>";
			break;

		// if the logged in user is admin...
		case '2':
			html+="<a href='<?=base_url?>admin-dashboard/'>Home</a>";
			html+="<a href='<?=base_url?>admin-profile/'><h4>Profile</h4></a><a href='<?=base_url?>add-agent/'><h4>Add Agent</h4></a>";
			html+="<a href='<?=base_url?>view-agents/'><h4>View Agents</h4></a><a href='<?=base_url?>add-school/'><h4>Add School</h4></a>"
			html+="<a href='<?=base_url?>view-all-schools/'><h4>View Schools</h4></a><a href='<?=base_url?>add-staff/'><h4>Add Staff Member</h4></a>"
			html+="<a href='<?=base_url?>view-staff-members/'><h4>View Staff Members</h4></a>";
			html+="<a href='<?=base_url?>view-courses-by-admin/'><h4>View Courses</h4></a>";

		break;

		// if the logged in user is agent...
		case '3':
			html+="<a href='<?=base_url?>agent-dashboard/'>Home</a><br><a href='<?=base_url?>agent-profile/'>Profile</a><br>";
			html+="<a href='<?=base_url?>add-student/'>Add Student</a><br>"
			html+="<a href='<?=base_url?>view-students/'>View Students</a><br><a href='<?=base_url?>view-sub-agents/'>View Sub Agents</a><br>";
			html+="<a class='btn btn-primary' id='create_sublogin'>Create Sub agents</a><br><br>";
		break;

		// if logged in user is sub agent...
		case '4':
			html+="<a href='<?=base_url?>sub-agent-dashboard/'>Home</a><br>";
			html+="<a href='<?=base_url?>sub-agent-profile/'>Profile</a><br><a href='<?=base_url?>add-student/'>Add Student</a><br>";
    		html+="<a href='<?=base_url?>view-students/'>View Students</a><br>";
		break;

		// if logged in user is staff...
		case '5':
			html+="<a href='<?=base_url?>staff-dashboard/'>Home</a><br><a href='<?=base_url?>staff-profile/'>Profile</a><br>";
			html+="<a href='<?=base_url?>view-applications-by-staff'>Applications</a><br><a href='<?=base_url?>review-applications'>Applications Under Review</a><br>"
			break;

	}
	$("#sidebar").html(html);
	includeJs();
}

function includeJs(){
	var myscript = document.createElement('script');
	myscript.setAttribute('src',"<?=content_url("themes/applyboard/js/header.js")?>")
	document.head.appendChild(myscript);
}

</script>