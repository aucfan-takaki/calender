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

//今月の日数
$last_day = date(t);

//今月の1日の曜日を計算(0~6:日~土)
$start_day = (date(w) - date(j) +8 ) % 7;

//カレンダーの1マス目の日数
$count_day = 1 - $start_day;

//カレンダーの週数
$count_week = ceil(($last_day + $count_day)/7);	

/*
$start_day = date('w', mktime(0, 0, 0, $month, 1, $year));
echo "$start_day";
*/

for ($i = 1; $i <= $last_day; $i++){
	var_dump($i);

	//土曜日の日付を表示したら改行する
	$saturday = (date(w) - date(j) +$i +35 ) % 7;
	if($saturday == 6){
		echo("\n");
	}
}

?>

<!-- tableの設定 -->
<style>
table {
	border-collapse: collapse;
}
td {
	border: solid 1px;
	padding: 0.5em;
}
</style>

<!-- カレンダーのテーブル -->
<table>
<tr><?php echo("$this_year".'年'."$this_month".'月') ?></tr>
	<tr>
		<td>日</td>
		<td>月</td>
		<td>火</td>
		<td>水</td>
		<td>木</td>
		<td>金</td>
		<td>土</td>
	</tr>
	<?php for($i=0; $i < $count_week; $i++) : ?>
	<tr>
		<?php for(; $count_day <= $last_day; $count_day++) : ?>
			<!--  -->
			<td> <?php if($count_day > 0){echo $count_day;} ?></td>
			<!-- 土曜だったらループを抜ける -->
			<?php if(($count_day +$start_day)% 7==0){
				$count_day++;
				break;
				}?>
		<?php endfor ?>
	</tr>
	<?php endfor ?>
</table>


<?php

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

