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

    try {
        if ( verifyUser() ) {
            switch( $_GET['val'] ) {

                // get all the countries...
                case 'getCountries':
                $sql = 'select * from countries';

                // query to get all the countries...
                $data = $wpdb->get_results( $sql );

                // response array...
                $response = ['status'=>Success_Code, 'type'=>'countries', 'message'=>'Countries Fetched Successfully', 'data'=>$data];
                break;

                // get all the schools...
                case 'getSchools':

                // get the country id ...
                $cntry_id = $_GET['cntry_id'];
                if ( $cntry_id == 'all' ) {
                    $sql = "select * from school where status='1'";
                } else {
                    $sql = 'select * from school where countries_id='.$cntry_id." && status='1'";
                }

                // query to get all the schools...
                $data = $wpdb->get_results( $sql );
                $response = ['status'=>Success_Code, 'type'=>'schools', 'message'=>'Schools Fetched Successfully', 'data'=>$data];
                break;

                // get the courses...
                case 'getCourses':

                // decode the school id...
                $id = base64_decode( $_GET['school'] );
            
                $start = $_GET['start'];
                $length = $_GET['length'];

                // set the limit of query...
                $limit = 'limit '.$start.','.$length;

                // sort array...
                $sort_arr = ['c.id', 'c.name'];

                // order by...
                $order_by = 'order by '.$sort_arr[$_GET['order'][0][column]].' '.$_GET['order'][0][dir];

                // search array to search by column name...
                $srch_arr = ['c.id', 'c.name', 'c.code', 'type.name', 'category.name'];

                // if search value is not empty....
                if ( !empty( $_GET['search'][value] ) ) {
                    $where = '&& ';

                    $srch_val = $_GET['search'][value];
                    foreach ( $srch_arr as $col_name ) {
                        $where .= $col_name." like '%".$srch_val."%' or ";
                    }

                    // repalcing the 'or' from last of the string...
                    $where = substr_replace( $where, '', -3 );
                }

                $sql = "select c.id,c.name,c.code,type.name as type_name,category.name as category_name
                 from courses as c join type on type.id=c.type_id join category 
                on category.id=c.category_id where c.school_id=".$id." && c.status='1'";

                // query to get the total results...
                $total_records = $wpdb->get_results( $sql );

                // print_r( $total_records );
                // die;
                $sql = $sql.' '.$where.' '.$order_by.' '.$limit;

                // query to display the filter results....
                $display_records = $wpdb->get_results( $sql );

                // if the records is not empty...
                if ( !empty( $display_records ) ) {
                    foreach ( $display_records as $key=>$obj ) {
                        $record = [];
                        $record[] = $obj->id;
                        $record[] = $obj->name;
                        $record[] = $obj->code;
                        $record[] = $obj->type_name;
                        $record[] = $obj->category_name;
                        $record[] = "<input type='button' value='View' c_id=".base64_encode( $obj->id )." class='btn btn-primary view'>&nbsp;&nbsp;
                    <input type='button' value='Edit' c_id=".base64_encode( $obj->id )." class='btn btn-primary edit'>&nbsp;&nbsp;
                    <input type='button' value='Delete' c_id=".base64_encode( $obj->id )." class='btn btn-danger delete'>";
                        $output['aaData'][] = $record;
                    }
                    $output['iTotalDisplayRecords'] = count( $total_records );
                    $output['iTotalRecords'] = count( $display_records );
                } else {
                    $output['aaData'] = [];
                    $output['iTotalDisplayRecords'] = 0;
                    $output['iTotalRecords'] = 0;
                }

                // return the json output...
                echo json_encode( $output );
                exit;
                break;

                default:
                throw new Exception( 'No match Found' );
                break;
            }
        }
    } catch( Exception $e ) {
        $response = ['status'=>Error_Code, 'message'=>$e->getMessage()];
    }
} else {
    $response = ['status'=>Error_Code, 'message'=>'Unauthorized Access'];
}
echo json_encode( $response );
?>