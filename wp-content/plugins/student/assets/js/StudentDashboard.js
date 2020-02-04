var availablePrograms = [];
var availableSchools = [];

var currentTab = 0; // Current tab is set to be the first tab (0)

var progress_width = 0;

var grades = [];

// function that loads on script load...

// $(document).ready(function() {


showTab(currentTab); // Display the current tab

//calling function to get the modal dynamic data from server...
getModalData();

// });

// function to show tabs when user fills detail in modal form...
function showTab(n) {
    // This function will display the specified tab of the form...
    var x = document.getElementsByClassName("tab");

    x[n].style.display = "block";
    //... and fix the Previous/Next buttons:

    if (n == 0) {
        document.getElementById("prevBtn").style.display = "none";
    } else {
        document.getElementById("prevBtn").style.display = "inline";
    }
    if (n == (x.length - 1)) {
        $("#nextBtn").val('Finish');
    } else {
        $("#nextBtn").val('Next');
    }
}

function nextPrev(n) {
    if (n == -1) {
        progress_width = progress_width - 35;
    } else {
        progress_width = progress_width + 35;
    }
    // This function will figure out which tab to display
    var x = document.getElementsByClassName("tab");

    // Hide the current tab:
    x[currentTab].style.display = "none";

    // Increase or decrease the current tab by 1:
    currentTab = currentTab + n;

    // if you have reached the end of the form...
    if (currentTab >= x.length) {

        // ... the form gets submitted:
        submitEligibilityData();
        return false;
    }
    document.getElementById("progress_bar").style.width = progress_width + "%";

    // Otherwise, display the correct tab:
    showTab(currentTab);
}

// function to get modal dynamic data...
function getModalData() {
    var cntry_html = "";
    var exam_html = "";
    var category_html = "";
    var grade_html = "";

    $.ajax({
        url: student_server_url + "GetModalData.php",
        type: "get",
        dataType: "json",
        data: { val: "getModalData" },

        // appending token in request...
        beforeSend: function (request) {

            // calling function that appends the token defined in token.js file 
            // inside common directory of plugins.

            if (!appendToken(request)) {

                // if the token is not in the localStorage...
                redirectLogin();
            }
        },

        // if the response is success
        success: function (response) {

            // calling function that verifies the token defined in token.js file 
            // inside common directory of plugins.
            if (verifyToken(response)) {

                // if status is 200...
                if (response.status == 200) {
                    // if the user is not eligible...
                    if (response.eligible != 1) {

                        // then displaying the eligibility modal...
                        $("#eligibilty_modal").modal('show');

                        // if response object has country key in it...
                        if (response.hasOwnProperty("cntry_data")) {

                            // country html...
                            cntry_html += "<option selected='selected' disabled>Select</option>";

                            // each loop to display countries in drop down...
                            $.each(response.cntry_data, function (k, obj) {
                                cntry_html += "<option value=" + obj.id + ">" + obj.name + "</option>";
                            });

                            // displaying countries in the dropdown...
                            $("#nationality").html(cntry_html);
                            $("#country").html(cntry_html);
                        }

                        // if response object has exams key in it...
                        if (response.hasOwnProperty("exams")) {
                            exam_html += "<option selected='selected' disabled>Select</option>";
                            exam_html += "<option value='0'>No I don't have this</option>";

                            // each loop to display exams in drop down...
                            $.each(response.exams, function (k, obj) {
                                exam_html += "<option value=" + obj.id + ">" + obj.name + "</option>";
                            });

                            // set the exams in exam dropdown...
                            $("#exams").html(exam_html);
                        }

                        // if response object has course categories key in it...
                        if (response.hasOwnProperty("course_category")) {

                            // each loop to dynamically display course categories in drop down...
                            $.each(response.course_category, function (k, obj) {
                                category_html += "<input type='checkbox' name='course[" + obj.name + "]'";
                                category_html += "value=" + obj.id + " unchecked=false>" + obj.name;
                            });

                            // set the course category html...
                            $("#course_category").html(category_html);
                        }

                        // if response object has grade key in it...
                        if (response.hasOwnProperty("grade")) {
                            grade_html += "<option selected='selected' disabled>Select Grade</option>";

                            // each loop to dynamically display all classes in drop down...
                            $.each(response.grade, function (k, obj) {

                                // decoding the json...
                                grades.push(JSON.parse(obj.grade_scheme));

                                // loop to get all the classess extracted from json...
                                $.each(JSON.parse(obj.grade_scheme), function (grade) {
                                    grade_html += "<option value=" + obj.id + ">" + grade + "</option>";
                                })

                                //set the html of grade drop down...
                                $("#grade").html(grade_html);
                            });
                        }
                    } else {
                        var btn_html = "<input type='button' style='float:right' width='100px'";
                        btn_html += "value='View Eligible Programs' id='view_eligible_program'>";
                        $("#eligible_program_btn").html(btn_html);
                    }
                }
            }

            // if the token is not verified...
            else {
                redirectLogin();
            }
        },

        // if server response is not success...
        error: function (error) {
            console.error(error);
        }
    })
}

$(document).on('click', '#view_eligible_program', function () {

    window.location.href = "http://localhost/wordpress/wordpress/index.php/eligible-programs/";

});


// function to submit the modal data...
function submitEligibilityData() {

    // ajax that submits modal data to server...
    $.ajax({
        url: student_server_url + "Eligibility.php",
        type: "post",
        dataType: "json",
        data: $("#eligibilityForm").serializeArray(),

        // appending token in the request...
        beforeSend: function (request) {

            // calling function that appends the token defined in token.js file 
            // inside common directory of plugins.
            if (!appendToken(request)) {
                redirectLogin();
            }
        },

        // success response from server...
        success: function (response) {

            // calling function that verifies the token defined in token .js file 
            // inside common directory of plugins.
            if (verifyToken(response)) {

                // if response status is 200...
                if (response.status == 200) {
                    swal({
                        title: response.message,
                        icon: 'success'
                    })

                    // if the modal data successfully submitted then hiding the modal...
                    $("#eligibilty_modal").modal('hide');

                    // function to refresh the page after eligibility data is submitted...
                    setTimeout(function () {
                        location.reload();
                    }, 1500);

                }

                // if response is not success...
                else {
                    swal({
                        title: response.message,
                        icon: 'error'
                    })
                    document.getElementById("progress_bar").style.width = "0%";
                    currentTab = 0;
                    showTab(0);

                }
            }

            // redirecting to login page...
            else {
                redirectLogin();
            }
        },

        // if not a success response...
        error: function (error) {
            swal({
                title: "Internal Server Error",
                icon: "error"
            })
        }
    })
}

// when user selects the exam...
$("#exams").change(function () {
    var marks_html = "";
    var exam_val = $(this).children("option:selected").val();

    // then defining the text fields to enter marks in different subjects...
    if (exam_val != 0) {
        marks_html += "<p>Reading&nbsp;&nbsp;<input type='text' name=exams[" + exam_val + "][reading] id='reading'></p>";
        marks_html += "<p>Writing&nbsp;&nbsp;<input type='text' name=exams[" + exam_val + "][writing] id='writing'></p>";
        marks_html += "<p>Listening&nbsp;&nbsp;<input type='text' name=exams[" + exam_val + "][listening] id='listening'></p>";
        marks_html += "<p>Speaking&nbsp;&nbsp;<input type='text' name=exams[" + exam_val + "][speaking] id='speaking'></p>";
    } else {
        marks_html = "";
    }

    // setting the marks html...
    $("#marks_html").html(marks_html);
})

// when user selects the class...
$("#grade").change(function () {
    var scheme_html = "<option selected='selected' disabled>Select</option>";

    var grade_name = $(this).children("option:selected").text();

    // then based on class setting the dropdown of schemes...
    grades.filter(grade_class => {
        if (grade_class) {
            if (grade_class.hasOwnProperty(grade_name)) {
                $.each(grade_class, function (grade, scheme_arr) {
                    $.each(scheme_arr, function (k, scheme) {
                        scheme_html += "<option>" + scheme + "</option>"
                    })
                })
            }
        }
    });

    // setting the scheme dropdown html...
    $("#scheme").html(scheme_html);
})

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
    window.location.href = "http://localhost/wordpress/wordpress/index.php/view-course?data=" + c_id + "," + s_id;
})


// function that redirects to login page...
function redirectLogin() {
    localStorage.removeItem('data');
    setTimeout(function () {
        window.location.href = "http://localhost/wordpress/wordpress/index.php/student-login/";
    }, 2000)
}