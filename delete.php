<?php

session_start();
require_once('dbconnect.php');

if(isset($_SESSION['id'])){
  $id=$_REQUEST['id'];
  //expensesテーブルの指定１行を取得
  $messages=$db->prepare('SELECT * from expenses where id=?');
  $messages->execute(array($id));
  $message=$messages->fetch();



  if($message['user_id']==$_SESSION['id']){

    
      
    //指定行を削除
    $del=$db->prepare('DELETE FROM expenses WHERE id=?');
    $del->execute(array($id));
  }
}

header('Location:index.php');
exit();
?>