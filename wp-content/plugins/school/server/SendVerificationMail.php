<?php

global $wpdb;

if(!isset($wpdb)){
    include_once '../../../../wp-config.php';
}

      if(!empty($_POST['val']) && $_POST['val']="verification_mail"){

        // encoding School id...
        $id=base64_encode($_POST['data']['id']);

        $password=md5(rand());


        // generating a random token...
        $token=base64_encode(rand(1000,1000000));

        $email=$_POST['data']['email'];

        $wpdb->update('school',['verify_token'=>$token,'password'=>$password],['id'=>$_POST['data']['id']]);


        // url of the verification page...
        $url=get_home_url()."/index.php/account-verification/?tok=".$token."&school=".$id."&type=school";

        // html to render when mail will be sent to user...
        $msg = "Welcome To Apply board.</h1><p>".$url." Please verify your account on clicking the link given below.</p><a class='btn btn-primary' href=".$url."></a>
         <h3>Email :</h3>".$email."\n <h3>Password : ".$password."</h3>";
      
        try{
          // sending mail to user...
          $mail_res=wp_mail($email,"<h3>Verify Your Applyboard Account</h3>",$msg);

          // if mail success...
          if($mail_res){
            $response =['status'=>200,'message'=>'Please check your mail.Verification mail sent'];
            echo json_encode($response);
          } else{
            throw new Exception('Internal server error');
          }
        }
        // return error response...
        catch(Exception $e){
          $response =['status'=>400,'message'=>$e->getMessage()];
          echo json_encode($response);
        }
      }

?>