<?php
global $wpdb;

if ( !isset( $wpdb ) ) {
    include_once '../../../../wp-config.php';
}

if ( file_exists( dirname( __FILE__, 3 ).'/common/autoload.php' ) ) {
    include_once dirname( __FILE__, 3 ).'/common/autoload.php';
}

function verifyUser() {
    $payload = JwtToken::getBearerToken();

    return Admin::verifyUser( $payload );
}

if ( !empty( $_GET ) ) {

    if ( verifyUser() ) {

        $start = $_GET['start'];
        $length = $_GET['length'];
        $limit = 'limit '.$start.','.$length;

        $sort_arr = ['id', 'name'];

        $order_by = 'order by '.$sort_arr[$_GET['order'][0][column]].' '.$_GET['order'][0][dir];

        $srch_arr = ['s.id', 's.name', 's.email', 's.address', 's.number', 's.postal_code', 'countries.name'];

        if ( !empty( $_GET['search'][value] ) ) {
            $where = '&& ';

            $srch_val = $_GET['search'][value];
            foreach ( $srch_arr as $col_name ) {
                $where .= $col_name." like '%".$srch_val."%' or ";
            }

            $where=substr_replace( $where, '',-3);
        }

        $sql = "select s.id,s.name,s.email,s.address,s.number,s.postal_code,countries.name as cntry_name,s.profile_image 
    from school as s join countries on countries.id=s.countries_id where status='1'";

        $total_records = $wpdb->get_results( $sql );

        // print_r( $total_records );
        // die;
        $sql = $sql.' '.$where.' '.$order_by.' '.$limit;

        // echo $sql;die;
        $display_records = $wpdb->get_results( $sql );

        if ( !empty( $display_records ) ) {
            foreach ( $display_records as $key=>$obj ) {
                $record = [];
                $record[] = $obj->id;
                $record[] = $obj->name;
                $record[] = $obj->email;
                $record[] = $obj->address;
                $record[] = $obj->number;
                $record[] = $obj->cntry_name;
                $record[] = $obj->postal_code;
                $record[] = "<img src='".school_asset_url.'/images/'.$obj->profile_image."' width='50px' height='50px'>";

                $record[] = "<input type='button' value='View' s_id=".base64_encode($obj->id)." class='btn btn-primary view'>&nbsp;&nbsp;
            <input type='button' value='Edit' s_id=".base64_encode($obj->id)." class='btn btn-primary edit'>&nbsp;&nbsp;
            <input type='button' value='Delete' s_id=".base64_encode($obj->id)." class='btn btn-danger delete'>";
                $output['aaData'][] = $record;
            }
            $output['iTotalDisplayRecords'] = count( $total_records );
            $output['iTotalRecords'] = count( $display_records );
        } else {
            $output['aaData'] = [];
            $output['iTotalDisplayRecords'] = 0;
            $output['iTotalRecords'] = 0;
        }

        echo json_encode( $output );
    }
}
?>