<?php
class Connection extends PDO {
  private $_dsn = 'mysql:dbname=guestbook;host=127.0.0.1';
  private $_user = 'root';
  private $_password = '';
  
  public function __construct() {
    try{
      parent::__construct($this->_dsn, $this->_user, $this->_password);
    } catch (PDOException $e) {
      echo 'Подключение не удалось '. $e->getMessage();
    }
  }
}
?>
