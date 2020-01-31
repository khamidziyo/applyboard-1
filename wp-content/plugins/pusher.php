<?php
  require __DIR__ . '/vendor/autoload.php';

  $options = array(
    'cluster' => 'ap2',
    'useTLS' => true
  );
  $pusher = new Pusher\Pusher(
    '9d27859f518c27645ae1',
    'e188f03d451b5cfa8179',
    '940473',
    $options
  );

  $data['message'] = 'hello world';
 
  $pusher->trigger('my-channel', 'my-event', $data);
?>
