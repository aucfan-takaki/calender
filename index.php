<?php
/*

echo date("今日の日付：n月j日\n");

echo date("今日の曜日：");
if(date(w)==0){
	echo("日曜");
}else if(date(w)==1){
	echo("月曜");
}else if(date(w)==2){
	echo("火曜");
}else if(date(w)==3){
	echo("水曜");
}else if(date(w)==4){
	echo("木曜");
}else if(date(w)==5){
	echo("金曜");
}else if(date(w)==6){
	echo("土曜");
}else{
	echo("曜日エラー");
}

*/

//string date (string $format [, int $timestamp = time()]);

$timestamp = time();


//キーワード
//Y/m/d

$month = date(n,$timestamp);

echo"$month ";

$month += 1;

echo"$month ";

$timestamp += 200000000;

$month = date(n,$timestamp);

echo"$month ";



$start_day = (date(w) - date(j) +8 ) % 7;
//今月の1日の曜日を計算

echo"$start_day ";



echo $timestamp = date("Y年m月d日",strtotime("+7 month"));


echo $lastday = date( t );

echo date( t ,$timestamp);

$n = 7;

echo date( t ,strtotime("+$n month"));

