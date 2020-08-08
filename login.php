
<?php
session_start();
require_once('dbconnect.php');

if($_COOKIE['email']!==''){
  $email = $_COOKIE['email'];
}

if(!empty($_POST)){
  $email=$_POST['email'];
  if($_POST['email'] !== '' && $_POST['password']!==''){
    $login = $db->prepare('SELECT * FROM users WHERE email=? and password=?');
    $login->execute(array(
      $_POST['email'],
      sha1($_POST['password']),
    ));
    $user=$login->fetch();

    if($user){
      $_SESSION['id'] = $user['id'];
      $_SESSION['time']=time();

      if($_POST['save']=='on'){
        setcookie('email',$_POST['email'],time()+60*60*24*14);
      }
      header('Location:index.php');
      exit();
    }else{
      $error['login']='failed';
    }
  }else{
    $error['login']='blank';
  }
}
?>

<?php require_once('header.php');?>
    <!-- フォーム
      メールアドレス、パスワード
  -->
  <div class="main-input">
    <h2 class="main-input__title">ログイン</h2>
    <form action="" method="post">
      <dl>
      <?php if($error['login']==='blank'):?>
        <p class="error">* メールアドレスとパスワードを入力してください。</p>
      <?php endif;?>
      <?php if($error['login']==='failed'):?>
        <p class="error">* ログインに失敗しました。メールアドレスまたはパスワードが間違っています。</p>
      <?php endif;?>
        <dt class="main-input__headline">●メールアドレス</dt>
        <dd>
          <input type="text" name="email" id="" class="main-input__form" maxlength="255" value="<?php print(htmlspecialchars($email,ENT_QUOTES));?>">
          
        </dd>
  
        <dt class="main-input__headline">●パスワード</dt>
        <dd>
          <input type="password" name="password" id="" class="main-input__form" maxlength="100" value="<?php print(htmlspecialchars($_POST['password'],ENT_QUOTES));?>">
        </dd>
  
        <dt class="main-input__headline"></dt>
        <dd>
          <input type="checkbox" name="save" value="on">
          <label for="save">次回から自動的にログインする</label>
        </dd>
      </dl>
      <button class="button button--login">ログイン</button>
    </form>

    <a href="register.php">新規登録はこちら</a>
  </div>
  
</body>
</html>
