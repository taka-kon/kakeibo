<!-- 新規登録確認画面 -->
<?php
session_start();
require_once('dbconnect.php');
//送信データがない場合は登録画面に飛ばす
if(!isset($_SESSION['join'])){
  header("Location:register.php");
  exit();
}
if($_SESSION['join']['sex']==1){
  $sex="男性";
}elseif($_SESSION['join']['sex']==2){
  $sex="女性";
}else{
  $sex="その他";
}

$year=htmlspecialchars($_SESSION['join']['year'],ENT_QUOTES);
$month=htmlspecialchars($_SESSION['join']['month'],ENT_QUOTES);
$day=htmlspecialchars($_SESSION['join']['day'],ENT_QUOTES);

$i=3; //変更点


if(!empty($_POST)){
  //年-月-日の配列を作り、DBに格納
  $birthday_array = array($year,$month,$day) ;  //year,month,dayの配列を作る
  $birthday = implode("-",$birthday_array);   //3要素を'-'で区切った文字列にする

  $statement = $db->prepare('INSERT INTO users (name,email,password,sex,birthday,icon) value(?,?,?,?,?,?)');

  $statement->execute(array(
    $_SESSION['join']['name'],
    $_SESSION['join']['email'],
    sha1($_SESSION['join']['password']),
    $_SESSION['join']['sex'],
    $birthday,
    $_SESSION['join']['image'],
  ));

  //この後、記録されたユーザidをexpensesテーブルのuser_idに挿入し、その行の残高を0に設定する。
  //新規登録したユーザのidを取り出す
  // $user_id = $_SESSION['join']['id'];
  $user_ids = $db->query('SELECT max(id) FROM users');
  $user_id = $user_ids->fetch();
  foreach($user_id as $ui){

  }
  
  //DBに記録したのでsessionの内容はいらなくなった
  //残っていると、DBに重複して情報が記録されてしまう
  unset($_SESSION['join']);

  

  header('Location:thanks.php');
  exit();
}
?>
<?php require_once('header.php');?>

    <div class="main-input">
      <h2>確認画面</h2>
  
      <form action="" method="post">
      <!-- hiddenがない場合の問題点
            check.phpの「登録する」ボタンを押して初めてDBに格納
            ↑を確認するため///register.phpではif(!empty($_POST))でPOSTしているか確認できる
            確認画面のcheck.phpではフォームの内容がないのでPOSTがあるかどうかの判断が難しい
            下のhiddenは「送信しています」という合図→確認画面でも「登録」をクリックしたかどうかを簡単に判断できる
       -->
        <input type="hidden" name="action" value="submit">
        <dl>
          <dt>●ニックネーム</dt>
          <dd><?php print(htmlspecialchars($_SESSION['join']['name'],ENT_QUOTES));?></dd>
          <dt>●メールアドレス</dt>
          <dd><?php print(htmlspecialchars($_SESSION['join']['email'],ENT_QUOTES));?></dd>
          <dt>●パスワード</dt>
          <dd>表示されません</dd>
          <dt>●性別</dt>
          <dd><?php print($sex);?></dd>
          <dt>●生年月日</dt>
          <dd><?php print($year."年".$month."月".$day."日");?></dd>
          <dt>●アイコン</dt>
          <dd>
            <?php if(strpos($_SESSION['join']['image'],'-')===false):?>
              <img class="main-input__check-image" src="./other/mem_pic/noImage.png">
              <?php else:?>
                <img class="main-input__check-image" src="./other/mem_pic/<?php print(htmlspecialchars($_SESSION['join']['image'],ENT_QUOTES));?>">

            <?php endif;?>
          </dd>
        </dl>
        <a href="register.php?action=rewrite" class="button button--rewrite">書き直す</a>
        <button class="button button--register">登録する</button>
  
      </form>

    </div>
</body>
</html>