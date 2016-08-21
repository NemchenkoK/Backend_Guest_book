<?php
class Builder{

  public function renderHead(){
    $result =
      '<!doctype html>
       <html lang="en">
       <head>
         <meta charset="UTF-8">
         <title>Guest Book</title>
         <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">
         <link rel="stylesheet" href="css/main.css">
         <script src="http://code.jquery.com/jquery-1.12.0.min.js"></script>
         <script src="js/main.js"></script>
       </head>
       <body>
       <div class="wrapper">
         <div class="records">';
    return $result;
  }

  public function renderForm(){
    Helper::getCaptcha();
    $result =
      '</div>
       <div class="form-record">
         <form role="form" class="form-record-body" action="'.$_SERVER['PHP_SELF'].'" method="POST">
           <input type="text" class="form-control my-form-control" name="userName" placeholder="Ваше имя" required>
           <textarea class="form-control record-text" name="recordText"  cols="75" rows="10" placeholder="Введите текст сообщения" required></textarea>
           <img class="my-captcha" src="img/captcha/tmp'.(Helper::$count - 1).'.jpg" alt="">
           <input type="text" class="form-control my-form-control captchaAnswer" name="captchaAnswer'.(Helper::$count - 1).'" placeholder="Введите текст с картинки" required>
           <input type="submit" class="btn btn-success my-btn" name="addRecord" value="Добавить сообщение">
         </form>
       </div>
     </div>';
    return $result;
  }
  
  public function renderRecords($records) {
    $result = '';
    for ($i = 0; $i < count($records); $i++) {
      Helper::getCaptcha();
      $result .=
        '<div class="record">
           <div class="record__img"></div>
           <div class="record__content">
             <div class="record__user-name">'.$records[$i]['userName'].'</div>
             <div class="record__text">'.nl2br($records[$i]['text']).'</div>
             <a class="record__answer" href="#">Ответить</a>
             '. $this->renderAnswers($records[$i]['answers']).'
           </div>
           <div class="form-record inactive --bordered">
             <form role="form" class="form-record-body" action="'.$_SERVER['PHP_SELF'].'" method="POST">
               <input type="hidden" name="idRecord" value="'.$records[$i]['id'].'">
               <input type="text" class="form-control my-form-control" name="userName" placeholder="Ваше имя" required>
               <textarea class="form-control record-text" name="recordText"  cols="75" rows="10" placeholder="Введите текст ответа" required></textarea>
               <img class="my-captcha" src="img/captcha/tmp'.$i.'.jpg" alt="">
               <input type="text" class="form-control my-form-control captchaAnswer" name="captchaAnswer'.$i.'" placeholder="Введите текст с картинки" required>
               <input type="submit" class="btn btn-success my-btn" name="addAnswer" value="Добавить ответ">
             </form>
           </div>  
         </div>';
    }
    return $result;
  }

  private function renderAnswers($answers) {
    $result = '';
    for ($i = 0; $i < count($answers); $i++){
      $result .=
        '<div class="answer">
           <div class="record__img"></div>
           <div class="answer__content">
             <div class="record__user-name">'.$answers[$i]['userName'].'</div>
             <div class="record__text">'.nl2br($answers[$i]['text']).'</div>
           </div>
         </div>';
    }
    return $result;
  }
}