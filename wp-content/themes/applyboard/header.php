
<!-- <!DOCTYPE html> -->

<style>
    a {
        text-decoration: none;
        color: black;
    }

    a:visited {
        color: black;
    }

    .box::-webkit-scrollbar-track {
        -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.3);
        background-color: #F5F5F5;
        border-radius: 5px
    }

    .box::-webkit-scrollbar {
        width: 10px;
        background-color: #F5F5F5;
        border-radius: 5px
    }

    .box::-webkit-scrollbar-thumb {
        background-color: black;
        border: 2px solid black;
        border-radius: 5px
    }

    header {
        -moz-box-shadow: 10px 10px 23px 0px rgba(0, 0, 0, 0.1);
        box-shadow: 10px 10px 23px 0px rgba(0, 0, 0, 0.1);
        height: 110px;
        vertical-align: middle;
    }

    h1 {
        float: left;
        padding: 10px 30px
    }

    body {
        margin: 0;
        padding: 0;
        font-family: 'Raleway', sans-serif;
    }

    .icons {
        display: inline;
        float: right
    }

    .notification {
        padding-top: 30px;
        position: relative;
        display: inline-block;
    }

    .number {
        height: 22px;
        width: 22px;
        background-color: #d63031;
        border-radius: 20px;
        color: white;
        text-align: center;
        position: absolute;
        top: 23px;
        left: 60px;
        padding: 3px;
        border-style: solid;
        border-width: 2px;
    }

    .number:empty {
        display: none;
    }

    .notBtn {
        transition: 0.5s;
        cursor: pointer
    }

    .fas {
        font-size: 25pt;
        padding-bottom: 10px;
        color: black;
        margin-right: 40px;
        margin-left: 40px;
    }

    .box {
        width: 400px;
        height: 0px;
        border-radius: 10px;
        transition: 0.5s;
        position: absolute;
        overflow-y: scroll;
        padding: 0px;
        left: -300px;
        margin-top: 5px;
        background-color: #F4F4F4;
        -webkit-box-shadow: 10px 10px 23px 0px rgba(0, 0, 0, 0.2);
        -moz-box-shadow: 10px 10px 23px 0px rgba(0, 0, 0, 0.1);
        box-shadow: 10px 10px 23px 0px rgba(0, 0, 0, 0.1);
        cursor: context-menu;
    }

    /* .fas:hover {
        color: #d63031;
    } */
/* 
    .notBtn:hover>.box {
        height: 60vh
    } */

    .content {
        padding: 20px;
        color: black;
        vertical-align: middle;
        text-align: left;
    }

    .gry {
        background-color: #F4F4F4;
    }

    .top {
        color: black;
        padding: 10px
    }

    .display {
        position: relative;
    }

    .cont {
        position: absolute;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: #F4F4F4;
    }

    .cont:empty {
        display: none;
    }

    .stick {
        text-align: center;
        display: block;
        font-size: 50pt;
        padding-top: 70px;
        padding-left: 80px
    }

    /* .stick:hover {
        color: black;
    } */

    .cent {
        text-align: center;
        display: block;
    }

    .sec {
        padding: 25px 10px;
        background-color: #F4F4F4;
        transition: 0.5s;
    }

    .profCont {
        padding-left: 15px;
    }

    .profile {
        -webkit-clip-path: circle(50% at 50% 50%);
        clip-path: circle(50% at 50% 50%);
        width: 75px;
        float: left;
    }

    .txt {
        vertical-align: top;
        font-size: 1.25rem;
        padding: 5px 10px 0px 115px;
    }

    .sub {
        font-size: 1rem;
        color: grey;
    }

    .new {
        border-style: none none solid none;
        border-color: red;
    }

    /* .sec:hover {
        background-color: #BFBFBF;
    } */
</style>

<html lang="en">

<head>


<div class="ph-item loader">
    <div class="ph-col-12">
    <div class="ph-row">
            <div class="ph-col-6 big"></div>
            <div class="ph-col-4 empty big"></div>
            <div class="ph-col-2 big"></div>
            <div class="ph-col-4"></div>
            <div class="ph-col-8 empty"></div>
            <div class="ph-col-6"></div>
            <div class="ph-col-6 empty"></div>
            <div class="ph-col-12"></div>
        </div>
    </div>
</div>


	<title></title>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">

	  	<link rel="stylesheet" href="<?=get_template_directory_uri();?>/style.css">
		<link rel="stylesheet" href="<?=content_url('themes/applyboard/css/jquery.dataTables.min.css')?>">
		<link rel="stylesheet" href="<?=content_url('themes/applyboard/css/jquery-ui.css')?>">
		<link rel="stylesheet" href="<?=content_url('themes/applyboard/css/jquery.multiselect.css')?>">
		<link rel="stylesheet" href="<?=content_url('themes/applyboard/css/multi-select.css')?>">
		<link rel="stylesheet" href="<?=content_url('themes/applyboard/css/jquery.fancybox.css')?>">
		<link rel="stylesheet" href="<?=content_url('themes/applyboard/css/bootstrap-select.min.css')?>">
		<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

		<link rel="stylesheet" href="<?=content_url('themes/applyboard/css/placeholder-loading.min.css')?>">

		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">


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

	<div class="icons">
        <div class="notification">
            <a href="#">
                <div class="notBtn" href="#">
                    <!--Number supports double digets and automaticly hides itself when there is nothing between divs -->
					<p id="notif_count"></p>
				<i class="material-icons" id="notification">add_alert</i>
                    <div class="box">
                        <div class="display">
                            <div class="nothing">
                                <i class="fas fa-child stick"></i>
                                <div class="cent">Looks Like your all caught up!</div>
                            </div>
                            <div class="cont">

                                <span id="view_notification">


								</span>
                                <!-- Fold this div and try deleting evrything inbetween -->
								
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>



	<span id="user_email"></span>
    <span id="user_image"></span>

    <input type="button" class="btn btn-danger" id="logout" value="Logout"  name="logout">
    </span><br><br>
	</div>
</div>

	<div class="container">
<div class="row">
<div class="col-sm-3">
<div class="sidenav">
<span id="sidebar">
</span>



<!-- <div class="col-sm-9">
<div class="main-content"> -->


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

    <!-- </div>
	</div> -->
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
			html+="<div class='sidenav'><span class='glyphicon glyphicon-home'></span>&nbsp;<b><a href='<?=base_url?>admin-dashboard/'>Home</a></b><br>";
			html+="<span class='glyphicon glyphicon-user'></span>&nbsp;<b><a href='<?=base_url?>admin-profile/'>Profile</a></b><br><span class='glyphicon glyphicon-plus'></span>&nbsp;<b><a href='<?=base_url?>add-agent/'>Add Agent</a></b><br>";
			html+="<span class='glyphicon glyphicon-eye-open'></span>&nbsp;<b><a href='<?=base_url?>view-agents/'>View Agents</a></b><br><span class='glyphicon glyphicon-plus'></span>&nbsp;<b><a href='<?=base_url?>add-school/'>Add School</a></b><br>"
			html+="<span class='glyphicon glyphicon-education'></span>&nbsp;<b><a href='<?=base_url?>view-all-schools/'>View Schools</a></b><br><span class='glyphicon glyphicon-plus'></span>&nbsp;<b><a href='<?=base_url?>add-staff/'>Add Staff Member</a></b><br>"
			html+="<span class='glyphicon glyphicon-eye-open'></span>&nbsp;<b><a href='<?=base_url?>view-staff-members/'>View Staff Members</a></b><br>";
			html+="<span class='glyphicon glyphicon-eye-open'></span>&nbsp;<b><a href='<?=base_url?>view-courses-by-admin/'>View Courses</a></b></div>";

		break;

		// if the logged in user is agent...
		case '3':
			html+="<a href='<?=base_url?>agent-dashboard/'>Home</a><br><a href='<?=base_url?>agent-profile/'>Profile</a><br>";
			html+="<a href='<?=base_url?>add-student/'>Add Student</a><br>"
			html+="<a href='<?=base_url?>view-students/'>View Students</a><br><a href='<?=base_url?>view-sub-agents/'>View Sub Agents</a><br>";
			html+="<a href='<?=base_url?>messages/'>Messages</a><br>";
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


