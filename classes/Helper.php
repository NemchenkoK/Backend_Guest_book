<?php
class Helper{
  public static $count = 0;
 
  public static function getRecords(){
    $db = new Connection();
    $sql = 'select id, pid, userName, text from records';
    $tmp = $db->query($sql);
    $result = $tmp->fetchAll(PDO::FETCH_ASSOC);
    $newResult = [];
    foreach ($result as $val){
      if ($val['pid'] == 0){
        array_push($newResult, $val);
      }
    }
    for ($i = 0; $i < count($newResult); $i++){
      $newResult[$i]['answers'] = [];
      foreach ($result as $val){
        if ($val['pid'] == $newResult[$i]['id']) {
          array_push($newResult[$i]['answers'], $val);
        }
      }
    }
    return $newResult;
  }
  
  public static function setRecord($userName, $recordText, $idRecord = null) {
    if ($idRecord != null) {
      $sql = 'insert into records (pid, userName, text, userIp, userBrowser) values ('.$idRecord.', \''.$userName.'\', \''.$recordText.'\', \''.$_SERVER['REMOTE_ADDR'].'\', \''.$_SERVER['HTTP_USER_AGENT'].'\')';
    } else {
      $sql = 'insert into records (userName, text, userIp, userBrowser) values ( \''.$userName.'\', \''.$recordText.'\', \''.$_SERVER['REMOTE_ADDR'].'\', \''.$_SERVER['HTTP_USER_AGENT'].'\')';
    }
    $db = new Connection();
    if ($db->exec($sql) == 0) {
      throw new PDOException('Ошибка записи Сообщения в базу!');
    }
  }
  
  public static function getCaptcha($index = null) {
    $width = 150;
    $height = 50;
    $sign = 5;
    $imgCode = '';

    $letters = ['A','B','C','D','E','F','G','H','J','K','M','N',
                'P','Q','R','S','T','U','V','W','X','Y','Z',
                '0','1','2','3','4','5','6','7','8','9'];
    $digitalData = [44,66,88,111,133,155,177,199];
  
    $img = imagecreatetruecolor($width, $height);
    $fon = imagecolorallocate($img, 255, 255, 255);
    imagefill($img, 0, 0, $fon);
  
    $letter_Width = intval((0.9*$width)/$sign);
  
    for($j=0; $j<$width; $j++) {
      for($i=0; $i<($height*$width)/600; $i++) {
        $color = imagecolorallocatealpha($img,
          $digitalData[rand(0,count($digitalData)-1)],
          $digitalData[rand(0,count($digitalData)-1)],
          $digitalData[rand(0,count($digitalData)-1)],
          rand(10,30));
        imagesetpixel($img, rand(0,$width), rand(0,$height), $color);
      }
    }
  
    for($i=0; $i<$sign; $i++) {
      $color = imagecolorallocatealpha($img,
        $digitalData[rand(0,count($digitalData)-1)],
        $digitalData[rand(0,count($digitalData)-1)],
        $digitalData[rand(0,count($digitalData)-1)],
        rand(10,30));
    
      $letter = $letters[rand(0,sizeof($letters)-1)];
    
      if(empty($x)) { $x = intval($letter_Width*0.2); }
      else {
        if(rand(0,1))  $x = $x + $letter_Width + rand(0, intval($letter_Width*0.1));
        else $x = $x + $letter_Width - rand(0, intval($letter_Width*0.1));
      }
      $y = rand( intval($height*0.7), intval($height*0.8) );
    
      $size = rand(intval(0.4*$height), intval(0.5*$height));
      $angle = rand(0, 50) - 25;
      $imgCode .= $letter;
      if ($index != null) {
      imagettftext($img, $size, $angle, $x, $y, $color, "../fonts/Ubuntu-M.ttf", $letter);
      } else {
        imagettftext($img, $size, $angle, $x, $y, $color, "fonts/Ubuntu-M.ttf", $letter);
      }
    }
    if ($index != null) {
      $_SESSION['captchaAnswer'.$index] = $imgCode;
      $captchaName = 'reload'. rand((1 * ($index + 1)), (1000 * ($index + 1))). '.jpg';
      $_SESSION['reloadedCaptcha'] = $captchaName;
      imagejpeg($img, '../img/captcha/'.$captchaName);
    } else {
      $_SESSION['captchaAnswer' . self::$count] = $imgCode;
      imagejpeg($img, 'img/captcha/tmp' . self::$count . '.jpg');
      self::$count++;
    }
  }
  
  public static function checkDirExists() {
    if (!file_exists('img/captcha')) {
      mkdir('img/captcha', 0777);
    }
  }
  
  public static function removeOldCaptchas(){
    $files = scandir('img/captcha');
    for ($i = 0; $i < count($files); $i++){
      unlink('img/captcha/'.$files[$i]);
    }
  }
  
}