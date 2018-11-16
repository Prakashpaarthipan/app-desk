<?php
session_start();
error_reporting(0);
include('lib/config.php');
include("db_connect/public_functions.php");

/* switch ($_GET['ftp']) {
    case '159':
        $image_folder = 'ftp://'.$ftp_user_name_159.':'.$ftp_user_pass_159.'@'.$ftp_server_159.'/'.$_GET['path'];
        break;

    case 'apd':
        $image_folder = 'ftp://'.$ftp_user_name_apdsk.':'.$ftp_user_pass_apdsk.'@'.$ftp_server_apdsk.'/'.$_GET['path'];
        break;
    
    default:
        $image_folder = 'ftp://'.$ftp_user_name.':'.$ftp_user_pass.'@'.$ftp_server.'/'.$_GET['path'];
        break;
} */

$image_folder = 'ftp://'.$ftp_user_name_apdsk.':'.$ftp_user_pass_apdsk.'@'.$ftp_server_apdsk.$ftp_srvport_apdsk.'/'.$_GET['path'];
if (isset($_GET['pic']) && basename($_GET['pic']) == $_GET['pic']) {
    $pic = $image_folder.$_GET['pic'];
    if (file_exists($pic) && is_readable($pic)) {
        // echo "get the filename extension";
        $ext = substr($pic, -3); 
        // set the MIME type
        switch ($ext) {
            case 'jpeg':
            case 'jpg':
                $mime = 'image/jpeg';
                break;
            case 'gif':
                $mime = 'image/gif';
                break;
            case 'png':
                $mime = 'image/png';
                break;
            case 'pdf':
                $mime = 'application/pdf';
                break;
            default:
                $mime = false;
        }

        if ($mime) {
            header('Content-type:'.$mime);
            header('Content-length:'.filesize($pic));
            $file = fopen($pic, 'rb');
            if ($file) {
                fpassthru($file);
                exit;
            }
        }
    }
}
?>