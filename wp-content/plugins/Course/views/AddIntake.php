<?php

function addIntake()
{
    ?>
        <div class="container">

        <form name="add_intake_form" id="add_intake_form">
        <div class="form-group">
      <label for="intakes">Intakes</label>

      <select class="form-control" id="intakes" name="intake[]" multiple="multiple">
        <option selected disabled>Select Intake</option>

      </select>
    </div>

    <div id="intake_date"></div>

<span id="button">
<input type="submit" id="submit_btn" data_type='add_intake' value="Add Intake" class="btn btn-success">
</span>

    </form>

    <table id="intake_table">
    <thead>
    <th>Id</th>
    <th>Month</th>
    <th>Start Date</th>
    <th>End Date</th>
    <th>Application Deadline</th>
    <th>Created On</th>
    <th>Action</th>
    </thead>
    </table>

        </div>



  <script src="<?=constant('course_asset_url')?>js/AddIntake.js"></script>
    <?php
}

add_shortcode('add_intake', 'addIntake');
