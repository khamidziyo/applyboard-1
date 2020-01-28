// function that redirects to login page...
function redirectLogin() {
    localStorage.removeItem('data');
    setTimeout(function() {
        window.location.href = "http://localhost/wordpress/wordpress/index.php/admin-login/";
    }, 2000)
}

$(document).ready(function() {
    var i = 0;

    // $('input:checkbox:checked').prop('checked', false);


    // function to get country data on load of script ...
    getDataBYAjax('', 'country');


    // function to get states according to that country...
    $("#country").change(function() {
        var cntry_id = btoa($(this).val());

        //calling function to get states...
        getDataBYAjax(cntry_id, 'state');
    })

    // function to get cities according to that state...
    $("#state").change(function() {
        var state_id = btoa($(this).val());

        //calling function to get cities...
        getDataBYAjax(state_id, 'city');
    })

    // function to set the pincode of particular city...
    $("#city").change(function() {
        var pin_code = $(this).children("option:selected").attr('data_code');

        // set pincode according to city...
        $(".postal_code").val(pin_code);
    })



    // function to check whether accodomation facility available or not...
    $("#accomodation").click(function() {
        var accomodation_bool = $(this).prop('checked');

        // if facility available then ask living cost...
        if (accomodation_bool) {
            $("#living_cost").html('<label>Living Cost<input type="text" name="living_cost" ><br></label>');
        } else {
            $("#living_cost").html('');
        }
    })

    // function to preview pofile image when user selects profile image...
    $("#profile_image_input").change(function() {
        $("#profile_image").show();
        if (this.files && this.files[0]) {

            // creating file reader object...
            var reader = new FileReader();

            reader.onload = function(e) {

                // setting the profile image...
                $('#profile_image').attr('src', e.target.result);
            }

            reader.readAsDataURL(this.files[0]);
        }
    })

    // function to preview cover image when user selects cover image...
    $("#cover_image_input").change(function() {
        $("#cover_image").show();
        if (this.files && this.files[0]) {

            // creating file reader object...
            var reader = new FileReader();

            reader.onload = function(e) {

                // setting the cover image...
                $('#cover_image').attr('src', e.target.result);
            }

            reader.readAsDataURL(this.files[0]);
        }
    })

    // function to check if user wants to upload any school document...
    $("#chk_box").click(function() {
        var chk_bool = $(this).prop('checked');

        // if a checkbox is true...
        if (chk_bool) {

            // displaying the upload file button
            var certificate_html = '<span><label>Upload Certificate</label><input type="file" name="document[]" class="document_input"><input type="button" value="Remove" id="delete"><img src="" id="document_0" name="document" width="200px" height="200px"><br></span>';

            // displaying the image file button...
            $("#certificate_div").html(certificate_html);
        } else {
            $("#certificate_div").html('');
        }
        //    console.log(chk_bool);
    });

    // function to preview the document image to user... 
    $(document).on('change', '.document_input', function() {

        if (this.files && this.files[0]) {

            // creating file reader object...
            var reader = new FileReader();

            reader.onload = function(e) {

                // setting the source of document file
                $('#document_' + i).attr('src', e.target.result);
            }
            reader.readAsDataURL(this.files[0]);
        }

        // when user clicks on add more button...
        $("#add_more_button").html('<input type="button" value="Add More" id="add_more"><br>');
    })

    // function to show document file button when user click on add more button
    $(document).on('click', '#add_more', function() {
        i++;
        var file_html = "<span><input type='file' name='document[]' class='document_input'><input type='button' value='Remove' id='delete'><img src='' id='document_" + i + "' width='200px' height='200px'></span>";

        // setting the certificate div...
        $("#certificate_div").append(file_html);
    })

    // when user click on remove button to remove any certificate...
    $(document).on('click', '#delete', function() {
        $(this).parent().remove();
    })

    // when user submits the form to add school...
    $("#add_school_form").submit(function(e) {
        e.preventDefault();

        // displaying the loading icon to user...
        $("#loading_gif").show();

        // displaying the add school button to user...
        $("#add_school").hide();


        // getting the object of school form...
        var myform = document.getElementById("add_school_form");

        // creating the instance of formdata...
        var form_data = new FormData(myform);

        // ajax call to submit the form data...
        $.ajax({
            url: server_url + 'AddSchoolData.php',
            type: 'post',
            data: form_data,
            dataType: 'json',
            processData: false,
            contentType: false,

            beforeSend: function(request) {
                if (!appendToken(request)) {

                    // if the token is not in the localStorage...
                    redirectLogin();
                }
            },

            // if the data submitted successfully then show success response...
            success: function(response) {
                // hiding the loading gif...
                $("#loading_gif").hide();

                // displaying add school button after response...
                $("#add_school").show();
                if (verifyToken(response)) {

                    // if response return success code 200...
                    if (response.status == 200) {

                        // reset all the form fields...
                        location.reload();
                        // $("#add_school_form")[0].reset();

                        // displaying sweet alert with success message...
                        swal({
                            title: response.message,
                            icon: 'success'
                        })
                    }

                    // displaying error message...
                    else {
                        swal({
                            title: response.message,
                            icon: 'error'
                        })
                    }
                } else {
                    // if the token is not in the localStorage...
                    redirectLogin();
                }
            },

            // if response return with error...
            error: function(error) {

                // hiding loading icon...
                $("#loading_gif").hide();

                // displaying school button again...
                $("#add_school").show();

                // displaying error message in sweet alert...
                swal({
                    title: error,
                    icon: 'error'
                })
            }
        })
        console.log(form_data);
    })
})

// common function to get country state and city according to the set value by ajax...
function getDataBYAjax(data = null, val) {
    // alert(data);
    var html = "";

    // ajax call here...
    $.ajax({
        url: server_url + 'GetData.php',
        type: 'get',
        dataType: 'json',
        beforeSend: function(request) {
            if (!appendToken(request)) {

                // if the token is not in the localStorage...
                redirectLogin();
            }
        },
        data: {
            val: val,
            data: data
        },

        // if there is a success response...
        success: function(response) {
            if (verifyToken(response)) {
                html += '<option name="" value="">Select ' + val + '</option>';

                // if success response with status code 200... 
                if (response.status == 200) {

                    // if value is city then set the dropdown...
                    if (val == "city") {
                        $.each(response.data, function(k, obj) {
                            html += '<option name="' + obj.name + '" value="' + obj.id + '" data_code="' + obj.postal_code + '">' + obj.name + '</option>';
                        })
                    }

                    // if value is country or state then set dropdown ...
                    else {
                        $.each(response.data, function(k, obj) {
                            html += '<option name="' + obj.name + '" value="' + obj.id + '">' + obj.name + '</option>';
                        })
                    }
                    // setting the dropdown html...
                    $("#" + val).html(html);
                }
                // if any error response code 400...
                else {
                    $("#" + val).html(html);
                    swal({
                        title: response.message,
                        icon: 'error'
                    })
                    redirectLogin();
                }
            } else {
                redirectLogin();
            }
        },

        // if not a success response...
        error: function(err) {
            console.log(err);
            swal({
                title: err,
                icon: 'error'
            })
        }
    })
}