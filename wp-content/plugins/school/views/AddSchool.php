<?php
include_once dirname(__FILE__,2)."/server/functions.php";

function addSchool(){

    if(!empty($_GET['sch'])){
        $s_id=base64_decode($_GET['sch']);
        
        $data=getSchoolDetailById($s_id);
        // echo "<pre>";
        // print_r($data);
        // die;
        }
    ?>
        <script src="<?=asset_url?>js/AddSchool.js"></script>

    <div class="container-fluid">
    <form name="add_school_form" id="add_school_form">
    <p>Name <input type="text" name="name" id="name" value="<?=!empty($data[0])?$data[0]->sch_name:''?>"  required></p>
    
    <p>Email <input type="email" name="email" id="email" value="<?=!empty($data[0])?$data[0]->email:''?>" required email></p>
    
    <p>Address <input type="text" name="address" id="address" value="<?=!empty($data[0])?$data[0]->address:''?>" required></p>
    
    <p>Phone Number <input type="number" name="number" id="number" value="<?=!empty($data[0])?$data[0]->number:''?>" required></p>
    
    <p>Description<br>
    <textarea name="description" id="description" placeholder="Give a short description about your school." required>
    <?=!empty($data[0])?$data[0]->description:''?>
    </textarea></p><br>
    
    <p>Country&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    
    <select name="country" id="country" required>
    <option selected="selected" disabled>Select Country</option>
    </select></p>


    <label>State&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <select name="state" id="state" required>
    <option selected="selected" disabled>Select state</option>
    </select></label><br>

        <?php
        if(!empty($data[0])){
            ?>
    <script>
        var data=getDataBYAjax('<?=base64_encode($data[0]->countries_id)?>','state');
    </script>
            <?php
        }
        ?>


    <p>City &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <select name="city" id="city" required>
    <option selected="selected" disabled>Select City</option>
    </select></p>

    <?php
        if(!empty($data[0])){
            ?>
    <script>
        var data=getDataBYAjax('<?=base64_encode($data[0]->state_id)?>','city');
    </script>
            <?php
        }
        ?>

    <?php
        if(!empty($data)){
   ?>
    <script>
   setTimeout(function(){
      $('#country').children("option[value="+<?=$data[0]->countries_id?>+"]").attr('selected',true)
      $('#school_type').children("option[value="+<?=$data[0]->type?>+"]").attr('selected',true)
      $('#state').children("option[value="+<?=$data[0]->state_id?>+"]").attr('selected',true)
      $('#city').children("option[value="+<?=$data[0]->city_id?>+"]").attr('selected',true)
    },3000);
   </script>
   <?php
    }
   ?>
    <p>School Type &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <select  name="school_type" id="school_type" required>
    <option value=''>Select School Type</option>
    <option value="1" name='School'>School</option>
    <option value="2" name='College'>College</option>
    <option value="3" name='University'>University</option>
    <option value="4" name='Institute'>Institute</option>
    </select></p>

    <p>Postal Code <input type="text" value="<?=!empty($data[0])?$data[0]->postal_code:''?>" name="postal_code" class="postal_code" disabled required></p>
    <input type="hidden" name="pin_code" class="postal_code" value="<?=!empty($data[0])?$data[0]->postal_code:''?>"/>



    <p>Accomodation <input type="checkbox" name="accomodation" id="accomodation" value=1>

    <span id="living_cost"></span>

    </p>

    <?php
if(!empty($data[0]) && $data[0]->accomodation==1){
    ?>
   <script>
   $('#accomodation').prop('checked',true)
    $("#living_cost").html('<label>Living Cost <input type="text" name="living_cost" value=<?=$data[0]->living_cost?>><br></label>');
   </script>
   <?php
}
?>


    <p>Work while studying <input type="checkbox" style="vertical-align:middle" name="work_studying" id="work_studying" value=1 ></p>
    
    <?php
    if(!empty($data[0]) && $data[0]->work_studying==1){
    echo "<script>$('#work_studying').prop('checked',true)</script>";
    }else{
        echo "<script>$('#work_studying').prop('checked',false)</script>"; 
    }
?>

    <p>Conditional Offer letter <input type="checkbox" name="offer_letter" id="offer_letter" value=1></p>

    <?php
    if(!empty($data) && $data[0]->offer_letter==1){
    echo "<script>$('#offer_letter').prop('checked',true)</script>";
    }else{
        echo "<script>$('#offer_letter').prop('checked',false)</script>"; 
    }
?>
    
    <?php
    if(!empty($data[0])){
        ?>
        <p>Profile Image <input type="file" name="profile_image" id="profile_image_input">
         <input type="hidden" name="pro_image" value="<?=$data[0]->profile_image?>">
        <img src="<?=asset_url?>images/<?=$data[0]->profile_image?>" id="profile_image" name="profile_image" width="200px" height="200px"></p>
<?php
       
    }else{
?>
    <p>Profile Image <input type="file" name="profile_image" id="profile_image_input" required>
    <img src="" id="profile_image" name="profile_image" width="200px" height="200px" style="display:none"></p>

<?php
    }
    ?>

    
    
    <?php
    if(!empty($data[0])){
        ?>
    <input type="hidden" name="school_id" id="school_id" value="<?=$_GET['sch']?>">
     <p>Cover Image<input type="file" name="cover_image" id="cover_image_input">
        <input type="hidden" name="co_image" value="<?=$data[0]->cover_image?>">
        <img src="<?=asset_url?>images/<?=$data[0]->cover_image?>" id="cover_image" name="cover_image" width="200px" height="200px"></p>
<?php
       
    }else{
?>
    <p>Cover Image<input type="file" name="cover_image" id="cover_image_input" required>
    <img src="" id="cover_image" name="cover_image" width="200px" height="200px" style="display:none"></p>

<?php
    }
    ?>    
    <p><input type="checkbox" id="chk_box">Certificate<short>(if any)</short></p>
    
    <div id="certificate_div">
    </div>

    <?php
    if(!empty($data) && !empty($data[0]->document)){
        echo "<script>$('#chk_box').prop('checked',true)</script>";
        echo  "<label>Certificates</label>";

        foreach($data as $key=>$obj){
        ?>
        <span>
        <input type="file" name="document[]" class="document_input">
        <input type="button" value="Remove" id="delete">
        <input type="hidden" name="certificates[]" value="<?=$obj->document?>">
        <img src="<?=asset_url.'certificates/'.$obj->document?>" id="document_0" name="document" width="200px" height="200px">
        <br>
        </span>
        <?php
        }
    }
    ?>

    <span id="add_more_button">
    <?php
    if(!empty($data[0])){
        ?>
        <input type="button" value="Add More" id="add_more">
        <?php
    }
    ?>
    </span>
    
    <img src="<?=constant('asset_url')?>images/loading.gif" id="loading_gif" width="200px" height="200px" style="display:none">
    <?php
    if(!empty($data[0])){
        echo "<input type='submit' value='Update School' name='submit' id='add_school'>";
    }else{
        echo "<input type='submit' value='Add School' name='submit' id='add_school'>";
    }
        ?>
    </form>
    
    </div>
    <?php
}
add_shortcode('add_school','addSchool');

?>