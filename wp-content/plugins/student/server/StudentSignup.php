<?php

include_once 'functions.php';
global $wpdb;

if(!isset($wpdb)){
    include_once '../../../../wp-config.php';
}

// if data is not empty...
if(!empty($_POST)){
    try{

        // each loop to check if any value is empty or not...
        foreach($_POST as $key=>$value){
            if(empty($value)){
                throw new Exception($key." is required");
            }
        }

          // if a mail is invalid...
        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        throw new Exception('Invalid email format');
        }

        // checkmail function to check availability of user...
        $mail=checkMail($_POST['email']);
 
        // if user already exist with same email...
        if(!empty($mail)){
            throw new Exception('This email already exists');
        }

        // if length of password is less than 6...
        if(strlen($_POST['password'])<6){
            throw new Exception("Password length should be minimum six characters");
        }

        // if password and confirm password not matches...
        if($_POST['password']!=$_POST['con_password']){
            throw new Exception("Password do not match");
        }



        // hashing the password...
        $password=password_hash($_POST['password'],PASSWORD_DEFAULT);

        $token=base64_encode(rand(1000000,10000000000));

        // insertion array...
        $insert_stu=['email'=>$_POST['email'],'password'=>$password,'verify_token'=>$token,'created_at'=>Date('Y-m-d h:i:s')];
        
         // to start the transaction...
         $wpdb->query( 'START TRANSACTION' );

        // creating student in database...
        $result=$wpdb->insert('users',$insert_stu);

        // get the id of last inserted user...
        $user_id = base64_encode($wpdb->insert_id);

        if($result){

        // url of the verification page...
        $url=get_home_url()."/index.php/account-verification/?tok=".$token."&student=".$user_id."&type=student";

        // html to render when mail will be sent to user...
        $msg = "<h1>Hello ".$_POST['email']."\n Welcome To Apply board.</h1>
        <p>".$url." Please verify your account on clicking the link given below.</p>
        <a class='btn btn-primary' href=".$url."></a>";
    
          // sending mail to user...
            $mail_res=wp_mail($_POST['email'],"<h3>Activate Your Applyboard Account</h3>",$msg);
 
            // if mail sent successfully...
            if($mail_res){
                $wpdb->query( 'COMMIT' );

                $response=['status'=>Success_Code,'message'=>"Mail sent successfully.Please check your mail to veriied.."]; 
            }

            // if mail not sent successfully...
         else{
             echo $mail_res;
            $wpdb->query( 'ROLLBACK' );

            throw new Exception('Mail not sent successfully');
        }

        // if user not created successfully...
        }else{
            throw new Exception('profile not created successfully');
    }
}

    // if any error occurs...
    catch(Exception $e){
        $response=['status'=>Error_Code,'message'=>$e->getMessage()];
    }
}

// if a user directly access this page...
else{
    $response=['status'=>Error_Code,'message'=>"Unauthorized Access"];
}
echo json_encode($response);
?>