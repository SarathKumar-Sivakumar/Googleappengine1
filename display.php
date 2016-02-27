<?php

require_once 'google/appengine/api/cloud_storage/CloudStorageTools.php';
use google\appengine\api\cloud_storage\CloudStorageTools;

$object_image_file = 'gs://sarathwebstorage/001.jpg';
$object_image_url = CloudStorageTools::getImageServingUrl($object_image_file,
                                            ['size' => 400, 'crop' => true]);

header('Location:' .$object_image_url);



?>