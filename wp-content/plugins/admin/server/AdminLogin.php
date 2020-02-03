<?php


global $wpdb;

if(!isset($wpdb)){
    include_once '../../../../wp-config.php';
}
if(file_exists(dirname(__FILE__,3)."/common/autoload.php")){
    include_once dirname(__FILE__,3)."/common/autoload.php";
}

if(!empty($_POST)){
    $response=[];

    try{

        // if any of the form field is empty...
        foreach($_POST as $key=>$value){
            if(empty($value)){
                throw new Exception($key." is required");
            }
        }

        $email=$_POST['email'];
        $password=$_POST['password'];

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception('Invalid email');
        }

          // query that fetch the data of user where email exists...
          $sql="select * from users where email='".$email."'";
          $user=$wpdb->get_results($sql);

          // if user with that email exists...
          if(!empty($user)){
              $pass=$user[0]->password;
            
              // then verify its password...
              if(password_verify($password,$pass)){


                  if($user[0]->role!="2"){
                      throw new Exception('Unauthorized Access for user '.$email);
                  }
                  else{

                  $payload = [
                    'iat' => time(),
                    'iss' => 'localhost',
                    'exp' => time() +(24*60*60),
                    'userId' => $user[0]->id,
                ];
                $access_token = Jwt::encode($payload, Secret_Key);

                $data=['email'=>$user[0]->email,'role'=>$user[0]->role,'token'=>$access_token];
                $response=['status'=>Success_Code,'message'=>"Login Success",'data'=>$data];
              }
            }
              
              //if password not exists...
              else{
                  throw new Exception('Password not exists');
              }
          }else{
              throw new Exception('Email not exists');
          }
    }
    
    //catch the exception...
    catch(Exception $e){
        $response=['status'=>Error_Code,'message'=>$e->getMessage()];
    }

    echo json_encode($response);
}
?>