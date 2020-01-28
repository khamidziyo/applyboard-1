<?php
global $wpdb;

if ( !isset( $wpdb ) ) {
    include_once '../../../../wp-config.php';
}

function getSchoolDetailById( $id ) {

    try {
    if ( empty( $id ) ) {
        throw new Exception( 'School id not found.Please try again' );
    }
    global $wpdb;
        $sql = "select *,s.name as sch_name,s.created_at as sch_created_at,countries.name as cntry_name,state.name as state_name,cities.name as city_name 
        FROM school as s join countries on countries.id=s.countries_id join state on state.id=s.state_id 
        join cities on cities.id=s.city_id left join school_certificate as sc on s.id=sc.school_id where s.id=".$id;
        $data = $wpdb->get_results( $sql );

        if ( empty( $data ) ) {
            throw new Exception( 'No school data found.Please try again' );
        }else{
            return $data;
        }

    } catch( Exception $e ) {
        ?>
        <script>
        swal({
            title:"<?=$e->getMessage()?>",
            icon:'error'
        })
        </script>
        <?php
        return false;
    }

}
?>