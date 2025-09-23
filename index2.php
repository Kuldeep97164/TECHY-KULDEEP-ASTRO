<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$uri  = $_SERVER['REQUEST_URI'] ?? '';
$path = parse_url($uri, PHP_URL_PATH);
$path = preg_replace('#^/index2.php#', '', $path);

if (empty($path)) {
    http_response_code(400);
    die("Missing path after index2.php");
}

$baseCdn   = "https://live1-814bffb9b389f652-cf.foxtelgroupcdn.net.au/out/v1";
$targetUrl = rtrim($baseCdn, "/") . $path;
if (!empty($_SERVER['QUERY_STRING'])) {
    $targetUrl .= "?" . $_SERVER['QUERY_STRING'];
}

$proxy = "tcp://46.203.73.222:6749";
$auth  = base64_encode("kdrfmivp:cjua4ymkkpvf");

$opts = [
    "http" => [
        "method" => "GET",
        "header" =>
            "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/127.0.0.1 Safari/537.36\r\n" .
            "Accept: */*\r\n" .
            "Referer: https://www.foxtel.com.au/\r\n" .
            "Origin: https://www.foxtel.com.au\r\n" .
            "Proxy-Authorization: Basic $auth\r\n",
        "proxy" => $proxy,
        "request_fulluri" => true,
        "timeout" => 30
    ]
];

$context = stream_context_create($opts);
$fp = @fopen($targetUrl, "rb", false, $context);

if (!$fp) {
    http_response_code(502);
    die("Failed to fetch: $targetUrl");
}

$meta = stream_get_meta_data($fp);
$contentType = "application/octet-stream";
if (!empty($meta['wrapper_data'])) {
    foreach ($meta['wrapper_data'] as $hdr) {
        if (stripos($hdr, "Content-Type:") === 0) {
            header($hdr);
            $contentType = trim(substr($hdr, 13));
        }
    }
}

if (stripos($contentType, "xml") !== false || stripos($targetUrl, ".mpd") !== false) {
    $data = stream_get_contents($fp);
    fclose($fp);

    $data = preg_replace_callback(
        '/(initialization|media|sourceURL|BaseURL)="([^"]+)"/i',
        function ($m) use ($path) {
            $abs = $m[2];
            if (strpos($abs, "http") !== 0) {
                $base = preg_replace('#/[^/]*$#', '', $path);
                $abs = $base . '/' . ltrim($abs, '/');
            }
            return $m[1] . '="https://astro-ecru-delta.vercel.app/index2.php' . $abs . '"';
        },
        $data
    );

    $data = preg_replace_callback(
        '#<BaseURL>([^<]+)</BaseURL>#i',
        function ($m) use ($path) {
            $abs = $m[1];
            if (strpos($abs, "http") !== 0) {
                $base = preg_replace('#/[^/]*$#', '', $path);
                $abs = $base . '/' . ltrim($abs, '/');
            }
            return "<BaseURL>https://astro-ecru-delta.vercel.app/index2.php$abs</BaseURL>";
        },
        $data
    );

    header("Content-Type: application/dash+xml");
    echo $data;
} else {

    fpassthru($fp);
    fclose($fp);
}
