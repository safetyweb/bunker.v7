<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

$return = [];

$images = [];

$limit = @$_REQUEST["limit"] ?: 10;
$query = @$_REQUEST["query"];
$query = trim(str_replace(" ", "+", $query));

$search_engine = @$_REQUEST["search_engine"] ?: "google";
$url = $search_engine == "google"
    ? "https://www.google.com/search?q=" . $query . "&tbm=isch"
    : "https://www.bing.com/images/search?q=" . $query . "&scope=images";


$return["query"] = $query;
$return["limit"] = $limit;
$return["search_engine"] = $search_engine;
$return["url"] = $url;
$return["message"] = "";

if ($query == "") {
    $return["message"] = "Nada para ser buscado!";
} else {

    $fp = @file_get_contents($url);

    if ($fp === FALSE)
        $return["message"] = "Não foi possível obter dados de $url";

    if (!$fp)
        $return["message"] = "Não foi possível obter dados de $url";

    if ($return["message"] == "") {

        preg_match_all('/<img[^>]+>/i', $fp, $result);

        $result = $result[0];

        for ($i = 1; $i < count($result); $i++) {

            preg_match('@src="([^"]+)"@', $result[$i], $match);
            $result[$i] = array_pop($match);

            if (@getimagesize($result[$i])) {

                if (count($images) == $limit)
                    break;

                $image["uri"] = trim($result[$i]);

                array_push($images, $image);
            }
        }
    }
}

$return["images"] = $images;

echo json_encode($return);
/*


return $images;
*/