

var course_id;

var params = window.location.search;
var url_params = new URLSearchParams(params);
if (url_params.has('c_id')) {
    course_id = url_params.get('c_id');
}

$("#submit_btn").hide();

var data = { 'val': 'getIntakes', course_id: course_id };

getCourseIntake(data);



function getCourseIntake(data) {

    var html = "";
    var month_arr = [];

    $.ajax({
        url: course_server_url + "GetIntakes.php",
        dataType: "json",
        data: data,
        beforeSend: function (request) {

            if (!appendToken(request)) {
                schoolRedirectLogin();
            }
        },
        success: function (response) {
            if (verifyToken(response)) {
                if (response.status == 200) {
                    // console.log(response);

                    if (response.hasOwnProperty('c_intake')) {
                        var html = "";
                        $.each(response.c_intake, function (k, obj) {
                            html += "<option class='month' value='" + obj.id + "' month_name='" + obj.name + "'>" + obj.name + "&nbsp;&nbsp;" + obj.year + "</option>";
                        })
                        $("#intakes").html(html);

                        $('#intakes').multiSelect({
                            afterSelect: function (month_id) {
                                $("#submit_btn").show();

                                month_arr.push(month_id);
                                var intake_html = "";
                                var intake_month = $("#intakes option[value='" + month_id + "']").text();

                                intake_html += "<span id='" + month_id + "'><h3>Enter start and end date of ";
                                intake_html += intake_month + " intake</h3><label>Start Date</label>"
                                intake_html += "<input class='start_" + intake_month + "' type='text' name='date[" + month_id + "][start_date]' placeholder='Enter start Date' required><br>";
                                intake_html += "<label>End Date</label><input class='end_" + intake_month + "' type='text' name='date[" + month_id + "][end_date]' placeholder='Enter End Date' required><br>"
                                intake_html += "<label>Application Deadline</label><input type='text' class='deadline_" + intake_month + "' name='deadline[" + month_id + "]' placeholder='Enter Application Deadline' required></span>"
                                $("#intake_date").append(intake_html);

                                $(".start_" + intake_month).datepicker({
                                    changeMonth: true,
                                    changeYear: true
                                });
                                $(".end_" + intake_month).datepicker({
                                    changeMonth: true,
                                    changeYear: true
                                });
                                $(".deadline_" + intake_month).datepicker({
                                    changeMonth: true,
                                });
                            },
                            afterDeselect: function (month_id) {
                                month_arr.pop(month_id);

                                $("#" + month_id).remove();

                                if (month_arr.length == 0) {
                                    $("#submit_btn").hide();
                                }

                            }
                        });
                    }

                } else {
                    errorSwal(response);
                }
            } else {
                schoolRedirectLogin();
            }

        },
        error: function (error) {
            var response = { status: 400, 'message': 'Internal Server Error' };
            errorSwal(response);
        }
    })
}
viewIntakeTable();


function viewIntakeTable() {
    $("#intake_table").DataTable({
        "lengthMenu": [5, 10, 20, 30, 40],
        "pageLength": 5,
        "processing": true,
        "serverSide": true,
        "order": [[0, "desc"]],
        "language": {
            "emptyTable": "No intake available"
        },

        "destroy": true,
        "columnDefs": [
            // { targets: '_all', visible: true },
            { "orderable": false, "targets": [6] }

        ],
        "ajax": ({
            url: course_server_url + "GetIntakes.php",
            data: { course_id: course_id, val: "getCurrentIntakes" },
            beforeSend: function (request) {

                if (!appendToken(request)) {
                    schoolRedirectLogin();
                }
            }
        }),
        "initComplete": function (seting, response) {
            // console.log(response);

            // calling function that verifies the token defined in token .js file 
            // inside common directory of plugins.
            if (verifyToken(response)) {
                if (response.status == 400) {
                    errorSwal(response);
                }
            } else {
                schoolRedirectLogin();
            }
        }
    });
}

$("#add_intake_form").submit(function (e) {
    e.preventDefault();

    var form = document.getElementById('add_intake_form');
    var form_data = new FormData(form);
    form_data.append('course_id', course_id);

    var type = $("#submit_btn").attr('data_type')
    switch (type) {
        case 'add_intake':
            form_data.append('val', 'addCourseIntake');
            break;

        case 'update_intake':
            form_data.append('val', 'updateCourseIntake');
            break;

    }

    $.ajax({
        url: course_server_url + "CourseIntake.php",
        type: "post",
        data: form_data,
        dataType: "json",
        contentType: false,
        processData: false,
        beforeSend: function (request) {

            if (!appendToken(request)) {
                schoolRedirectLogin();
            }
        },
        success: function (response) {
            if (verifyToken(response)) {
                sweetalert(response);

                if (response.status == 200) {
                    setTimeout(function () {
                        location.reload();
                    }, 1500)
                }

            } else {
                schoolRedirectLogin();
            }
        }, error: function (error) {
            var response = { status: 400, 'message': 'Internal Server Error' };
            errorSwal(response);
        }
    })
})

// when school clicks on edit button to edit any course intake...
$(document).on('click', '.edit_intake', function () {
    var intake_html = "";

    var intake_id = $(this).attr('intake_id');
    var data = { intake_id: intake_id, val: 'getCourseIntakeDetail' };

    $.ajax({
        url: course_server_url + "GetIntakes.php",
        data: data,
        dataType: "json",
        beforeSend: function (request) {

            if (!appendToken(request)) {
                schoolRedirectLogin();
            }
        },
        success: function (response) {
            if (verifyToken(response)) {

                if (response.status == 200) {
                    // console.log(response.course_intake);
                    var month_id = response.course_intake.intake_id;
                    var month_name = response.course_intake.month_name;

                    intake_html += "<span id='" + month_id + "'><input type='hidden' name='intake_id' value='" + intake_id + "'><h3>Enter start and end date of ";
                    intake_html += month_name + " intake</h3><label>Start Date</label>"
                    intake_html += "<input class='start_" + month_name + "' type='text' id='start_date' name='date[" + month_id + "][start_date]' placeholder='Enter start Date' required><br>";
                    intake_html += "<label>End Date</label><input class='end_" + month_name + "' type='text' id='end_date' name='date[" + month_id + "][end_date]' placeholder='Enter End Date' required><br>"
                    intake_html += "<label>Application Deadline</label><input type='text' id='deadline' class='deadline_" + month_name + "' name='deadline[" + month_id + "]' placeholder='Enter Application Deadline' required><br>"
                    intake_html += "<input type='submit' id='submit_btn' data_type='update_intake' value='Update Intake' class='btn btn-success'></span>";
                    $("#intake_date").html(intake_html);

                    $("#start_date").val(response.course_intake.start_date);
                    $("#end_date").val(response.course_intake.end_date);
                    $("#deadline").val(response.course_intake.deadline);

                    $(".start_" + month_name).datepicker({
                        changeMonth: true,
                        changeYear: true
                    });
                    $(".end_" + month_name).datepicker({
                        changeMonth: true,
                        changeYear: true
                    });
                    $(".deadline_" + month_name).datepicker({
                        changeMonth: true,
                    });

                }

            } else {
                schoolRedirectLogin();
            }
        }, error: function (error) {
            var response = { status: 400, 'message': 'Internal Server Error' };
            errorSwal(response);
        }
    })
})

// when school clicks on remove button to remove any course intake...

$(document).on('click', '.remove_intake', function () {
    var intake_id = $(this).attr('intake_id');

    swal({
        title: "Are you sure you want to remove this intake",
        icon: "warning",
        buttons: [
            'Cancel',
            'Yes,Remove.'
        ]
    }).then(function (val) {
        if (val) {
            removeIntake(intake_id);
        }
    })
})

function removeIntake(intake_id) {
    var data = { intake_id: intake_id, val: 'removeIntake' };

    $.ajax({
        url: course_server_url + "RemoveIntake.php",
        type: "post",
        data: data,
        dataType: "json",
        beforeSend: function (request) {

            if (!appendToken(request)) {
                schoolRedirectLogin();
            }
        },
        success: function (response) {
            if (verifyToken(response)) {
                sweetalert(response);

                if (response.status == 200) {
                    setTimeout(function () {
                        location.reload();
                    }, 1500)
                }

            } else {
                schoolRedirectLogin();
            }
        }, error: function (error) {
            var response = { status: 400, 'message': 'Internal Server Error' };
            errorSwal(response);
        }
    })
}