<?php

function addSchool()
{
    ?>

    <div class="container-fluid">
    <form name="add_school_form" id="add_school_form">

    <div class="form-group">
      <label for="school_name">Name:</label>
      <input type="text" class="form-control" id="school_name" placeholder="Enter school name" name="name" required>
    </div>

    <div class="form-group">
      <label for="school_email">Email:</label>
      <input type="email" class="form-control" id="school_email" placeholder="Enter school email" name="email" required>
    </div>

    <div class="form-group">
      <label for="address">Address:</label>
      <input type="text" class="form-control" id="address" placeholder="Enter school address" name="address" required>
    </div>

    <div class="form-group">
      <label for="number">Phone Number:</label>
      <input type="number" class="form-control" id="number" name="number"  required>
    </div>

    <div class="form-group">
      <label for="description">Description:</label>
      <textarea class="form-control" id="description" name="description" placeholder="Give a short description about your school." required></textarea>
    </div>

    <div class="form-group">
      <label for="country">Country</label>

      <select class="selectpicker form-control" id="country" data-live-search="true" name="country" required>
      </select>
    </div>

    <div class="form-group">
      <label for="state">State</label>

      <select class="selectpicker form-control" id="state" data-live-search="true" name="state" required>
        <option selected="selected" disabled>Select state</option>

      </select>
    </div>

    <div class="form-group">
      <label for="city selectpicker">City</label>

      <select class="selectpicker form-control" id="city" data-live-search="true" name="city" required>
        <option selected="selected" disabled>Select City</option>

      </select>
    </div>



    <div class="form-group">
      <label for="school_type selectpicker">School Type</label>

      <select class="selectpicker form-control" id="school_type" data-live-search="true" name="school_type" required>
            <option selected="selected" disabled>Select School Type</option>
            <option value="1" name='School'>School</option>
            <option value="2" name='College'>College</option>
            <option value="3" name='University'>University</option>
            <option value="4" name='Institute'>Institute</option>
      </select>
    </div>

    <div class="form-group">
      <label for="postal_code">Postal Code:</label>
      <input type="text" class="form-control postal_code" name="postal_code" disabled required>
      <input type="hidden" name="pin_code" class="postal_code"/>
    </div>

    <div class="form-group">
      <label for="accomodation">Accomodation:</label>
      <input type="checkbox" class="form-control" name="accomodation" id="accomodation" value="1">
    </div>

    <div class="form-group">
    <span id="living_cost"></span>

    </div>


    <div class="form-group">
      <label for="work_studying">Work while studying:</label>
      <input type="checkbox" class="form-control" name="work_studying" id="work_studying" value="1" >
    </div>

    <div class="form-group">
      <label for="offer_letter">Conditional Offer letter:</label>
      <input type="checkbox" class="form-control" name="offer_letter" id="offer_letter" value="1">
    </div>




    <label>Profile Image</label><br>
    <img src="" id="profile_image" name="profile_image" width="200px" height="200px" style="display:none"><br>
    <input type="hidden" name='previous_profile_image' id="previous_profile_image">
    <input type="file" name="profile_image" id="profile_image_input" required>


    <label>Cover Image</label><br>
    <img src="" id="cover_image" name="cover_image" width="200px" height="200px" style="display:none">
    <input type="hidden" name='previous_cover_image' id="previous_cover_image">
    <input type="file" name="cover_image" id="cover_image_input" required>


    <div class="form-group">
      <label for="chk_box" id="certificate_label">Certificate<short>(if any)</short></label>
      <input type="checkbox" class="form-control" name="certificate" id="chk_box" value="1" required>
    </div>

    <div id="certificate_div">
    </div>


        <input type="button" value="Add More" id="add_more"><br>'

    <img src="<?=school_asset_url?>images/loading.gif" id="loading_gif" width="200px" height="200px" style="display:none">
        <input type='submit' value='Add School' class='btn btn-primary' name='submit' id='add_school_btn'>

    </form>

    <script src="<?=school_asset_url?>js/AddSchool.js"></script>
    </div>

    <?php
}
add_shortcode('add_school', 'addSchool');

?>