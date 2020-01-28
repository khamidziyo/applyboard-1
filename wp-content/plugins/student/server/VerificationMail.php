<?php

global $wpdb;

if(!isset($wpdb)){
    include_once '../../../../wp-config.php';
}

      if(!empty($_POST['val'])){

        // encoding School id...
        $user_id=base64_encode($_POST['id']);



        // generating a random token...
        $token=base64_encode(rand(1000,1000000));

        $email=$_POST['email'];

        $wpdb->update('users',['verify_token'=>$token],['id'=>$_POST['id']]);


        // url of the verification page...
        $url=get_home_url()."/index.php/account-verification/?tok=".$token."&student=".$user_id."&type=student";

        // html to render when mail will be sent to user...
         $msg = "<h1>Hello ".$email."\n Welcome To Apply board.</h1>
        <p>".$url." Please verify your account on clicking the link given below.</p>
        <a class='btn btn-primary' href=".$url."></a>";

      
        try{
          // sending mail to user...
          $mail_res=wp_mail($email,"<h3>Verify Your Applyboard Account</h3>",$msg);

          // if mail success...
          if($mail_res){
            $response =['status'=>200,'message'=>'Please check your mail.Verification mail sent'];
          } else{
            throw new Exception('Mail not sent due to internal server error');
          }
        }
        // return error response...
        catch(Exception $e){
          $response =['status'=>400,'message'=>$e->getMessage()];
        }
        echo json_encode($response);

      }

?>
