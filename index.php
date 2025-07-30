<?php
$get = $_GET['get'];
$mpdUrl = 'https://linears2-playback.astro.com.my/vsg/41PRoPlPVA72W-bkKRiivWcdApMjahRSTuycJVX5nENq4=IkwB/dash-wv/jitp-dashisowm' . $get;

$mpdheads = [
  'http' => [
      'header' => "User-Agent: Mozilla/5.0 (Linux; Android 10; Mi 9T Pro Build/QKQ1.190825.002; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/83.0.4103.101 Mobile Safari/537.36\r\n",
      'follow_location' => 1,
      'timeout' => 5
  ]
];
$context = stream_context_create($mpdheads);
$res = file_get_contents($mpdUrl, false, $context);
echo $res;
?>
