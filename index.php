<?php
echo date("今日：n月j日<br/>");
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

