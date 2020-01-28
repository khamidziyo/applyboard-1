<?php

global $wpdb;

if(!isset($wpdb)){
    include_once '../../../../wp-config.php';
}

if(!empty($_POST)){
    $response=[];

    try{
        foreach($_POST as $key=>$value){
            if(empty($value)){
                throw new Exception($key." is required");
            }
        }

        if(strlen($_POST['password'])<6){
            throw new Exception("Password should not be less than six characters");
        }

        if($_POST['password']!=$_POST['confirm_password']){
            throw new Exception("Password and confirm password are not same");
        }

        // get the forgot password token...
        $token=$_POST['token'];

        // get the student id whose password is to be updated...
        $student_id=base64_decode($_POST['student']);

        // query to update the new password...
        $update=$wpdb->update('users',['password'=>password_hash($_POST['password'],PASSWORD_DEFAULT)],
        ['forgot_password_token'=>$token,'id'=>$student_id]);

        // if password updated successfully...
        if($update){
            $response=['status'=>Success_Code,'message'=>"Password Updated Successfully"];
        }
        // if password not updated...
        else{
            throw new Exception('Password not updated');
        }
       
        // catch the exceptions...
    }catch(Exception $e){
        $response=['status'=>Error_Code,'message'=>$e->getMessage()];
    }
 
    // if user directly access this page...
}else{
    $response=['status'=>Error_Code,'message'=>"Unauthorized Access"];
}

echo json_encode($response);
?>