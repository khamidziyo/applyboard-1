
$("#dob").datepicker({
    changeMonth: true,
    changeYear: true,
    yearRange: "1980:2014"
});
var local_data;

local_data = JSON.parse(localStorage.getItem('data'));

switch (local_data.role) {

    // if role is agent...
    case "3":
        var data = { val: 'getDataByAgent' };
        break;

    // if role is sub agent...
    case "4":
        var data = { val: 'getDataBySubAgent' };
        break;
}

// function that calls to get the language country and qualifications in dropdown
getStudentData(data);


const queryString = window.location.search;
var stu_id = '';

const urlParams = new URLSearchParams(queryString);
if (urlParams.get('id')) {
    $("#submit_btn").val('Update Student');
    $("#img_input").attr('required', false);
    $("#grade_scheme").attr('required', false);

    stu_id = urlParams.get('id');

    switch (local_data.role) {

        // if role is agent...
        case "3":
            var data = { student: stu_id, 'val': 'editUserByAgent' };

            break;

        // if role is sub agent...
        case "4":
            var data = { student: stu_id, 'val': 'editUserBySubAgent' };

            break;
    }

    // function that calls when student updates the profile
    getUserProfile(data);
}




$("#add_more_btn").click(function () {
    var html = "<span><input type='file' class='form-control' class='documents' name='documents[]'><br>";
    html += "<input type='button' name='remove' Value='Remove' class='remove'></span>"
    $("#add_more").append(html);
})

$(document).on('click', '.remove', function () {
    $(this).parent().remove();
})


// function that invokes when agent edits any user...
function getUserProfile(data) {
    var doc_html = "<h2>Documents Uploaded</h2><ul>";
    var sub_marks_html = "";

    $.ajax({
        url: agent_server_url + "GetStudentProfile.php",
        dataType: "json",
        data: data,

        beforeSend: function (request) {
            if (!appendToken(request)) {
                agentRedirectLogin();
            }
        }, success: function (response) {
            if (verifyToken(response)) {
                if (response.status == 200) {
                    if (response.hasOwnProperty('data')) {

                        switch (local_data.role) {

                            // if role is agent...
                            case "3":
                                var data = { 'val': 'getExamByAgent', 'id': response.data.language_prior };
                                break;

                            // if role is sub agent...
                            case "4":
                                var data = { 'val': 'getExamBySubAgent', 'id': response.data.language_prior };
                                break;
                        }

                        // calling function to get exams of specific language...
                        getStudentData(data);


                        // decoding json to get the exams...
                        var exams = JSON.parse(response.data.exam);
                        // console.log(exams);
                        var sub_arr = Object.values(exams);
                        // console.log(sub_arr);

                        // loop over subject array to get the exams and marks stored in array...
                        $.each(sub_arr, function (k, obj) {

                            var exam_id = Object.keys(exams)[k];

                            $(".exam_input[value=" + exam_id + "]").prop("checked", "true");

                            var exam_name = $(".exam_input[value=" + exam_id + "]").attr('name');

                            sub_marks_html += "<span id='" + exam_name + "'><h2>Marks Scored In " + exam_name + " </h2>";

                            for (arr_key in Object.keys(obj)) {

                                // to get the subject... 
                                sub_marks_html += "<label>" + Object.keys(obj)[arr_key] + "</label>&nbsp;&nbsp;&nbsp"

                                // to get the marks...
                                sub_marks_html += "<input type='text' name='exams[" + exam_id + "][" + Object.keys(obj)[arr_key] + "]' value=" + Object.values(obj)[arr_key] + "><br><br>"
                            }
                            sub_marks_html += "</span>"
                        })

                        $("#sub_marks").html(sub_marks_html);

                        $("#first_name").val(response.data.f_name)
                        $("#last_name").val(response.data.l_name)
                        $("#stu_email").val(response.data.email)
                        $("#pass_number").val(response.data.passport_no)

                        if (response.data.gender == "1") {
                            $("#gender").val("male").prop('checked', true);
                        } else {
                            $("#gender").val("female").prop('checked', true);
                        }

                        $("#marks").val(response.data.score)
                        $("#dob").val(response.data.dob);
                        $("#lang_prior").val(response.data.language_prior);
                        $("#nationality").val(response.data.nationality);
                        $("#qualification").val(response.data.grade_id);
                        $("#visa").val(response.data.has_visa);
                        $("#image").show();
                        $("#image").attr('src', student_assets_url + "images/" + response.data.image)
                        $("#cur_image").val(response.data.image);

                        switch (local_data.role) {

                            // if role is agent...
                            case "3":
                                var data = { 'val': 'getGradeSchemeByAgent', 'id': response.data.grade_id };
                                break;

                            // if role is sub agent...
                            case "4":
                                var data = { 'val': 'getGradeSchemeBySubAgent', 'id': response.data.grade_id };
                                break;
                        }

                        getStudentData(data);
                        $("#grade_scheme").val(response.data.grade_scheme);
                        // console.log(response.data);
                    }
                    if (response.hasOwnProperty('documents')) {
                        if (response.documents.length > 0) {
                            // console.log(response.documents);
                            $.each(response.documents, function (k, obj) {
                                doc_html += "<li>" + obj.document + "<button type='button' class='btn btn-danger remove_doc'";
                                doc_html += "data_id=" + obj.id + ">Remove</button></li><br>";
                            })
                            doc_html += "</ul>";
                            $("#documents").html(doc_html);
                        }
                    }

                } else {
                    errorSwal(response);
                }

            }
        }, error: function (err) {
            var response = { 'status': 400, 'message': 'Internal Server Error' };
            errorSwal(response);
        }
    })
}


// function to get nationality,language and highest qualification...
function getStudentData(data) {
    var cntry_html = "";
    var grade_html = "";
    var language_html = "";
    var exam_html = "";
    var grade_scheme_html = "";
    var grades = [];

    $.ajax({
        url: agent_server_url + "GetData.php",
        dataType: "json",
        data: data,
        async: false,
        beforeSend: function (request) {
            if (!appendToken(request)) {
                agentRedirectLogin();
            }
        }, success: function (response) {
            if (verifyToken(response)) {
                if (response.status == 200) {
                    if (response.hasOwnProperty('cntry_data')) {
                        // country html...
                        cntry_html += "<option selected='selected' disabled>Select Country</option>";

                        // each loop to display countries in drop down...
                        $.each(response.cntry_data, function (k, obj) {
                            cntry_html += "<option value=" + obj.id + ">" + obj.name + "</option>";
                        });

                        // displaying countries in the dropdown...
                        $("#nationality").html(cntry_html);
                    }

                    if (response.hasOwnProperty('grade')) {
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
                            $("#qualification").html(grade_html);
                        });
                    }

                    if (response.hasOwnProperty('languages')) {

                        // language html...
                        language_html += "<option selected='selected' disabled>Select</option>";

                        // each loop to display countries in drop down...
                        $.each(response.languages, function (k, obj) {
                            language_html += "<option value=" + obj.id + ">" + obj.name + "</option>";
                        });

                        // displaying countries in the dropdown...
                        $("#lang_prior").html(language_html);
                    }


                    if (response.hasOwnProperty('exam_data')) {

                        // each loop to display exams as radio buttons...
                        $.each(response.exam_data, function (k, obj) {
                            exam_html += "<input type='checkbox' class='exam_input' value='" + obj.id + "' name='" + obj.name + "'>" + obj.name + "<br>";
                        });

                        // displaying exams in the ...
                        $("#exams").html(exam_html);
                    }

                    if (response.hasOwnProperty('grade_data')) {

                        // each loop to display grade schemes in drop down...
                        grade_scheme_html += "<option selected disabled>Select Grade Scheme</option>";
                        $.each(response.grade_data, function (grade, scheme_arr) {
                            $.each(scheme_arr, function (k, scheme) {
                                grade_scheme_html += "<option value='" + scheme + "'>" + scheme + "</option>";
                            })
                        });
                        $("#grade_scheme").html(grade_scheme_html);
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


$(document).on('click', '.remove_doc', function () {
    var doc_id = $(this).attr('data_id');
    swal({
        title: "Are you sure you want to remove this document",
        icon: "warning",
        buttons: ['Cancel', 'Yes,remove it'],
    }).then(function (val) {
        if (val) {
            removeDoc(doc_id);
        }
    })
})

function removeDoc(doc_id) {

    switch (local_data.role) {

        // if role is agent...
        case "3":
            var data = { doc_id: doc_id, val: 'removeDocumentByAgent' };
            break;

        // if role is sub agent...
        case "4":
            var data = { doc_id: doc_id, val: 'removeDocumentBySubAgent' };
            break;
    }



    $.ajax({
        url: agent_server_url + "RemoveDocument.php",
        type: "post",
        data: data,
        dataType: "json",
        beforeSend: function (request) {
            if (!appendToken(request)) {
                agentRedirectLogin();
            }
        }, success: function (response) {
            if (verifyToken(response)) {
                if (response.status == 200) {
                    setTimeout(function () {
                        location.reload();
                    }, 1500);
                }
                sweetalert(response);
            } else {
                agentRedirectLogin();
            }
        }, error: function (error) {
            var response = { 'status': 400, 'message': 'Internal Server Error' };
            errorSwal(response);
        }
    })
}



$(document).on('click', '.exam_input', function () {
    var exam = $(this).attr('name');

    var exam_id = $(this).val();

    if ($(this).prop('checked')) {
        // when user selects the exam...
        var marks_html = "";

        // then defining the text fields to enter marks in different subjects...
        marks_html += "<span id='" + exam + "'><h2>Marks Scored In " + exam + "</h2>"
        marks_html += "<p>Reading&nbsp;&nbsp;<input type='text' name=exams[" + exam_id + "][reading] id='reading'></p>";
        marks_html += "<p>Writing&nbsp;&nbsp;<input type='text' name=exams[" + exam_id + "][writing] id='writing'></p>";
        marks_html += "<p>Listening&nbsp;&nbsp;<input type='text' name=exams[" + exam_id + "][listening] id='listening'></p>";
        marks_html += "<p>Speaking&nbsp;&nbsp;<input type='text' name=exams[" + exam_id + "][speaking] id='speaking'></p>";
        marks_html += "</span>";
        // setting the marks html...
        $("#sub_marks").append(marks_html);
    } else {
        $("#" + exam).remove();
    }

})

// when user changes the language based on language exam will appear...
$("#lang_prior").change(function () {
    var lang_id = $(this).val();
    $("#sub_marks").html('');
    switch (local_data.role) {

        // if role is agent...
        case "3":

            var data = { 'val': 'getExamByAgent', 'id': lang_id };
            break;

        // if role is sub agent...
        case "4":
            var data = { 'val': 'getExamBySubAgent', 'id': lang_id };
            break;
    }
    // calling function to get exams of specific language...
    getStudentData(data);
})


$("#qualification").change(function () {

    var grade_id = $(this).val();
    switch (local_data.role) {

        // if role is agent...
        case "3":

            var data = { 'val': 'getGradeSchemeByAgent', 'id': grade_id };
            break;

        // if role is sub agent...
        case "4":
            var data = { 'val': 'getGradeSchemeBySubAgent', 'id': grade_id };
            break;
    }

    getStudentData(data);
})

$("#img_input").change(function () {
    previewImage(this);
})

function previewImage(file_input) {
    $("#image").show();
    if (file_input.files && file_input.files[0]) {

        // creating file reader object...
        var reader = new FileReader();

        reader.onload = function (e) {

            // setting the source of document file
            $('#image').attr('src', e.target.result);
        }
        reader.readAsDataURL(file_input.files[0]);
    }
}

$("#add_student").submit(function (e) {
    e.preventDefault();

    var form = document.getElementById('add_student');
    var form_data = new FormData(form);

    if (stu_id != '') {
        form_data.append('student_id', stu_id);
    }

    switch (local_data.role) {

        // if role is agent...
        case "3":
            form_data.append('val', 'addStudentByAgent');
            break;

        // if role is sub agent...
        case "4":
            form_data.append('val', 'addStudentBySubAgent');
            break;
    }

    $.ajax({
        url: student_server_url + "AddStudent.php",
        type: "post",
        dataType: "json",
        data: form_data,
        contentType: false,
        processData: false,

        beforeSend: function (request) {
            if (!appendToken(request)) {
                agentRedirectLogin();
            }
        }, success: function (response) {
            if (verifyToken(response)) {
                sweetalert(response);

                if (response.status == 200) {
                    form.reset();

                    setTimeout(function () {
                        window.location.href = base_url + "view-students/";
                    }, 1500);
                }


            } else {
                agentRedirectLogin();
            }
        }, error: function (error) {
            var response = { 'status': 400, 'message': 'Internal Server Error' };
            errorSwal(response);
        }
    })
})