<?php
include __DIR__.'/vendor/autoload.php';
$dotenv = Dotenv\Dotenv::create(__DIR__);
$dotenv->load();
$token = getenv('TOKEN');
$discord = new \Discord\Discord([
    'token' => $token
]);

$discord->on('ready', function ($discord) {
    echo "Bot is ready.", PHP_EOL;
  
    // Listen for events here
    $discord->on('message', function ($message) {
        if($message->content == "dogme"){
            $json = file_get_contents('https://dog.ceo/api/breed/husky/images/random');
            $json_obj = json_decode($json);
            //echo $json_obj->message;
            $message->reply($json_obj->message);
            //var_dump($message);
        }
        //echo "Recieved a message from {$message->author->username}: {$message->content}", PHP_EOL;
    });
});

$discord->run();