<?php 

global $wpdb;

if(!isset($wpdb)){
    include_once '../../../../wp-config.php';
}

include_once(dirname(__FILE__,3)."/common/Jwt.php");

if(!empty($_POST['email'])){

    try{
        $email=$_POST['email'];
        $password=$_POST['password'];
        
        // query to check that email or password matches...
        $login_res=$wpdb->get_results("select id,email,status from school where email='".$email."' && password='".md5($password)."'");
        
        // get the status of user...
        $status=$login_res[0]->status;

        // get email and id if user is not verified to verify it again...
        $email=$login_res[0]->email;

        $id=$login_res[0]->id;
        $data=['email'=>$email,'id'=>$id];

        // if email password matches...
        if(!empty($login_res)){

        // if status is 0 means account is not verified...
        if($status==0){
            $response=['status'=>401,'message'=>'Your account is not verified.Please verify it first','data'=>$data];
            echo json_encode($response);
            exit();
        }

        $payload = [
            'iat' => time(),
            'iss' => 'localhost',
            'exp' => time() +(24*60*60),
            'userId' => $login_res[0]->id
        ];

             $access_token=Jwt::encode($payload,Secret_Key);
    
            // if user is verified then login success...
            $data=['email'=>$email,'id'=> $login_res[0]->id,'role'=>0,'token'=>$access_token];
            $response=['status'=>200,'message'=>'Login Success','data'=>$data];
        } 
        // if email and password are incorrect...
        else{
            throw new Exception('Incorrect email or password');
        }
    }
    //return the error message if found any error... 
    catch(Exception $e){
        $response=['status'=>400,'message'=>$e->getMessage()]; 
    }
    echo json_encode($response);

}

?>