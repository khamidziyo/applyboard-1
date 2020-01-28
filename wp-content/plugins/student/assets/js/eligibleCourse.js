$(document).ready(function () {

    viewCourseTable();

    $("#dob").datepicker({
        changeMonth: true,
        changeYear: true
    });

});

// function to view the courses to those i can apply...
function viewCourseTable() {
    $("#eligible_course_table").DataTable({
        "lengthMenu": [1, 2, 3, 4],
        "pageLength": 1,
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
            data: { val: "getEligibleCourses" },
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

// when user clicks on not eligible button...
$(document).on('click', '.not_eligible_btn', function () {
    var course_id = $(this).attr('c_id');
    window.location.href = "http://localhost/wordpress/wordpress/index.php/view-course/?data=" + course_id;
});

// when user clicks on apply button of a particular course...
$(document).on('click', '.apply', function () {

    // to store all the languages...
    var language_html = "";

    var course = $(this).attr('c_id');
    $.ajax({
        url: student_server_url + "ApplyCourse.php",
        type: "post",
        dataType: "json",
        data: { val: "applyCourse", course: course },

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
                if (response.status == 200) {
                    $(this).val("Already Applied");
                    swal({
                        title: response.message,
                        icon: 'success'
                    })
                }

                // response status 201 if a profile is incomplete...
                else if (response.status == 201) {
                    swal({
                        title: response.message,
                        icon: 'warning'
                    }).then(function () {
                        $("#profile_modal").modal('show');
                        language_html += "<option selected disabled>Select Your Prior Language</option>";
                        $.each(response.languages, function (k, obj) {
                            language_html += "<option value=" + obj.id + ">" + obj.name + "</option>"
                        })
                        $("#lang_prior").html(language_html);
                    })
                }
                else {
                    swal({
                        title: response.message,
                        icon: 'error'
                    })
                }
            } else {
                redirectLogin();
            }
        },

        // if the response is error...
        error: function (err) {
            swal({
                title: "Internal Server Error",
                icon: "error"
            })
            console.error(err);
        }
    })
})

$("#image_input").change(function () {
    $("#image").show();

    if (this.files && this.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#image').attr('src', e.target.result);
        }
        reader.readAsDataURL(this.files[0]);
    }
});

$("#user_profile").submit(function (e) {
    e.preventDefault();
    var form = document.getElementById('user_profile');
    var form_data = new FormData(form);
    form_data.append('val', 'updateUserData');
    $.ajax({
        url: student_server_url + "UpdateProfile.php",
        type: "post",
        dataType: "json",
        data: form_data,
        contentType: false,
        processData: false,
        beforeSend: function (req) {
            if (!appendToken(req)) {
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
                    $("#profile_modal").modal('hide');

                    // function to reload the table...
                    viewCourseTable();

                } else {
                    swal({
                        title: response.message,
                        icon: 'error'
                    })
                }
            } else {
                redirectLogin();
            }
        },
        error: function (error) {
            console.error(error);
            swal({
                title: "Internal Server Error",
                icon: 'error'
            })
        }
    })
    console.log(form_data);
})


// function to redirect on login page if any authentication requires...
function redirectLogin() {
    localStorage.removeItem('data');
    setTimeout(function () {
        window.location.href = "http://localhost/wordpress/wordpress/index.php/student-login/";
    }, 1000)
}