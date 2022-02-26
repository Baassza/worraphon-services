<?php
function makeJson($message, $data, $provice = null, $district = null)
{
    $json = new stdClass();
    $json->message = $message;
    if ($provice != null) {
        $json->provice = $provice;
    }
    if ($district != null) {
        $json->district = $district;
    }
    $json->zipcode = $data;
    echo json_encode($json);
}

if (!isset($_GET['provice']) || !isset($_GET['district'])) {
    http_response_code(400);
    makeJson("Missing parameters", []);
    exit;
}
$file = file_get_contents('data.txt');
$lines = explode("\n", $file);
$data = [];
foreach ($lines as $line) {
    $row = [];
    foreach (explode(',', $line) as $item) {
        $row[] = trim($item);
    }
    $data[] = $row;
}
$provice = $_GET['provice'];
$district = $_GET['district'];
$result = [];
foreach ($data as $item) {
    if ($item[0] == $provice && $item[1] == $district) {
        $result[] = $item[2];
    }
}
if (count($result) == 0) {
    http_response_code(404);
    makeJson("Not found", [], $provice, $district);
    exit;
}
makeJson("Success", $result, $provice, $district);
