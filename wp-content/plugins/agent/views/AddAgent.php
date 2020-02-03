<?php
function addAgent()
{
    ?>
  <div class="container">
  <h2>Contact Detail of Business</h2>
  <form name="add_agent" id="add_agent">

    <div class="form-group">
      <label for="business_name">Business Name:</label>
      <input type="text" class="form-control" id="business_name" placeholder="Enter business name" name="business_name" required>
    </div>

    <div class="form-group">
      <label for="business_email">Business Email:</label>
      <input type="email" class="form-control" id="business_email" placeholder="Enter business email" name="business_email" required email>
    </div>

    <div class="form-group">
      <label for="password">Password:</label>
      <input type="password" class="form-control" id="password" placeholder="Enter business password" name="password" required>
    </div>

    <div class="form-group">
      <label for="con_password">Confirm Password:</label>
      <input type="password" class="form-control" id="con_password" placeholder="Enter business password" name="con_password" required>
    </div>

    <div class="form-group">
      <label for="business_address">Business Address:</label>
      <input type="text" class="form-control" id="business_address" placeholder="Enter business address" name="business_address" required>
    </div>

    <div class="form-group">
      <label for="business_phone">Business Phone:</label>
      <input type="text" class="form-control" id="business_phone" placeholder="Enter business contact number" name="business_phone" required number>
    </div>

    <div class="form-group">
      <label for="business_site">Business Website:</label>
      <input type="url" class="form-control" id="business_site" placeholder="Enter business website url" name="business_site" required >
    </div>

    <div class="form-group">
      <label for="business_img">Business Image:</label>
      <input type="file" class="form-control" id="business_img" name="business_img" required >
      <img src="" id="image" name="image" style="display:none" width="200px" height="200px">
    </div>

    <h2>Contact Detail Of Authorized Person of Business</h2>

    <div class="form-group">
      <label for="person_name">Name:</label>
      <input type="text" class="form-control" id="person_name" placeholder="Enter name" name="person_name" required >
    </div>

    <div class="form-group">
      <label for="person_mail">Email:</label>
      <input type="email" class="form-control" id="person_mail" placeholder="Enter email address" name="person_mail" required email>
    </div>

    <div class="form-group">
      <label for="person_number">Contact Number:</label>
      <input type="text" class="form-control" id="person_number" placeholder="Enter contact number" name="person_number" required number>
    </div>

    <div class="form-group">
      <label for="person_address">Address:</label>
      <input type="text" class="form-control" id="person_address" placeholder="Enter address" name="person_address" required >
    </div>

    <input type="submit" class="btn btn-success" value="Submit">
     </form>
     <script src="<?=agent_asset_url?>js/AddAgent.js"></script>
</div>
    <?php
}

add_shortcode('add_agent', 'addAgent');
