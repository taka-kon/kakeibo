<!-- 2か月前までの折れ線グラフを表示 -->
<script type="text/javascript">

// ▼グラフの中身
var lineChartData_3m = {

  //2か月前~今月
   labels : [
     <?php for($i=-2;$i<=0;$i++):?>
       
       "<?php 
       if($i==-2){
         echo date("y年n月",strtotime(" $i month"));
       }else{
         echo date("n月",strtotime(" $i month"));
       }
   ?>",
     <?php endfor;?>
       
   ],
   datasets : [
      {
       lineTension:0,
         label: "出費",
         fillColor : "rgba(92,220,92,0)", // 線から下端までを塗りつぶす色
         strokeColor : "rgb(0)", // 折れ線の色
         pointColor : "rgb(0)",  // ドットの塗りつぶし色
         pointStrokeColor : "black",        // ドットの枠線色
         pointHighlightFill : "black",     // マウスが載った際のドットの塗りつぶし色
         pointHighlightStroke : "black",    // マウスが載った際のドットの枠線色

         //日ごとの出費の合計を入れたい
         data : [ 
           <?php for($i=2;$i>=0;$i--):?>
             <?php 
           
             //SELECT * FROM expenses WHERE DATE_FORMAT(day, '%Y%m') = DATE_FORMAT(CURDATE() - INTERVAL 1 MONTH, '%Y%m')：指定月分の行を全て取得
             //"INTERVAL 1"の"1"を変更できる
             //0...今月　1...1か月前　2...2か月前 →$iを2,1,0の順にする
             $sql = "SELECT * FROM expenses WHERE DATE_FORMAT(day, '%Y%m') = DATE_FORMAT(CURDATE() - INTERVAL ? MONTH, '%Y%m') and user_id = ?";
             $graph_mon = $db->prepare($sql); 
             $graph_mon->execute(array(
               $i,
               $_SESSION['id'],
             ));
             $g=$graph_mon->fetchAll();
             $minus_sum=0;
             foreach($g as $g1){
               $minus_sum+=$g1['minus'];
             }
             echo $minus_sum.',';
             ?>
           <?php endfor;?>
           ],      // 各点の値
         
      },

     //  食費
      {
       lineTension:0,
         label: "食費",
         fillColor : "rgba(92,220,92,0)", // 線から下端までを塗りつぶす色
         strokeColor : "rgb(250, 107, 11)", // 折れ線の色
         pointColor : "rgb(40)",  // ドットの塗りつぶし色
         pointStrokeColor : "black",        // ドットの枠線色
         pointHighlightFill : "black",     // マウスが載った際のドットの塗りつぶし色
         pointHighlightStroke : "black",    // マウスが載った際のドットの枠線色

         //日ごとの出費の合計を入れたい
         data : [ 
           <?php for($i=2;$i>=0;$i--):?>
             <?php 
           
             //SELECT * FROM expenses WHERE DATE_FORMAT(day, '%Y%m') = DATE_FORMAT(CURDATE() - INTERVAL 1 MONTH, '%Y%m')：指定月分の行を全て取得
             //"INTERVAL 1"の"1"を変更できる
             //0...今月　1...1か月前　2...2か月前 →$iを2,1,0の順にする
             $sql = "SELECT * FROM expenses WHERE DATE_FORMAT(day, '%Y%m') = DATE_FORMAT(CURDATE() - INTERVAL ? MONTH, '%Y%m') and user_id = ? and genre=0";
             $graph_mon = $db->prepare($sql); 
             $graph_mon->execute(array(
               $i,
               $_SESSION['id'],
             ));
             $g=$graph_mon->fetchAll();
             $minus_sum=0;
             foreach($g as $g1){
               $minus_sum+=$g1['minus'];
             }
             echo $minus_sum.',';
             ?>
           <?php endfor;?>
           ],      // 各点の値
         
      },
      //日用品費
      {
       lineTension:0,
         label: "出費",
         fillColor : "rgba(92,220,92,0)", // 線から下端までを塗りつぶす色
         strokeColor : "rgb(4, 136, 4)", // 折れ線の色
         pointColor : "rgb(40)",  // ドットの塗りつぶし色
         pointStrokeColor : "black",        // ドットの枠線色
         pointHighlightFill : "black",     // マウスが載った際のドットの塗りつぶし色
         pointHighlightStroke : "black",    // マウスが載った際のドットの枠線色

         //日ごとの出費の合計を入れたい
         data : [ 
           <?php for($i=2;$i>=0;$i--):?>
             <?php 
           
             //SELECT * FROM expenses WHERE DATE_FORMAT(day, '%Y%m') = DATE_FORMAT(CURDATE() - INTERVAL 1 MONTH, '%Y%m')：指定月分の行を全て取得
             //"INTERVAL 1"の"1"を変更できる
             //0...今月　1...1か月前　2...2か月前 →$iを2,1,0の順にする
             $sql = "SELECT * FROM expenses WHERE DATE_FORMAT(day, '%Y%m') = DATE_FORMAT(CURDATE() - INTERVAL ? MONTH, '%Y%m') and user_id = ? and genre=1";
             $graph_mon = $db->prepare($sql); 
             $graph_mon->execute(array(
               $i,
               $_SESSION['id'],
             ));
             $g=$graph_mon->fetchAll();
             $minus_sum=0;
             foreach($g as $g1){
               $minus_sum+=$g1['minus'];
             }
             echo $minus_sum.',';
             ?>
           <?php endfor;?>
           ],      // 各点の値
         
      },
      //レジャー費
      {
       lineTension:0,
         label: "出費",
         fillColor : "rgba(92,220,92,0)", // 線から下端までを塗りつぶす色
         strokeColor : "rgb(255,0,0)", // 折れ線の色
         pointColor : "rgb(40)",  // ドットの塗りつぶし色
         pointStrokeColor : "black",        // ドットの枠線色
         pointHighlightFill : "black",     // マウスが載った際のドットの塗りつぶし色
         pointHighlightStroke : "black",    // マウスが載った際のドットの枠線色

         //日ごとの出費の合計を入れたい
         data : [ 
           <?php for($i=2;$i>=0;$i--):?>
             <?php 
           
             //SELECT * FROM expenses WHERE DATE_FORMAT(day, '%Y%m') = DATE_FORMAT(CURDATE() - INTERVAL 1 MONTH, '%Y%m')：指定月分の行を全て取得
             //"INTERVAL 1"の"1"を変更できる
             //0...今月　1...1か月前　2...2か月前 →$iを2,1,0の順にする
             $sql = "SELECT * FROM expenses WHERE DATE_FORMAT(day, '%Y%m') = DATE_FORMAT(CURDATE() - INTERVAL ? MONTH, '%Y%m') and user_id = ? and genre=2";
             $graph_mon = $db->prepare($sql); 
             $graph_mon->execute(array(
               $i,
               $_SESSION['id'],
             ));
             $g=$graph_mon->fetchAll();
             $minus_sum=0;
             foreach($g as $g1){
               $minus_sum+=$g1['minus'];
             }
             echo $minus_sum.',';
             ?>
           <?php endfor;?>
           ],      // 各点の値
         
      },
      //交通費
      {
       lineTension:0,
         label: "出費",
         fillColor : "rgba(92,220,92,0)", // 線から下端までを塗りつぶす色
         strokeColor : "rgb(5, 2, 197)", // 折れ線の色
         pointColor : "rgb(40)",  // ドットの塗りつぶし色
         pointStrokeColor : "black",        // ドットの枠線色
         pointHighlightFill : "black",     // マウスが載った際のドットの塗りつぶし色
         pointHighlightStroke : "black",    // マウスが載った際のドットの枠線色

         //日ごとの出費の合計を入れたい
         data : [ 
           <?php for($i=2;$i>=0;$i--):?>
             <?php 
           
             //SELECT * FROM expenses WHERE DATE_FORMAT(day, '%Y%m') = DATE_FORMAT(CURDATE() - INTERVAL 1 MONTH, '%Y%m')：指定月分の行を全て取得
             //"INTERVAL 1"の"1"を変更できる
             //0...今月　1...1か月前　2...2か月前 →$iを2,1,0の順にする
             $sql = "SELECT * FROM expenses WHERE DATE_FORMAT(day, '%Y%m') = DATE_FORMAT(CURDATE() - INTERVAL ? MONTH, '%Y%m') and user_id = ? and genre=3";
             $graph_mon = $db->prepare($sql); 
             $graph_mon->execute(array(
               $i,
               $_SESSION['id'],
             ));
             $g=$graph_mon->fetchAll();
             $minus_sum=0;
             foreach($g as $g1){
               $minus_sum+=$g1['minus'];
             }
             echo $minus_sum.',';
             ?>
           <?php endfor;?>
           ],      // 各点の値
         
      },
      //固定費
      {
       lineTension:0,
         label: "出費",
         fillColor : "rgba(92,220,92,0)", // 線から下端までを塗りつぶす色
         strokeColor : "rgb(223, 0, 204)", // 折れ線の色
         pointColor : "rgb(40)",  // ドットの塗りつぶし色
         pointStrokeColor : "black",        // ドットの枠線色
         pointHighlightFill : "black",     // マウスが載った際のドットの塗りつぶし色
         pointHighlightStroke : "black",    // マウスが載った際のドットの枠線色

         //日ごとの出費の合計を入れたい
         data : [ 
           <?php for($i=2;$i>=0;$i--):?>
             <?php 
           
             //SELECT * FROM expenses WHERE DATE_FORMAT(day, '%Y%m') = DATE_FORMAT(CURDATE() - INTERVAL 1 MONTH, '%Y%m')：指定月分の行を全て取得
             //"INTERVAL 1"の"1"を変更できる
             //0...今月　1...1か月前　2...2か月前 →$iを2,1,0の順にする
             $sql = "SELECT * FROM expenses WHERE DATE_FORMAT(day, '%Y%m') = DATE_FORMAT(CURDATE() - INTERVAL ? MONTH, '%Y%m') and user_id = ? and genre=4";
             $graph_mon = $db->prepare($sql); 
             $graph_mon->execute(array(
               $i,
               $_SESSION['id'],
             ));
             $g=$graph_mon->fetchAll();
             $minus_sum=0;
             foreach($g as $g1){
               $minus_sum+=$g1['minus'];
             }
             echo $minus_sum.',';
             ?>
           <?php endfor;?>
           ],      // 各点の値
         
      },
      //その他
      {
       lineTension:0,
         label: "出費",
         fillColor : "rgba(92,220,92,0)", // 線から下端までを塗りつぶす色
         strokeColor : "rgb(109, 109, 109)", // 折れ線の色
         pointColor : "rgb(40)",  // ドットの塗りつぶし色
         pointStrokeColor : "black",        // ドットの枠線色
         pointHighlightFill : "black",     // マウスが載った際のドットの塗りつぶし色
         pointHighlightStroke : "black",    // マウスが載った際のドットの枠線色

         //日ごとの出費の合計を入れたい
         data : [ 
           <?php for($i=2;$i>=0;$i--):?>
             <?php 
           
             //SELECT * FROM expenses WHERE DATE_FORMAT(day, '%Y%m') = DATE_FORMAT(CURDATE() - INTERVAL 1 MONTH, '%Y%m')：指定月分の行を全て取得
             //"INTERVAL 1"の"1"を変更できる
             //0...今月　1...1か月前　2...2か月前 →$iを2,1,0の順にする
             $sql = "SELECT * FROM expenses WHERE DATE_FORMAT(day, '%Y%m') = DATE_FORMAT(CURDATE() - INTERVAL ? MONTH, '%Y%m') and user_id = ? and genre=5";
             $graph_mon = $db->prepare($sql); 
             $graph_mon->execute(array(
               $i,
               $_SESSION['id'],
             ));
             $g=$graph_mon->fetchAll();
             $minus_sum=0;
             foreach($g as $g1){
               $minus_sum+=$g1['minus'];
             }
             echo $minus_sum.',';
             ?>
           <?php endfor;?>
           ],      // 各点の値
         
      },
      
   ]

}



// ▼上記のグラフを描画するための記述
//onload...文書や画像など全てのリソースを読み込んでから処理を実行する
//  window.onload = function(){
//     var ctx = document.getElementById("graph-area").getContext("2d");
//     window.myLine = new Chart(ctx).Line(lineChartData,{
//       responsive : true
//     });
//  }

window.addEventListener('load', function(){
 var ctx = document.getElementById("graph-area-3m").getContext("2d");
 ctx.canvas.height = 300;
   window.myLine = new Chart(ctx).Line(lineChartData_3m,{
     responsive : true
   });
});
</script>