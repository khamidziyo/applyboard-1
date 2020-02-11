var availablePrograms = [];
var availableSchools = [];

getStudentDashboard();

function getStudentDashboard() {
    // ajax call to get the relevant courses...
    $.ajax({
        url: student_server_url + "StudentDashboard.php",
        data: { val: "studentDashboard" },
        dataType: "json",

        // apending token in request...
        beforeSend: function (request) {

            // calling function that appends the token defined in token.js file 
            // inside common directory of plugins.
            if (!appendToken(request)) {

                // if the token is not in the localStorage...
                studentRedirectLogin();
            }
        },

        // if response is success...
        success: function (response) {

            // calling function that verifies the token defined in token.js file 
            // inside common directory of plugins.
            if (verifyToken(response)) {

                // if response status is 200...
                if (response.status == 200) {
                    $("#applications").html(response.total_application);
                    $("#application_approve").html(response.application_approve);
                    $("#application_decline").html(response.application_decline);
                    $("#application_pending").html(response.application_pending);
                    // console.log(response)
                } else {
                    sweetalert(response);
                }
            }

            // redirecting to login page if token not verified...
            else {
                studentRedirectLogin();
            }
        },

        // if response is error...
        error: function (error) {
            console.error(error);
        }
    })
}



// when user searches  program then listing the available programs...
$("#program").autocomplete({
    source: availablePrograms,
});

// when user searches school then listing the available school...
$("#sch_name").autocomplete({
    source: availableSchools,
});


// when user enter the value in the input...
$("#program").keyup(function () {

    // if value is null...
    if ($(this).val() == "") {
        return false;
    }

    // ajax call to get the relevant courses...
    $.ajax({
        url: student_server_url + "GetCourses.php",
        type: "get",
        data: { term: $(this).val() },
        dataType: "json",

        // apending token in request...
        beforeSend: function (request) {

            // calling function that appends the token defined in token.js file 
            // inside common directory of plugins.
            if (!appendToken(request)) {

                // if the token is not in the localStorage...
                redirectLogin();
            }
        },

        // if response is success...
        success: function (response) {

            // calling function that verifies the token defined in token.js file 
            // inside common directory of plugins.
            if (verifyToken(response)) {

                // if response status is 200...
                if (response.status == 200) {
                    if (response.data != null) {
                        availablePrograms.length = 0;

                        // pushing the programs in the array by loop...
                        $.each(response.data, function (k, obj) {
                            availablePrograms.unshift(obj.name);
                        })
                    }
                }
            }

            // redirecting to login page if token not verified...
            else {
                redirectLogin();
            }
        },

        // if response is error...
        error: function (error) {
            console.error(error);
        }
    })
})

// when user enter the school name in the input...
$("#sch_name").keyup(function () {

    // if value is null...
    if ($(this).val() == "") {
        return false;
    }

    // ajax call to get the relevant schools...
    $.ajax({
        url: student_server_url + "GetSchools.php",
        type: "get",
        data: { term: $(this).val() },
        dataType: "json",

        // apending token in request...
        beforeSend: function (request) {


            // calling function that appends the token defined in token.js file 
            // inside common directory of plugins.
            if (!appendToken(request)) {

                // if the token is not in the localStorage...
                redirectLogin();
            }
        },

        // if response is successs...
        success: function (response) {

            // calling function that verifies the token defined in token.js file 
            // inside common directory of plugins.
            if (verifyToken(response)) {

                // if response status is 200...
                if (response.status == 200) {
                    if (response.data != null) {
                        availableSchools.length = 0;

                        //each loop to push all relevant schools in array...
                        $.each(response.data, function (k, obj) {
                            availableSchools.push(obj.name);
                        })
                    }
                }
            }

            // if token is not verified...
            else {
                redirectLogin();
            }
        },
        error: function (error) {
            console.error(error);
        }
    })
});



// when user clicks on search button to search for program...
$("#search_form").submit(function (e) {
    e.preventDefault();

    // call ajax to get get the detail of course...
    $.ajax({
        url: student_server_url + 'StudentDashboard.php',
        type: "post",
        dataType: "json",
        data: $("#search_form").serializeArray(),

        // appending token in request...
        beforeSend: function (request) {


            // calling function that appends the token defined in token.js file 
            // inside common directory of plugins.
            if (!appendToken(request)) {

                // if the token is not in the localStorage...
                redirectLogin();
            }
        },

        // if response is success...
        success: function (response) {


            // calling function that verifies the token defined in token.js file 
            // inside common directory of plugins.
            if (verifyToken(response)) {

                // if response status is 200...
                if (response.status == 200) {
                    if (response.data != null) {
                        $("#course_div").show();
                        $("#c_empty").hide();
                        $("#c_name").html(response.data.name);
                        $("#s_name").html(response.data.s_name);
                        $("#c_image").attr('src', course_assets_url + "images/" + response.data.image);
                        $("#view_detail").attr('c_id', response.data.c_id);
                        $("#view_detail").attr('s_id', response.data.s_id);

                    } else {
                        $("#course_div").hide();
                        $("#c_empty").show();
                    }

                } else {
                    swal({
                        title: response.message,
                        icon: "error"
                    })
                }
            }

            // if token not verified the redirecting user to login page...
            else {
                redirectLogin();
            }
        },

        // if response is error...
        error: function (error) {
            swal({
                title: "Internal Server Error",
                icon: "error"
            })
        }
    })
})

// when user clicks on view detail button to view the course in detail...
$("#view_detail").click(function () {
    var c_id = btoa($(this).attr('c_id'));
    var s_id = btoa($(this).attr('s_id'));

    // redirecting to view detail page of particular course...
    window.location.href = base_url + "view-course?c_id=" + c_id;
})

