

// i variable to create new file buttons on add more click...
var i = 0;

var search_params = new URLSearchParams(window.location.search);



// function to get country data on load of script ...
getDataBYAjax('', 'country');

if (search_params.has('sch')) {
    $("#profile_image_input").attr('required', false)
    $("#cover_image_input").attr('required', false)

    var sch_id = search_params.get('sch');
    var data = { school_id: sch_id, val: 'getSchoolProfileByAdmin' };

    getSchoolData(data);

    $("#add_school_btn").val('Update School');

}

// function to get states according to that country...
$("#country").change(function () {
    var cntry_id = btoa($(this).val());

    //calling function to get states...
    getDataBYAjax(cntry_id, 'state');
})

// function to get cities according to that state...
$("#state").change(function () {
    var state_id = btoa($(this).val());

    //calling function to get cities...
    getDataBYAjax(state_id, 'city');
})

// function to set the pincode of particular city...
$("#city").change(function () {
    var pin_code = $(this).children("option:selected").attr('data_code');

    // set pincode according to city...
    $(".postal_code").val(pin_code);
})

// common function to get country state and city according to the set value by ajax...
function getDataBYAjax(data = null, val) {
    // ajax call here...
    $.ajax({
        url: school_server_url + 'GetData.php',
        dataType: 'json',
        async: false,
        data: {
            val: val,
            data: data
        },
        beforeSend: function (request) {
            if (!appendToken(request)) {

                // if the token is not in the localStorage...
                adminRedirectLogin();
            }
        },

        // if there is a success response...
        success: function (response) {
            if (verifyToken(response)) {

                // if success response with status code 200... 
                if (response.status == 200) {

                    if (response.hasOwnProperty('countries')) {

                        var cntry_html = "<option selected='selected' disabled>Select Country</option>";

                        $.each(response.countries, function (k, obj) {
                            cntry_html += '<option value="' + obj.id + '">' + obj.name + '</option>';
                        })

                        // setting the countries html...
                        $("#country").html(cntry_html);
                        $('#country').selectpicker('refresh');

                    }

                    if (response.hasOwnProperty('states')) {
                        // console.log(response.states);
                        var state_html = "<option selected='selected' disabled>Select state</option>";

                        $.each(response.states, function (k, obj) {
                            state_html += '<option value="' + obj.id + '">' + obj.name + '</option>';
                        })
                        // console.log(state_html);

                        // setting the state html...
                        $("#state").html(state_html);
                        $('#state').selectpicker('refresh');
                    }

                    if (response.hasOwnProperty('cities')) {
                        // console.log(response.cities)
                        var city_html = "<option selected='selected' disabled>Select City</option>";
                        $.each(response.cities, function (k, obj) {
                            city_html += '<option value="' + obj.id + '" data_code="' + obj.postal_code + '">' + obj.name + '</option>';
                        })
                        // setting the cities html...
                        $("#city").html(city_html);
                        $('#city').selectpicker('refresh');
                    }
                }
            } else {
                adminRedirectLogin();
            }
        },

        // if not a success response...
        error: function (err) {
            var response = { 'status': 400, 'message': 'Internal Server Error' };
            errorSwal(response);
        }
    })
}


function getSchoolData(data) {
    $.ajax({
        url: school_server_url + 'GetSchoolProfile.php',
        dataType: 'json',
        async: false,
        data: data,
        beforeSend: function (request) {
            if (!appendToken(request)) {

                // if the token is not in the localStorage...
                adminRedirectLogin();
            }
        },

        // if there is a success response...
        success: function (response) {
            if (verifyToken(response)) {

                // if success response with status code 200... 
                if (response.status == 200) {
                    console.log(response)

                    if (response.hasOwnProperty('data')) {
                        var response_data = response.data;
                        $("#school_name").val(response_data.name);
                        $("#school_email").val(response_data.email);
                        $("#address").val(response_data.address);
                        $("#number").val(response_data.number);
                        $("#description").val(response_data.description);
                        $("#country").val(response_data.countries_id);
                        $('#country').selectpicker('refresh');

                        getDataBYAjax(btoa(response_data.countries_id), 'state');

                        $("#state").val(response_data.state_id);
                        $('#state').selectpicker('refresh');

                        getDataBYAjax(btoa(response_data.state_id), 'city');

                        $("#city").val(response_data.city_id);

                        $('#city').selectpicker('refresh');

                        $("#school_type").val(response_data.type);
                        $(".postal_code").val(response_data.postal_code);

                        if (response_data.accomodation == true) {
                            $("#accomodation").prop('checked', true);

                            var html = '<label>Living Cost</label><input class="form-control" type="text" name="living_cost" value="' + response_data.living_cost + '" >'
                            $("#living_cost").html(html);
                        } else {
                            $("#accomodation").prop('checked', false);
                        }

                        if (response_data.work_studying == true) {
                            $("#work_studying").prop('checked', true);
                        } else {
                            $("#work_studying").prop('checked', false);
                        }

                        if (response_data.offer_letter == true) {
                            $("#offer_letter").prop('checked', true);
                        } else {
                            $("#offer_letter").prop('checked', false);
                        }

                        $("#profile_image").show();
                        $("#profile_image").attr('src', school_assets_url + 'images/' + response_data.profile_image);
                        $("#previous_profile_image").val(response_data.profile_image);

                        $("#cover_image").show();
                        $("#cover_image").attr('src', school_assets_url + 'images/' + response_data.cover_image);
                        $("#previous_cover_image").val(response_data.cover_image);

                    }

                    if (response.hasOwnProperty('certificates')) {
                        $("#certificate_label").html("Certificates Uploaded");
                        if (response.certificates.length > 0) {
                            $("#chk_box").prop('checked', true);
                            var certificate_html = "<ul>";

                            $.each(response.certificates, function (k, obj) {
                                var type = obj.document.split('.').pop().toLowerCase();

                                switch (type) {
                                    case 'pdf':
                                        certificate_html += "<li><a href='" + school_assets_url + "certificates/" + obj.document + "' download='" + obj.document + "'><img src='https://www.downloadexcelfiles.com/sites/all/themes/anu_bartik/icon/pdf48.png' width='48' height='48'>PDF</a>&nbsp;&nbsp;<button type='button' class='btn btn-danger remove_doc' data_id=" + obj.id + ">Remove Document</button></li><br>";
                                        break;

                                    case 'docx':
                                        certificate_html += "<li><a href='" + school_assets_url + "certificates/" + obj.document + "' target='_blank' download='" + obj.document + "'><img src='https://www.downloadexcelfiles.com/sites/all/themes/anu_bartik/icon/xlsx48.png' width='48' height='48'>CSV</a>&nbsp;&nbsp;<button type='button' class='btn btn-danger remove_doc' data_id=" + obj.id + ">Remove Document</button></li><br>";
                                        break;

                                    case 'png':
                                        certificate_html += "<li><div style='display: none;' id='hidden_image_" + k + "'><img src='" + school_assets_url + "certificates/" + obj.document + "' width='80%' height='80%'></div><a href='" + school_assets_url + "certificates/" + obj.document + "' data-fancybox data-src='#hidden_image_" + k + "' download='" + obj.document + "'>Image</a>&nbsp;&nbsp;<button type='button' class='btn btn-danger remove_doc' data_id=" + obj.id + ">Remove Document</button></li><br>";
                                        break;

                                    case 'jpg':
                                        certificate_html += "<li><div style='display: none;' id='hidden_image_" + k + "'><img src='" + school_assets_url + "certificates/" + obj.document + "' width='80%' height='80%'></div><a href='" + school_assets_url + "certificates/" + obj.document + "' data-fancybox data-src='#hidden_image_" + k + "' download='" + obj.document + "'>Image</a>&nbsp;&nbsp;<button type='button' class='btn btn-danger remove_doc' data_id=" + obj.id + ">Remove Document</button></li><br>";
                                        break;

                                    case 'jpeg':
                                        certificate_html += "<li><div style='display: none;' id='hidden_image_" + k + "'><img src='" + school_assets_url + "certificates/" + obj.document + "' width='80%' height='80%'></div><a href='" + school_assets_url + "certificates/" + obj.document + "' data-fancybox data-src='#hidden_image_" + k + "' download='" + obj.document + "'>Image</a>&nbsp;&nbsp;<button type='button' class='btn btn-danger remove_doc' data_id=" + obj.id + ">Remove Document</button></li><br>";
                                        break;
                                }
                            })
                            certificate_html += "</ul>"

                            $("#certificate_div").html(certificate_html);
                        } else {
                            $("#certificate_div").html("No certificate Uploaded");
                        }
                    }

                }
            } else {
                adminRedirectLogin();
            }
        },

        // if not a success response...
        error: function (err) {
            var response = { 'status': 400, 'message': 'Internal Server Error' };
            errorSwal(response);
        }
    })
}

$(document).on('click', '.remove_doc', function () {
    var doc_id = $(this).attr('data_id');
    var data = { id: doc_id, val: 'removeCertificateByAdmin' };

    swal({
        title: "Are you sure you want to remove this document",
        icon: "warning",
        buttons: ['Cancel', 'Yes,Remove.']
    }).then(function (val) {
        if (val) {
            removeSchoolCertificate(data);
        }
    })
})

function removeSchoolCertificate(data) {
    // ajax call here...
    $.ajax({
        url: school_server_url + 'DeleteCertificate.php',
        dataType: 'json',
        type: "post",
        async: false,
        data: data,
        beforeSend: function (request) {
            if (!appendToken(request)) {

                // if the token is not in the localStorage...
                adminRedirectLogin();
            }
        },

        // if there is a success response...
        success: function (response) {
            if (verifyToken(response)) {
                sweetalert(response);

                if (response.status == 200) {
                    setTimeout(function () {
                        location.reload();
                    }, 1500);
                }
                console.log(response);
            } else {
                adminRedirectLogin();
            }
        },

        // if not a success response...
        error: function (err) {
            var response = { 'status': 400, 'message': 'Internal Server Error' };
            errorSwal(response);
        }
    })
}



// function to check whether accodomation facility available or not...
$("#accomodation").click(function () {
    var accomodation_bool = $(this).prop('checked');

    // if facility available then ask living cost...
    if (accomodation_bool) {
        $("#living_cost").html('<label>Living Cost</label><input class="form-control" type="text" name="living_cost" >');
    } else {
        $("#living_cost").html('');
    }
})

// function to preview pofile image when user selects profile image...
$("#profile_image_input").change(function () {
    $("#profile_image").show();
    if (this.files && this.files[0]) {

        // creating file reader object...
        var reader = new FileReader();

        reader.onload = function (e) {

            // setting the profile image...
            $('#profile_image').attr('src', e.target.result);
        }

        reader.readAsDataURL(this.files[0]);
    }
})

// function to preview cover image when user selects cover image...
$("#cover_image_input").change(function () {
    $("#cover_image").show();
    if (this.files && this.files[0]) {

        // creating file reader object...
        var reader = new FileReader();

        reader.onload = function (e) {

            // setting the cover image...
            $('#cover_image').attr('src', e.target.result);
        }

        reader.readAsDataURL(this.files[0]);
    }
})

// function to check if user wants to upload any school document...
$("#chk_box").click(function () {
    var chk_bool = $(this).prop('checked');

    // if a checkbox is true...
    if (chk_bool) {

        // displaying the upload file button
        var certificate_html = '<span><label>Upload Certificate</label><input type="file" name="document[]" class="document_input"><input type="button" value="Remove" id="delete"><br></span>';

        // displaying the image file button...
        $("#certificate_div").html(certificate_html);
    } else {
        $("#certificate_div").html('');
    }
    //    console.log(chk_bool);
});

// function to preview the document image to user... 
$(document).on('change', '.document_input', function () {

    if (this.files && this.files[0]) {

        // creating file reader object...
        var reader = new FileReader();

        reader.onload = function (e) {

            // setting the source of document file
            $('#document_' + i).attr('src', e.target.result);
        }
        reader.readAsDataURL(this.files[0]);
    }

    // when user clicks on add more button...
    // $("#add_more_button").html('<input type="button" value="Add More" id="add_more"><br>');
})

// function to show document file button when user click on add more button
$(document).on('click', '#add_more', function () {
    i++;
    var file_html = "<span><input type='file' name='document[]' class='document_input'><input type='button' value='Remove' id='delete'></span>";

    // setting the certificate div...
    $("#certificate_div").append(file_html);
})

// when user click on remove button to remove any certificate...
$(document).on('click', '#delete', function () {
    $(this).parent().remove();
})

// when user submits the form to add school...
$("#add_school_form").submit(function (e) {
    e.preventDefault();

    // displaying the loading icon to user...
    $("#loading_gif").show();

    // displaying the add school button to user...
    $("#add_school").hide();


    // getting the object of school form...
    var myform = document.getElementById("add_school_form");

    // creating the instance of formdata...
    var form_data = new FormData(myform);

    if (localStorage.getItem('data') != null) {
        var local_data = JSON.parse(localStorage.getItem('data'));
        switch (local_data.role) {

            // if logged in user is admin...
            case '2':
                if (search_params.has('sch')) {
                    form_data.append('school_id', search_params.get('sch'));
                    form_data.append('val', 'updateSchoolByAdmin');
                } else {
                    form_data.append('val', 'addSchoolByAdmin');
                }
                break;
        }
    }


    // ajax call to submit the form data...
    $.ajax({
        url: school_server_url + 'AddSchoolData.php',
        type: 'post',
        data: form_data,
        dataType: 'json',
        processData: false,
        contentType: false,

        beforeSend: function (request) {
            if (!appendToken(request)) {

                // if the token is not in the localStorage...
                adminRedirectLogin();
            }
        },

        // if the data submitted successfully then show success response...
        success: function (response) {
            // hiding the loading gif...
            $("#loading_gif").hide();

            // displaying add school button after response...
            $("#add_school").show();
            if (verifyToken(response)) {
                sweetalert(response);

                // reset all the form fields...
                myform.reset();

                // if response return success code 200...
                if (response.status == 200) {


                    setTimeout(function () {
                        location.reload();
                    }, 1500)

                }
            } else {
                // if the token is not in the localStorage...
                adminRedirectLogin();
            }
        },

        // if response return with error...
        error: function (error) {
            // reset all the form fields...
            myform.reset();

            // hiding loading icon...
            $("#loading_gif").hide();

            // displaying school button again...
            $("#add_school").show();

            var response = { 'status': 400, 'message': 'Internal Server Error' };
            errorSwal(response);
        }
    })
})
