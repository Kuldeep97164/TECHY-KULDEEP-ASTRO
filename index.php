<?php
$get = $_GET['get'];
$mpdUrl = 'https://b500e47a210c49479f2313d1a124f855.mediatailor.ap-southeast-1.amazonaws.com/v1/dash/ceb8322ae2e84c32cb0fce196fdc60100025ab50/'. $get;

$mpdheads = [
  'http' => [
      'header' => "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36\r\n",
      'follow_location' => 1,
      'timeout' => 5
  ]
];
$context = stream_context_create($mpdheads);
$res = file_get_contents($mpdUrl, false, $context);
echo $res;
?>
