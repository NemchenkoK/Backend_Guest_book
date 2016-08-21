<?php
require_once __DIR__. '/vendor/autoload.php';
error_reporting( E_ERROR );
session_start();

Helper::checkDirExists();
Helper::removeOldCaptchas();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $userName = (isset($_POST['userName'])) ? addslashes(trim(strip_tags($_POST['userName']))) : '';
  $recordText = (isset($_POST['recordText'])) ? addslashes(trim(strip_tags($_POST['recordText']))) : '';
  try {
    if ($userName == '' || $recordText == '') {
      throw new Exception('Не были введенны все обязательные поля!');
    }
    $keys = array_keys($_POST);
    if (isset($_POST['addRecord'])) {
      if ($_SESSION[$keys[2]] != $_POST[$keys[2]]) {
        throw new Exception('Была введена не правильная каптча!');
      }
      Helper::setRecord($userName, $recordText);
    } elseif (isset($_POST['addAnswer'])) {
      if ($_SESSION[$keys[3]] != $_POST[$keys[3]]) {
        throw new Exception('Была введена не правильная каптча!');
      }
      Helper::setRecord($userName, $recordText, $_POST['idRecord']);
    }
  } catch (Exception $e) {
    echo '<script>alert("'.$e->getMessage().'")</script>';
  } catch (PDOException $e) {
    echo '<script>alert("'.$e->getMessage().'")</script>';
  }
}

$records = Helper::getRecords();
$builder = new Builder();
echo $builder->renderHead();
echo $builder->renderRecords($records);
echo $builder->renderForm();

