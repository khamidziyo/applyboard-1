<?php
global $wpdb;

if(!isset($wpdb)){
    include_once '../../../../wp-config.php';
}

if(!empty($_GET['email'])){
    $response=[];

    try{
        $email=$_GET['email'];

        // if a mail is invalid...
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
          throw new Exception('Invalid email');
      }
      $sql="select id,email from users where email='".$email."' && role='1'";

      $user=$wpdb->get_results($sql);
   
      if(!empty($user)){
          $token=md5(rand(100000,100000000));
          $wpdb->update('users',['forgot_password_token'=>$token],['id'=>$user[0]->id]);

          $user_id=base64_encode($user[0]->id);

        // url of the forgot password recovery page...
        $url=get_home_url()."/index.php/reset-password/?tok=".$token."&student=".$user_id."&type=student";

        // html to render when mail will be sent to user...
         $msg = "<h1>Hello ".$email."\n Welcome To Apply board.</h1>
        <p>".$url." Please click on the below link to create new password.</p>
        <a class='btn btn-primary' href=".$url."></a>";

        $subject="<h1>Forgot Password Applyboard</h1>";

          // sending mail to user...
          $mail_res=wp_mail($email,$subject,$msg);

          // if mail success...
          if($mail_res){
            $response =['status'=>200,'message'=>'Please check your mail.Mail sent'];
          } else{
            throw new Exception('Mail not sent due to internal server error');
          }

      }else{
          throw new Exception('This email does not exists');
      }

    }catch(Exception $e){
        $response=['status'=>Error_Code,'message'=>$e->getMessage()];
    }

}else{
    $response=['status'=>Error_Code,'message'=>"Email is required"];
}
echo json_encode($response);
?>