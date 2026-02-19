<?php
$get = $_GET['get'];
$mpdUrl = 'https://linearjitp-playback.astro.com.my/dash-wv/linear/' . $get;

$mpdheads = [
  'http' => [
      'header' => "User-Agent: Mozilla%2F5.0%20%28Linux%3B%20Android%2010%3B%20MI%209%20Build%2FQKQ1.190825.002%3B%20wv%29%20AppleWebKit%2F537.36%20%28KHTML%2C%20like%20Gecko%29%20Version%2F4.0%20Chrome%2F111.0.5563.58%20Mobile%20Safari%2F537.36\r\n",
      'follow_location' => 1,
      'timeout' => 5
  ]
];
$context = stream_context_create($mpdheads);
$res = file_get_contents($mpdUrl, false, $context);
echo $res;
?>
