var price = document.getElementById("price");
// 出費額を格納
const max = parseInt(price.innerHTML);
// カウントの初期値（出費額-40）
count = parseInt(price.innerHTML)-160;
/*
max=41...count=2...2,3,4...41(40個)
max=40...count=1...1,2,3...40(40個)
max=39...count=0...0,1,2...39(40個)
max=38...count=-1...(×-1),0,1,2...38(39個)
max=37...count=-2...(×-2).(×-1),0,1,2...37(38個)
max=0...count=-39...(×-39),(×-38),...,(×-1),0(1個)
*/
// countupクロージャ countに1足しprice.innerHTMLに代入
countup=function(){
	count++;
	//この辺でif文で条件分岐する
	/*
		countが負数の時、price...は0にする
	*/ 
	if(count>=0){
		price.innerHTML=count;

	}
}

var id = setInterval(function(){
	countup();
	if(count>max-1){
		clearInterval(id);
	}
},1);





