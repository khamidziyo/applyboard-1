<span id="sidebar">
</span>

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
</script>