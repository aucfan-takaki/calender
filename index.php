<?php

//nヶ月後を表記
$n = 0;
//1ヶ月前、該当月、1か月後のカレンダー3つを作成
for ($target_number=$n -1; $target_number <= $n+1; $target_number++) :
//今月から何ヶ月ずれているか
$target_month = strtotime("+$target_number month");

//カレンダー作成

$this_year  = date(Y,$target_month);
$this_month = date(m,$target_month);
$this_day   = date(d,$target_month);

//今月から($target_month)ヶ月後の日数
$last_day = date(t,$target_month);
echo "日数：$last_day ";

//今月から($target_month)ヶ月後の1日の曜日を計算(0~6:日~土)
$start_day = (date(w,$target_month) - date(j,$target_month) +8 ) % 7;
echo "1日の曜日：$start_day";

//カレンダーの1マス目の日数
$count_day = 1 - $start_day;

//カレンダーの週数
$count_week = ceil(($last_day + $start_day)/7);

?>
<!DOCTYPE html>
<html>
<head>
	<?php //tableの設定 ?>
	<style>
	table {
		border-collapse: collapse;
	}
	td {
		border: solid 1px;
		padding: 0.5em;
	}
	</style>
	<title></title>
</head>
<body>
	<table>
	<tr><?php echo "$this_year" . '年' . "$this_month" . '月' ?></tr>
		<tr>
			<td>日</td>
			<td>月</td>
			<td>火</td>
			<td>水</td>
			<td>木</td>
			<td>金</td>
			<td>土</td>
		</tr>
		<?php
		// 週の数だけ繰り返す
		for($i=0; $i < $count_week; $i++) : ?>
		<tr>
			<?php for($j = 0 ; $j < 7 ; $j++ ) : ?>
				<td> <?php //1〜月の日数を表示
				if($count_day > 0 && $count_day <= $last_day) {
	 				echo $count_day; 
	 			} ?></td>
				<?php //土曜だったらループを抜ける
				if(($count_day + $start_day) % 7 == 0) {
					$count_day++;
					break;
				} ?>
			<?php $count_day++;
			endfor ?>
		</tr>
		<?php endfor ?>
	</table>
	<?php endfor ?>
</body>
</html>