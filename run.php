<?php 
include __DIR__ . '/vendor/autoload.php';
$dotenv = Dotenv\Dotenv::create(__DIR__);
$dotenv->load();
$token = getenv('TOKEN');

function get_http_response_code($url) {
    $headers = get_headers($url);
    return substr($headers[0], 9, 3);
}

$discord = new \Discord\Discord([
    'token' => $token]);
$discord->on('ready', function ($discord) {echo "Bot is ready.", PHP_EOL;

    // Listen for events here
    $discord->on('message', function ($message) {if ($message->content == "dogme") {
        $json = file_get_contents('https://dog.ceo/api/breed/husky/images/random'); 
        $json_obj = json_decode($json);
        $message->reply($json_obj->message);
    }

    });
    $discord->on('message', function ($message) {if (substr($message->content, 0, 7) === 'weather') {
        $query = str_replace("weather", "", $message->content);
        $weatherapi = getenv('WEATHERAPI');

        if(get_http_response_code('https://api.openweathermap.org/data/2.5/weather?q='.$query.'&appid='.$weatherapi.'&units=imperial') != "200"){
            $error = true;
        }else{
            $json = file_get_contents('https://api.openweathermap.org/data/2.5/weather?q='.$query.'&appid='.$weatherapi.'&units=imperial'); 
        }
        if($error){
            $message->reply('Sorry couldn\'t find that location.');  
        }else{
            $json_obj = json_decode($json);
            $c=0;
            $count = count($json_obj->weather);
            foreach ($json_obj->weather as $weather_items){
                if($count>1){
                    if($c == 0){
                        $descriptions .= $weather_items->description." and ";
                    }else if ($c == $count - 1){
                        $descriptions .= $weather_items->description;
                    }
                }
                $c++;
            }
            $temp = $json_obj->main->temp;
            
            $weather_statement = "Currently in ".$query.", ".$descriptions." with a temperature of ".$temp."F. Visibility is at ".$json_obj->visibility." feet, with wind speed at ".$json_obj->wind->speed."mph";
    
            $message->reply($weather_statement);     
        }
          

    }

    });
});
$discord->run();

