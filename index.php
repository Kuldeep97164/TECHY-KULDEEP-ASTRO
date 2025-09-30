<?php
$get = $_GET['get'];
$mpdUrl = 'https://ucdn.starhubgo.com/bpk-tv/' . $get;

$mpdheads = [
  'http' => [
   
      'follow_location' => 1,
      'timeout' => 5
  ]
];
$context = stream_context_create($mpdheads);
$res = file_get_contents($mpdUrl, false, $context);
echo $res;
?>
