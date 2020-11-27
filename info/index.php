<?php
header("Access-Control-Allow-Origin: *");
error_reporting(0);
require_once "class.youtube.php";
$yt  = new YouTubeDownloader();
$downloadLinks = '';
$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $videoLink = $_POST['video_link'];
    if (!empty($videoLink)) {
        $vid = $yt->getYouTubeCode($videoLink);
        if ($vid) {
            $result = $yt->processVideo($vid);

            if ($result) {
                //print_r($result);
                $info = $result['videos']['info'];
                $formats = $result['videos']['formats'];
                $adapativeFormats = $result['videos']['adapativeFormats'];
                $videoInfo = json_decode($info['player_response']);

                $title = $videoInfo->videoDetails->title;
                $thumbnail = $videoInfo->videoDetails->thumbnail->thumbnails[0]->url;
                $vid_url = $formats[0]["link"];
                $downloadURL = urldecode($vid_url);
                //print  $downloadURL;exit;
                $type = urldecode($vid_url);
                $vid_title = urldecode($vid_url);

                //Fm thinding file extension froe mime type
                $typeArr = explode("/", $type);
                $extension = $typeArr[0];

                $fileName = $vid_title . '.' . $extension;
                $data_array = array("title" => $title, "thumbnail" => $thumbnail, "video" => $vid_url);
                echo json_encode($data_array);
            } else {
                $error = "Something went wrong";
            }
        }
    } else {
        $error = "Please enter a YouTube video URL";
    }
}
