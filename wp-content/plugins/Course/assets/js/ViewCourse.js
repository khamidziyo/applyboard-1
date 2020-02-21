

viewCourses();


function viewCourses() {

    $("#view_course_table").DataTable({
        "lengthMenu": [5, 10, 20, 30, 40],
        "pageLength": 5,
        "processing": true,
        "serverSide": true,
        "order": [[0, "desc"]],
        "language": {
            "emptyTable": "No course available"
        },

        "destroy": true,
        "columnDefs": [
            // { targets: '_all', visible: true },
            { "orderable": false, "targets": [3, 4, 5, 6] }

        ],
        "ajax": ({
            url: course_server_url + "GetAllCourses.php",
            data: { val: "getCourses" },
            beforeSend: function (request) {

                if (!appendToken(request)) {
                    schoolRedirectLogin();
                }
            }
        }),
        "initComplete": function (seting, response) {

            // calling function that verifies the token defined in token .js file 
            // inside common directory of plugins.
            if (verifyToken(response)) { } else {
                schoolRedirectLogin();
            }
        }
    });
}

// $(document).on('click', '.intake', function () {
//     var avail_html = "";

//     course_id = $(this).attr('c_id');

//     var data = { course_id: course_id, 'val': 'getCourseIntake' };

//     $.ajax({
//         url: course_server_url + "CourseIntake.php",
//         type: "post",
//         dataType: "json",
//         data: data,
//         beforeSend: function (request) {

//             if (!appendToken(request)) {
//                 schoolRedirectLogin();
//             }
//         },
//         success: function (response) {
//             if (verifyToken(response)) {
//                 if (response.status == 200) {
//                     $("#intake_modal").modal('show');

//                     if (response.hasOwnProperty('intake_avail')) {
//                         if (response.intake_avail != null) {
//                             var d = new Date();
//                             var year = d.getFullYear();
//                             avail_html += "<h2>Intakes Available for year " + year + "</h2>";

//                             $.each(response.intake_avail, function (k, obj) {
//                                 avail_html += "<input type='checkbox' data_name='" + obj.name + "' class='intake_month' name='intake' value='" + obj.id + "' year='" + year + "' required>" + obj.name
//                                 avail_html += "<span id='" + obj.name + "'></span>";
//                             })
//                         }
//                     }
//                     if (response.hasOwnProperty('intake_avail_next')) {

//                         if (response.intake_avail_next != null) {
//                             var nxtyr = year + 1;

//                             avail_html += "<h2>Intakes Available for year " + nxtyr + "</h2>";

//                             $.each(response.intake_avail_next, function (k, obj) {
//                                 avail_html += "<input type='checkbox' data_name='" + obj.name + "' class='intake_month' name='intake' value='" + obj.id + "' year='" + nxtyr + "' required>" + obj.name
//                                 avail_html += "<span id='" + obj.name + "'></span>"
//                             })
//                         }
//                     }
//                     $("#intakes").html(avail_html);

//                     // if (response.hasOwnProperty('current_course_intakes')) {

//                     //     if (response.current_course_intakes != null) {

//                     //         $.each(response.current_course_intakes, function (k, obj) {
//                     //             $('.intake_month[value='+obj.intake_id+']').prop('checked', true);
//                     //             // console.log(obj.start_date + " " + obj.end_date);

//                     //             // date_html += "<p>Start Date</p><input type='text' class='start" + month_name + year + "' name=start_date[" + month_id + "][" + year + "] required>";
//                     //             // date_html += "<p>End Date</p><input type='text' class='end" + month_name + year + "' name=end_date[" + month_id + "][" + year + "] required>";
//                     //             // $("#" + month_name).html(date_html);
//                     //         })
//                     //     }
//                     // }

//                     // console.log(response.current_course_intakes);

//                 } else {
//                     errorSwal(response);
//                 }
//             } else {
//                 schoolRedirectLogin();
//             }

//         },
//         error: function (error) {
//             var response = { status: 400, 'message': 'Internal Server Error' };
//             errorSwal(response);
//         }
//     })

// });

$(document).on('click', '.add_intake', function () {
    var c_id = $(this).attr('c_id');

    window.location.href = base_url + "add-intake?c_id=" + btoa(c_id);
});




// $(document).on('click', '.intake_month', function () {
//     var date_html = "";
//     var month_id = $(this).val();
//     var year = $(this).attr('year');
//     var month_name = $(this).attr('data_name');

//     if ($(this).prop('checked')) {
//         date_html += "<p>Start Date</p><input type='text' class='start" + month_name + year + "' name=start_date[" + month_id + "][" + year + "] required>";
//         date_html += "<p>End Date</p><input type='text' class='end" + month_name + year + "' name=end_date[" + month_id + "][" + year + "] required>";
//         $("#" + month_name).html(date_html);
//     } else {
//         $("#" + month_name).html('');
//     }
//     var startDate = new Date(year, month_id - 1);
//     var endDate = new Date(year, month_id, 0);


//     $(".start" + month_name + year).datepicker();
//     $(".end" + month_name + year).datepicker();

//     $(".start" + month_name + year).datepicker("setDate", startDate);

//     $(".end" + month_name + year).datepicker("setDate", endDate);

// });



// $("#courseIntake").submit(function (e) {
//     e.preventDefault();
//     var form = document.getElementById('courseIntake');
//     var form_data = new FormData(form);
//     form_data.append('course_id', course_id);
//     form_data.append('val', 'updateCourseIntake');

//     $.ajax({
//         url: course_server_url + "CourseIntake.php",
//         type: "post",
//         dataType: "json",
//         data: form_data,
//         contentType: false,
//         processData: false,
//         beforeSend: function (request) {

//             if (!appendToken(request)) {
//                 schoolRedirectLogin();
//             }
//         },
//         success: function (response) {
//             if (verifyToken(response)) {
//                 sweetalert(response);

//                 if (response.status == 200) {
//                     setTimeout(function () {
//                         location.reload();
//                     }, 1500)
//                 }

//             } else {
//                 schoolRedirectLogin();
//             }

//         },
//         error: function (error) {
//             var response = { status: 400, 'message': 'Internal Server Error' };
//             errorSwal(response);
//         }
//     })
// })

$(document).on('click', '.view', function () {
    var c_id = $(this).attr('c_id');

    window.location.href = base_url + "view-course/?c_id=" + c_id;
})

$(document).on('click', '.edit', function () {
    var c_id = $(this).attr('c_id');
    window.location.href = base_url + "add-course?c_id=" + c_id;
})

$(document).on('click', '.delete', function () {
    var c_id = $(this).attr('c_id');

    var data_obj = { val: "delete_course", c_id: c_id };

    swal({
        title: "Are you sure you want to delete this course",
        icon: 'warning',
        buttons: [
            'Cancel',
            'Yes I am sure'
        ]
    }).then(function (val) {
        if (val) {
            deleteCourse(data_obj);
        }
    })

    // function to delete a particular course...
    function deleteCourse(data) {
        $.ajax({
            url: course_server_url + "DeleteCourse.php",
            type: "post",
            dataType: "json",
            data: data,
            beforeSend: function (request) {

                if (!appendToken(request)) {
                    schoolRedirectLogin();
                }
            },
            success: function (response) {
                if (verifyToken(response)) {
                    sweetalert(response);

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

})