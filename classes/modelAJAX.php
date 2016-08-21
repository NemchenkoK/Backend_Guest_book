<?php
require_once '../vendor/autoload.php';
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['key'] == 'checkCaptcha') {
  if ($_POST['value'] != $_SESSION['captchaAnswer'.$_POST['index']]) {
    $res['result'] = 0;
    $res['index'] = $_POST['index'];
    Helper::getCaptcha($_POST['index']);
    $res['captchaName'] = $_SESSION['reloadedCaptcha'];
    echo json_encode($res);
  }
}