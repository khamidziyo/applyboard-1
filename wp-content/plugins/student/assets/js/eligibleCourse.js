
var queryString = window.location.search;
var stu_id;


const urlParams = new URLSearchParams(queryString);


if (urlParams.get('id') != null) {
    stu_id = urlParams.get('id');

    var data = { student: stu_id, 'val': 'getEligibleCoursesByAgent' };
} else {
    var data = { 'val': 'getEligibleCoursesbyStudent' };
}

viewCourseTable(data);


$("#dob").datepicker({
    changeMonth: true,
    changeYear: true
});
var local_data = JSON.parse(localStorage.getItem('data'));

switch (local_data.role) {

    case '3':
        var data = { 'val': 'getDataByAgent' };
        break;

    case '4':
        var data = { 'val': 'getDataBySubAgent' };
        break;

    case '1':
        var data = { 'val': 'getDataByStudent' }
        break;
    default:
        swal({
            title: "No role match found",
            icon: 'error',
        })
}

// function to view the courses to those i can apply...
function viewCourseTable(data) {

    $("#eligible_course_table").DataTable({
        "lengthMenu": [5, 10, 20, 30, 40],
        "pageLength": 5,
        "processing": true,
        "order": [[0, "desc"]],
        "serverSide": true,
        "language": {
            "emptyTable": "No course available"
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
                    redirect();
                }
            }
        }),
        "initComplete": function (seting, response) {

            // calling function that verifies the token defined in token .js file 
            // inside common directory of plugins.
            if (verifyToken(response)) { } else {
                redirect();
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
                redirect();
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
                redirect();
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

$(document).on('click', '.apply', function (e) {
    e.preventDefault();
    var course_id = $(this).attr('c_id');
    var intake = $(e.target).closest("tr").find(".intake option:selected").val();

    if(intake==0){
        swal({
            title:'Please select the intake',
            icon:'error'
        })
        return false;
    }
    $(this).attr('disabled');
    switch (local_data.role) {

        // if logged in user is student...
        case '1':
            var data = { course: course_id, val: 'applyCourseByStudent', intake: intake };
            break;

        // if logged in user is agent...
        case '3':
            var data = { course: course_id, val: 'applyCourseByAgent', student_id: stu_id, intake: intake };
            break;

        // if logged in user is subagent...
        case '4':
            var data = { course: course_id, val: 'applyCourseBySubAgent', student_id: stu_id, intake: intake };
            break;

        default:
            swal({
                title: "No role match found",
                icon: 'error',
            })
    }

    $.ajax({
        url: student_server_url + "ApplyCourse.php",
        type: "post",
        data: data,
        dataType: "json",

        beforeSend: function (request) {
            if (!appendToken(request)) {
                redirect();
            }
        }, success: function (response) {
            if (verifyToken(response)) {
                sweetalert(response);
                if (response.status == 200) {
                    setTimeout(function () {
                        location.reload();
                    }, 1500);
                }
            } else {
                redirect();
            }
        }, error: function (error) {
            var response = { 'status': 400, 'message': 'Internal Server Error' };
            errorSwal(response);
        }
    })
})


// function to redirect on login page if any authentication requires...
function redirect() {
    localStorage.removeItem('data');

    switch (local_data.role) {

        // if logged in user is student...
        case '1':
            studentRedirectLogin();
            break;

        // if logged in user is agent...
        case '3':
            agentRedirectLogin();
            break;

        // if logged in user is subagent...
        case '4':
            subAgentRedirectLogin();
            break;

        default:
            swal({
                title: "No role match found",
                icon: 'error',
            })
    }
}