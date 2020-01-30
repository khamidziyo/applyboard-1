$(document).ready(function () {



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
                { "orderable": false, "targets": [1, 2, 3, 4, 5, 6, 7] }

            ],
            "ajax": ({
                url: course_server_url + "GetAllCourses.php",
                data: { val: "getCourses" },
                beforeSend: function (request) {

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
                        redirectLogin();
                    }
                },
                success: function (response) {
                    if (verifyToken(response)) {

                        if (response.status == 200) {
                            swal({
                                title: response.message,
                                icon: 'success'
                            })
                            viewCourses();
                        } else {
                            swal({
                                title: response.message,
                                icon: 'error',
                            })
                        }
                    } else {
                        redirectLogin();
                    }

                },
                error: function (error) {
                    swal({
                        title: response.message,
                        icon: 'error',
                    })
                }
            })
        }

    })


})

// function that redirects to login page...
function redirectLogin() {
    setTimeout(function () {
        window.location.href = base_url + "school-login-form/";
    }, 2000);
}