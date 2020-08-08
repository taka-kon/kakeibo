<?php

session_start();
require_once('dbconnect.php');
ini_set('display_errors', "Off"); //warning文の非表示

//1時間何もしない状態でいると自動的にログアウト
if(isset($_SESSION['id']) && $_SESSION['time']+3600>time()){
  $_SESSION['time']=time();
  $users=$db->prepare('SELECT * FROM users WHERE id=?');
  $users->execute(array($_SESSION['id']));
  $user = $users->fetch();

  // var_dump($user['id']);


}else{
  header('Location:login.php');
  exit();
}

$year=htmlspecialchars($_POST['year'],ENT_QUOTES);
$month=htmlspecialchars($_POST['month'],ENT_QUOTES);
$day=htmlspecialchars($_POST['day'],ENT_QUOTES);

//空の時のエラー表示
if(!empty($_POST)){
  
  if($_POST['genre']==100){
    $error['genre']='blank';
    $error['all']='blank';
  }
  //出費と追加残高の両方が未入力の場合
  if(empty($_POST['minus'])){
    $error['expense']='blank';
    $error['all']='blank';
  }
  

  //日付の文字列を作成
  $expense_day_array = array($year,$month,$day) ;  //year,month,dayの配列を作る
  $expense_day = implode("-",$expense_day_array);   //3要素を'-'で区切った文字列にする

  //備考の文字列を','で区切ったものに変えて格納
  $remark=str_replace('、', ',', $_POST['remark']);


}

//今月の出費合計
$sql = "SELECT * FROM expenses WHERE DATE_FORMAT(day,'%Y%m')=DATE_FORMAT(NOW(),'%Y%m') and user_id=?";
$now_mon= $db->prepare($sql);
$now_mon->execute(array(
  $_SESSION['id'],
));
$now_month=$now_mon->fetchAll();
$nowmon_sum = 0;
foreach($now_month as $nm){
  $nowmon_sum+=$nm['minus'];
}

  
//エラーが無い場合
if(empty($error)){

 

  if(!empty($_POST['plus'] ) || !empty($_POST['minus'] )){
    $statement = $db->prepare('INSERT INTO expenses (user_id,day,genre,minus) value(?,?,?,?)');
  
    $statement->execute(array(
      $user['id'],
      $expense_day,
      $_POST['genre'],
      $_POST['minus'],
    ));
    

    //送信後の画面から再び再読み込みすると、重複データがテーブルに入ってしまう。これを防ぐ。
    header('Location:index.php');
    exit();
  }

}

//メッセージを表示するためのDBからの取得
$sql='SELECT u.name,e.* from expenses e,users u where u.id=e.user_id and e.user_id=?  order by e.modified desc ';
$posts_get = $db->prepare($sql);
$posts_get->execute(array(
  $_SESSION['id'],
));
$posts=$posts_get->fetchAll();

?>

<?php require_once('header.php');?>
  <div class="main-index">
  <?php
  if($error['all']==='blank'):?>
  <p class="error">* 未入力があります。</p>
  <?php endif;?>

  <!-- $user['icon']に'-'が含まれているか
  '-'が含まれているとき・・・アイコン画像を登録
  含まれていない・・・画像をアップしていない
-->
  <?php if(strpos($user['icon'],'-')===false):?>
  
  <img class="main-index__icon" src="./other/mem_pic/<?php echo 'noImage.png'?>" alt="">
  <?php else:?>
    <img class="main-index__icon" src="./other/mem_pic/<?php echo $user['icon'];?>" alt="">
  <?php endif;?>

    <h2 class="main-index__top-text1"><span class="main-index__name"><?php print(htmlspecialchars($user['name'],ENT_QUOTES)); ?></span></h2>
    <div class="main-index__top-expense">
      <h2 class="main-index__top-text1">今月の出費額 </h2>
      <p class="main-index__top-text2"><span class="main-index__price" id = "price"><?php print($nowmon_sum); ?></span>円</p>

    </div>



    <form action="" class="main-index__form" method="post">

      <p class="main-index__form-label">日付</p>
      <?php
  
      //現在の年にselectedをつける
      $y=date("Y");
      $m=date("n");
      $d=date("j");
  
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
      
      for ($i=1960; $i <= $y; $i++) {
        //選択した値を格納した変数$yがoptionタグの内、同じ値のoptionのみ"selected"を入れる
        if($y==$i){
          $y_select="selected";
        }else{
          $y_select="";
        }
        $year .= '<option value="'.$i.'"'.$y_select.'>'.$i.'年</option>';
      }
  
     
      for ($i=1; $i <= 12; $i++) {
  
      if($m==$i){
        $m_select="selected";
      }else{
        $m_select="";
      }
  
  
        $month .= '<option value="'.$i.'"'.$m_select.'>'.$i.'月</option>';
      }
  
      for ($i=1; $i <= 31; $i++) {
      if($d==$i){
        $d_select="selected";
      }else{
        $d_select="";
      }
        $day .= '<option value="'.$i.'"'.$d_select.'>'.$i.'日</option>';
      }
  
      echo '
      <select name="year" class="main-index__select">'.$year.'</select>
      <select name="month" class="main-index__select">'.$month.'</select>
      <select name="day" class="main-index__select">'.$day.'</select>
      ';
      ?>
      <p class="main-index__form-label">ジャンル</p>
      <?php
       if(isset($_POST['genre'])){
        $genre_post=$_POST['genre'];
      }
        ?>
      <select name="genre" class="main-index__select">
      <option value="100" >未選択</option>
      
        <?php $genres=array("食費","日用品費","レジャー費","交通費","固定費","その他");
        foreach($genres as $genre=>$val):
          //if($genre_post===$genre)の"==="を"=="とした
          //$genre_post_selectに値を入れるif文をgenreがpostされているかのif文で囲った
          if(isset($_POST['genre'])){
            if($genre_post==$genre){
              
              $genre_post_select="selected";
            }else{
              $genre_post_select="";
            }
  
          }
          ?>
          <option value="<?php echo $genre; ?>" <?php print($genre_post_select); ?>><?php echo $val;?></option>
          
        <?php endforeach; ?>
  
      </select>
      <?php if($error['genre']==='blank'):?>
           <p class="error">* ジャンルが選択されていません。</p>
          <?php endif;?>
      <p class="main-index__form-label">出費</p>
      <input type="number" min="0" name="minus" value="<?php print(htmlspecialchars($_POST['minus']));?>" class="main-index__text">


      <?php if($error['expense']==='blank'):?>
           <p class="error">* 出費を入力してください。</p>
          <?php endif;?>
          
      
      <br>
      <div class="main-index__button-post">
        <button class="button button--post">追加する</button>
      </div>
    </form>
    <!-- グラフを表示 -->
    <div class="main-index__graphs">
      
    
      <h2 class="main-index__top-text1">3か月</h2>
      <canvas id="graph-area-3m" class="main-index__graph"></canvas> 
      <div class="main-index__g-item">
        <div class="g-item">
          <ul class="g-item__parts">
          <li class="g-item__part"><div class="g-item__box g-item__box--all"></div>合計</li>
            <li class="g-item__part"><div class="g-item__box g-item__box--0"></div>食費</li>
            <li class="g-item__part"><div class="g-item__box g-item__box--1"></div>日用品費</li>
            <li class="g-item__part"><div class="g-item__box g-item__box--2"></div>レジャー費</li>
            <li class="g-item__part"><div class="g-item__box g-item__box--3"></div>交通費</li>
            <li class="g-item__part"><div class="g-item__box g-item__box--4"></div>固定費</li>
            <li class="g-item__part"><div class="g-item__box g-item__box--5"></div>その他</li>
          </ul>
        </div>
      </div>
      <h2 class="main-index__top-text1">6か月</h2>
      <canvas id="graph-area-6m" class="main-index__graph"></canvas> 
      <div class="main-index__g-item">
        <div class="g-item">
          <ul class="g-item__parts">
            <li class="g-item__part"><div class="g-item__box g-item__box--all"></div>合計</li>
            <li class="g-item__part"><div class="g-item__box g-item__box--0"></div>食費</li>
            <li class="g-item__part"><div class="g-item__box g-item__box--1"></div>日用品費</li>
            <li class="g-item__part"><div class="g-item__box g-item__box--2"></div>レジャー費</li>
            <li class="g-item__part"><div class="g-item__box g-item__box--3"></div>交通費</li>
            <li class="g-item__part"><div class="g-item__box g-item__box--4"></div>固定費</li>
            <li class="g-item__part"><div class="g-item__box g-item__box--5"></div>その他</li>
          </ul>
        </div>
      </div>


    </div>
  
    <!-- 以下、JSONを含むscriptタグ-->
    <!-- 3か月間の出費グラフ -->
    <?php require_once('line_graph-3m.php');?> 
    <!-- 6か月間の出費グラフ -->
    <?php require_once('line_graph-6m.php');?> 
    
    <!-- 以下、更新情報 -->
    <div class="main-index__posts">
    
    <!-- 出費を入力した場合と残高を増やした場合を考える-->
    <?php foreach($posts as $post):?>

        <?php
      $day=htmlspecialchars($post['day'],ENT_QUOTES);
      $genre_num=htmlspecialchars($post['genre'],ENT_QUOTES);
      switch($genre_num){
        case 0:
          $genre="食費";
          break;
        case 1:
          $genre="日用品費";
          break;
        case 2:
          $genre="レジャー費";
          break;
        case 3:
          $genre="交通費";
          break;
        case 4:
          $genre="固定費";
          break;
        default:
          $genre="その他";
          break;
      }
      $modified=htmlspecialchars($post['modified'],ENT_QUOTES);
      $name=htmlspecialchars($post['name'],ENT_QUOTES);
      $minus=htmlspecialchars(number_format($post['minus']),ENT_QUOTES);
      $id=htmlspecialchars($post['id'],ENT_QUOTES);
    
      ?>
    
        <p><?php print($modified); ?>更新</p>
        <p><?php print($name);?>は、
    
        <?php if(!empty($minus)): ?>
          <b class="main-index__italic"><?php echo date('Y年n月d日',strtotime($day));?></b>に<b><?php print($minus);?>円</b>を<b><?php echo $genre;?></b>で使いました。
    
        <?php endif; ?>
    
        <div class="main-index__button-delete">
          <a href="delete.php?id=<?php print($id);?>" class="button button--delete">取消</a>

        </div>
        <hr>
  
    <?php endforeach;?>
      
    </div>
  
    
  </div>
  <script type="text/javascript" src="./other/js/script.js">
</script>
</body>
</html>