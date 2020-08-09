<?php
session_start();//セッションスタート
require_once('dbconnect.php');

if(!empty($_POST)){
  if($_POST['name']==''){
    $error['name']='blank';
  }
  if($_POST['email']==''){
    $error['email']='blank';
  }
  //パスワードが6文字より少ないときにエラー
  if(strlen($_POST['password'])<6 &&  strlen($_POST['password'])>0){
    $error['password']='length';
  }
  if($_POST['password']==''){
    $error['password']='blank';
  }
  if(!isset($_POST['sex'])){
    $error['sex']='blank';
  }
  if($_POST['year']=='0' || $_POST['month']=='0' || $_POST['day']=='0' ){
    $error['birthday']='blank';
  }
  $fileName=$_FILES['image']['name'];
  if(!empty($fileName)){
    $ext = substr($fileName,-3);
    if($ext != 'jpg' && $ext != 'gif' && $ext != 'png'){
      $error['image'] = 'type';
    }
  }
  
  if(empty($error)){
    //アカウント重複チェック
    $user = $db->prepare('SELECT COUNT(*) AS cnt FROM users WHERE email=?');
    $user->execute(array($_POST['email'])); 
    $record = $user->fetch();   //入力したメールアドレスがDBに既にあったら'1',無かったら'0'

    if($record['cnt']>0){
      $error['email']='duplicate';
    }
  }

  // move_uploaded_file(../other/mem_pic/20200730221205wallpaper-seal-photo-09.jpg): failed to open stream: No such file or directory in C:\xampp\htdocs\kakeibo\register.php on line 41

  //↑failed to open stream: No such file or directory =そのようなファイルやディレクトリはない
 
  //解決策・・・パスが違った。"../other/mem_pic/"→"./other/mem_pic/"
   //「../」 一段階外に出る　「./」現在のディレクトリ

  
  
  // Warning: move_uploaded_file(): Unable to move 'C:\xampp\tmp\php7272.tmp' to '/other/mem_pic/20200730221205wallpaper-seal-photo-09.jpg' in C:\xampp\htdocs\kakeibo\register.php on line 41

  //↑書き込み権限に問題あり
  
  if(empty($error)){
    //画像が存在しない
    if($_FILES['image']['name']==null){
      $file=''.$_FILES['image']['name'];
    }else{
      //ファイル名の直前に'-'を加える
      $file='-'.$_FILES['image']['name'];
    }
    //違う人が同じファイル名のデータを送信したとき同じ秒でない限り重複しない
    $image=date('YmdHis') . $file;

    

    //ディレクトリトラバーサル攻撃対策でbasename()関数を使う
    // $save = '../other/mem_pic/' . basename($image);
    // 第1引数...今ある場所 第2引数...移動先
    move_uploaded_file($_FILES['image']['tmp_name'],'./other/mem_pic/' . $image);
    $_SESSION['join']=$_POST;
    //DBに入れるため画像もセッションに保管
    $_SESSION['join']['image']=$image;
    var_dump($_FILES['image']['name']==null);
    header('Location: check.php');
    exit();
  }
}
//書き直し時のデータの保持
if($_REQUEST['action']=='rewrite' && isset($_SESSION['join']))
$_POST=$_SESSION['join'];
?>

<?php require_once('header.php');?>

  <div class="main-input">
    <h2 class="main-input__title">新規登録</h2>
    

  <form action="" method="post" class="form" enctype="multipart/form-data">
    <dl>
      <dt class="main-input__headline">●ニックネーム</dt>
      <dd class="main-input__tag-dd">
        <input type="text" name="name" id="" class="main-input__form" maxlength="255" value="<?php print(htmlspecialchars($_POST['name']));?>">
        <?php if($error['name']==='blank'):?>
         <p class="error">* ニックネームが入力されていません。</p>
        <?php endif;?>
      </dd>

      <dt class="main-input__headline">●メールアドレス</dt>
      <dd class="main-input__tag-dd">
        <input type="text" name="email" id="" class="main-input__form" maxlength="255" value="<?php print(htmlspecialchars($_POST['email']));?>">
        <?php if($error['email']==='blank'):?>
         <p class="error">* メールアドレスが入力されていません。</p>
        <?php endif;?>
        <?php if($error['email']==='duplicate'):?>
         <p class="error">* 指定したメールアドレスは、既に登録されています。</p>
        <?php endif;?>
      </dd>

      <dt class="main-input__headline">●パスワード</dt>
      <dd class="main-input__tag-dd">
        <input type="password" name="password" id="" class="main-input__form" maxlength="100" value="<?php print(htmlspecialchars($_POST['password']));?>">
        <?php if($error['password']==='blank'):?>
         <p class="error">* パスワードが入力されていません。</p>
        <?php endif;?>
        <?php if($error['password']==='length'):?>
         <p class="error">* 6文字以上で入力してください。</p>
        <?php endif;?>
      </dd>

      <dt class="main-input__headline">●性別</dt>
      <dd class="main-input__tag-dd">
        <?php
        if(isset($_POST['sex'])){
          $sex=$_POST['sex'];
        }
        ?>
        <input type="radio" name="sex" id="" value="1" <?php if($sex=="1"){echo 'readonly checked';}else{echo '';} ?>>男性
        <input type="radio" name="sex" id="" value="2" <?php if($sex=="2"){echo 'readonly checked';}else{echo '';}  ?>>女性
        <input type="radio" name="sex" id="" value="3" <?php if($sex=="3"){echo 'readonly checked';}else{echo '';}  ?>>その他
        <?php if($error['sex']==='blank'):?>
         <p class="error">* 選択してください。</p>
        <?php endif;?>
      </dd>

      <dt class="main-input__headline">生年月日</dt>
      <dd class="main-input__tag-dd">
        <?php

        //送信されていたら、$yに選んだ値を格納
        if(isset($_POST['year'])){
          $y=$_POST['year'];
        }
        if(isset($_POST['month'])){
          $m=$_POST['month'];
        }
        if(isset($_POST['day'])){
          $d=$_POST['day'];
        }
       

        $year.= '<option value="0">-----年</option>';
        for ($i=1960; $i <= date("Y"); $i++) {
          //選択した値を格納した変数$yがoptionタグの内、同じ値のoptionのみ"selected"を入れる
          if($y==$i){
            $y_select="selected";
          }else{
            $y_select="";
          }
          $year .= '<option value="'.$i.'"'.$y_select.'>'.$i.'年</option>';
      }
      
      $month.= '<option value="0">--月</option>';
      for ($i=1; $i <= 12; $i++) {

         if($m==$i){
          $m_select="selected";
        }else{
          $m_select="";
        }
       

          $month .= '<option value="'.$i.'"'.$m_select.'>'.$i.'月</option>';
      }
      
      $day.= '<option value="0">--日</option>';
      for ($i=1; $i <= 31; $i++) {
         if($d==$i){
          $d_select="selected";
        }else{
          $d_select="";
        }
          $day .= '<option value="'.$i.'"'.$d_select.'>'.$i.'日</option>';
      }
      
      echo '
      <select name="year" class="main-input__select">'.$year.'</select>
      <select name="month" class="main-input__select">'.$month.'</select>
      <select name="day" class="main-input__select">'.$day.'</select>
      ';
        ?>

        <?php if($error['birthday']==='blank'):?>
         <p class="error">* 生年月日を入力してください。</p>
        <?php endif;?>
      </dd>
      <dt>●アイコン画像</dt>
      <dd>
        <input type="file" name="image" value="test" />
        <?php if($error['image']==='type'):?>
         <p class="error">* gif,jpg,pngの画像を指定してください。</p>
        <?php endif;?>
        <?php if(!empty($error)):?>
         <p class="error">* 恐れ入りますが、画像を改めて指定してください。</p>
        <?php endif;?>
      </dd>

    </dl>
    <button class="button button--register">確認画面へ</button>
  </form>

  <a href="login.php">ログインページへ</a>
  </div>
</body>
</html>