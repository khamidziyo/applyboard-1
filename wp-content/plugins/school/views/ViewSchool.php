<?php

include_once dirname( __FILE__, 2 ).'/server/functions.php';

function viewSchool() {
    if ( !empty( $_GET['sch'] ) ) {
        $id = base64_decode( $_GET['sch'] );

        // get the school data by id...
        $data = getSchoolDetailById( $id );

        ?>

        <div class = 'container-fluid'>
        <?php
        if ( !empty( $data ) ) {
            ?>

            <h2>School Detail of <b> <?= $data[0]->sch_name?></b></h2><br>

            <p> Email: <b> <?= $data[0]->email?></b></p>

            <p>Address: <b><?= $data[0]->address?></b></p>

            <p>Phone number: <b><?= $data[0]->number?></b></p>

            <p>Description: <b><?= $data[0]->description?></b></p>

            <p>Type : <b><?php

            switch( $data[0]->type ) {
                case 1:
                echo 'School';
                break;

                case 2:
                echo 'College';
                break;

                case 3:
                echo 'University';
                break;

                case 4:
                echo 'Institute';
                break;
            }
            ?>
            </b></p>

            <p>Country : <b><?= $data[0]->cntry_name?></b></p>

            <p>state : <b><?= $data[0]->state_name?></b></p>

            <p>City : <b><?= $data[0]->city_name?></b></p>

            <p>Postal Code: <b><?= $data[0]->postal_code?></b></p>
            
            <p>Accomodation:
            <?php
            if ( $data[0]->accomodation == 1 ) {
                echo '<b>Yes </b>'."<br>";
                echo 'Living Cost : <b>'.$data[0]->living_cost.'</b>';
            } else {
                echo '<b>No</b>';
            }
            ?>
            </p>
            <p>Work while Studying
            <?php
            if ( $data[0]->work_studying == 1 ) {
                echo '<b>Yes</b>';
            } else {
                echo '<b>No</b>';
            }
            ?>
            </p>
            <p>Conditional Offer Letter
            <?php
            if ( $data[0]->offer_letter == 1 ) {
                echo '<b>Yes</b>';
            } else {
                echo '<b>No</b>';
            }
            ?>
            </p>

            <p>Created On:
            <?php
            echo "<b>".date("d-m-Y",strtotime($data[0]->sch_created_at))."</b>";
            ?>
            </p>

            <p>Profile Image</p>   
            <img src="<?=school_asset_url."images/".$data[0]->profile_image?>" width="200px" height="200px">

            <p>Cover Image </p>
            <img src="<?=school_asset_url."images/".$data[0]->cover_image?>" width="200px" height="200px">

            <p> Certificates: </p>
            <?php
            if(!empty($data[0]->document)){
                foreach($data as $key=>$obj){
                    ?>
             <img src="<?=school_asset_url."certificates/".$obj->document?>" width="200px" height="200px"><br><br>
                    <?php
                }
            }
            ?>
            <?php
        }
    }else{
        ?>
        <script>
        swal({
            title:"School id is required.Please try again.",
            icon:'error'
        })
        </script>
        <?php
    }
}
add_shortcode( 'view_school', 'viewSchool' );
?>