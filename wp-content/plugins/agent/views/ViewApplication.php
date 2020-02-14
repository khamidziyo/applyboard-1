<?php
function viewApplication()
{
    ?>
    <table id="view_application_table" border="2px solid black">
    <thead>
    <th>Id</th>
    <th>Student Name</th>
    <th>Created By</th>
    <th>School Name</th>
    <th>Course Name</th>
    <th>Submitted On</th>
    <th>Status</th>
    <th>Action</th>
    </thead>
    </table>

    <div class = 'modal fade' id = 'document_modal'>
    <div class = 'modal-dialog'>
    <div class = 'modal-content'>
    <div class = 'modal-header'>
    <button type = 'button' class = 'close' data-dismiss = 'modal'>&times;
    </button>
    <h4 class = 'modal-title'>Upload Documents</h4>
    </div>

    <div class = 'modal-body'>

    <form name="upload_document_form" id="upload_document_form">
    <input type="file" name="upload_document[]" required><br>
    <span id="add_more_docs"></span>
    <input type="button" name="add_more_btn" id="add_more_btn" class="btn btn-primary" value="Add more">

    <input type="submit" value="Upload Document" id="upload_btn" class="btn btn-success">
    </form>
    </div>
    <div class = 'modal-footer'>


    <button type = 'button' class = 'btn btn-default' data-dismiss = 'modal'>Close</button>
    </div>
    </div>
    </div>
    </div>

    <script src="<?=agent_asset_url?>js/ViewApplication.js"></script>

   <?php
}

add_shortcode('view_application', 'viewApplication')
?>
