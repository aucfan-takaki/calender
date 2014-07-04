<?php
/*

echo date("今日の日付：n月j日\n");

echo date("今日の曜日：");
if(date(w)==0) {
echo("日曜");
}
if(date(w)==1){
echo("月曜");
}
if(date(w)==2){
echo("火曜");
}
if(date(w)==3){
echo("水曜");
}
if(date(w)==4){
echo("木曜");
}
if(date(w)==5){
echo("金曜");
}
if(date(w)==6){
echo("土曜");
}

*/

//string date (string $format [, int $timestamp = time()]);


//キーワード
//Y/m/d

//今月のカレンダー作成		

$this_year  = date(Y);
$this_month = date(m);
$this_day   = date(d);

echo "$this_year".'/'."$this_month".'/'."$this_day";

echo $this_year;

$last_day = date(t);

//今月の1日の曜日を計算(0~6:日~土)
$start_day = (date(w) - date(j) +8 ) % 7;
echo "$start_day";

/*
$start_day = date('w', mktime(0, 0, 0, $month, 1, $year));
echo "$start_day";
*/
for ($i = 1; $i <= $last_day; $i++){
	var_dump($i);
}
exit;

//nヶ月後の設定
$n = 1 ;

//nヶ月後の日付
//var_dump($target_time);
$target_time = date('Y/m/d',strtotime("+$n month"));
var_dump($target_time);

//nヶ月後の日数
$last_day = date(t,strtotime("+$n month"));
var_dump($last_day);

//nヶ月後の1日の曜日
$first_day = (date(w,strtotime("+$n month")) - date(j,strtotime("+$n month"))+8)% 7;
var_dump($first_day);

