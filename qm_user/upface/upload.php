<?php
require_once(dirname(__FILE__)."/../config.php");
require 'AjaxUpload.class.php';
if ($_POST) {
	$form_name = $form_name;
    $file_size = intval($file_size);
    $upload = new AjaxUpload($form_name, $file_size);
}
?>