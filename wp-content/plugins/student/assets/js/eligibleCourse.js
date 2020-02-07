

var queryString = window.location.search;

if (window.location.href.indexOf("id") > -1) {
    const urlParams = new URLSearchParams(queryString);

    var stu_id = urlParams.get('id');
    var data = { student: stu_id, 'val': 'getEligibleCoursesByAgent' };
    viewCourseTable(data);
}



$("#dob").datepicker({
    changeMonth: true,
    changeYear: true
});


// function to view the courses to those i can apply...
function viewCourseTable(data) {

    $("#eligible_course_table").DataTable({
        "lengthMenu": [5, 10, 20, 30, 40],
        "pageLength": 5,
        "processing": true,
        "serverSide": true,
        "language": {
            "emptyTable": "No school available"
        },

        "destroy": true,
        "columnDefs": [
            // { targets: '_all', visible: true },
            { "orderable": false, "targets": [2, 3, 4, 5] }

        ],
        "ajax": ({
            url: student_server_url + "GetEligibleCourses.php",
            data: data,
            dataType: "json",

            // appending token in the request...
            beforeSend: function (request) {

                // calling function that appends the token defined in token.js file 
                // inside common directory of plugins.
                if (!appendToken(request)) {
                    redirectLogin();
                }
            }
        }),
        "initComplete": function (seting, response) {

            // calling function that verifies the token defined in token .js file 
            // inside common directory of plugins.
            if (verifyToken(response)) { } else {
                redirectLogin();
            }
        }
    });
}

$("#filter_applications").submit(function (e) {
    e.preventDefault();
    var form_data = $("#filter_applications").serializeArray();
    if (form_data.length > 0) {
        // console.log(form_data);

        var data = { student: window.stu_id, 'val': 'getEligibleCoursesByAgent', 'filter': form_data };

        viewCourseTable(data);
    }

})

if (localStorage.getItem('data') != null) {
    var local_data = JSON.parse(localStorage.getItem('data'));

    switch (local_data.role) {

        case '3':
            var data = { 'val': 'getDataByAgent' };
            break;

        case '4':
            var data = { 'val': 'getDataBySubAgent' };
            break;

        default:
            Swal({
                title: "No role match found",
                icon: 'error',
            })
    }
    getDropdownData(data);
}


getDropdownData(data);

function getDropdownData(data) {
    var cntry_html = "";
    var category_html = "";
    var school_html = "";
    var discipline_html = "";

    $.ajax({
        url: agent_server_url + "GetData.php",
        dataType: "json",
        data: data,

        beforeSend: function (request) {
            if (!appendToken(request)) {
                agentRedirectLogin();
            }
        }, success: function (response) {
            if (verifyToken(response)) {
                if (response.status == 200) {


                    if (response.hasOwnProperty('cntry_data')) {


                        // each loop to display countries in drop down...
                        $.each(response.cntry_data, function (k, obj) {
                            cntry_html += "<option value=" + obj.id + ">" + obj.name + "</option>";
                        });

                        // displaying countries in the dropdown...
                        $("#countries").html(cntry_html);

                        $('#countries').multiselect({
                            columns: 1,
                            placeholder: 'Select Countries',
                            search: true,
                            selectAll: true
                        });
                    }

                    if (response.hasOwnProperty('categories')) {


                        // each loop to display categories in drop down...
                        $.each(response.categories, function (k, obj) {
                            category_html += "<option value=" + obj.id + ">" + obj.name + "</option>";
                        });

                        // displaying categories in the dropdown...
                        $("#categories").html(category_html);


                        $('#categories').multiselect({
                            columns: 1,
                            placeholder: 'Select Category',
                            search: true,
                            selectAll: true
                        });
                    }

                    if (response.hasOwnProperty('disciplines')) {

                        // each loop to display schools in drop down...
                        $.each(response.disciplines, function (k, obj) {
                            discipline_html += "<option value=" + obj.id + ">" + obj.name + "</option>";
                        });
                        // console.log(school_html);

                        // displaying schools in the dropdown...
                        $("#discipline").html(discipline_html);


                        $('#discipline').multiselect({
                            columns: 1,
                            placeholder: 'Select School',
                            search: true,
                            selectAll: true
                        });
                    }


                    if (response.hasOwnProperty('school_data')) {

                        // each loop to display schools in drop down...
                        $.each(response.school_data, function (k, obj) {
                            school_html += "<option value=" + obj.id + ">" + obj.name + "</option>";
                        });
                        // console.log(school_html);

                        // displaying schools in the dropdown...
                        $("#schools").html(school_html);


                        $('#schools').multiselect({
                            columns: 1,
                            placeholder: 'Select School',
                            search: true,
                            selectAll: true
                        });
                    }


                }
                errorSwal(response);
            } else {
                agentRedirectLogin();
            }
        }, error: function (error) {
            var response = { 'status': 400, 'message': 'Internal Server Error' };
            errorSwal(response);
        }
    })
}


$("#countries").change(function () {
    let countries = $(this).val();

    var data = { 'val': 'getSchools', 'countries': countries };
    getDropdownData(data);

})



// // when user clicks on not eligible button...
$(document).on('click', '.not_eligible_btn', function () {
    var course_id = $(this).attr('c_id');

    window.location.href = base_url + "view-course?c_id=" + course_id;
});


// when user clicks on apply button of a particular course...
$(document).on('click', '.apply', function () {

    var course = $(this).attr('c_id');

    if (window.stu_id != null) {
        var data = { val: "applyCourseByAgent", course: course, 'student': window.stu_id }
    } else {
        var data = { val: "applyCourseByStudent", course: course };
    }

    $.ajax({
        url: student_server_url + "ApplyCourse.php",
        type: "post",
        dataType: "json",
        data: data,

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
                sweetalert(response);

                if (response.status == 200) {
                    $(this).val("Applied");
                    $(this).attr('disabled', true);
                    setTimeout(function () {
                        location.reload();
                    }, 1000);
                }
            } else {
                redirectLogin();
            }
        },

        // if the response is error...
        error: function (err) {
            var response = { 'status': 400, 'message': 'Internal Server Error' };
            errorSwal(response);
        }
    })
})



// function to redirect on login page if any authentication requires...
function redirectLogin() {
    localStorage.removeItem('data');
    setTimeout(function () {
        window.location.href = base_url + "student-login/";
    }, 1000)
}