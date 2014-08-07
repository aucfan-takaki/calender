<?php

require_once dirname(__FILE__). '/calendar.php';

//年月を取得、nullの場合は今月の値
$this_year = $_GET["year"];
$this_month= $_GET["month"];
if (empty($this_year)) $this_year   = date(Y);
if (empty($this_month)) $this_month = date(m);

$target_time = mktime(0,0,0,$this_month,1,$this_year);


$cal = new Calendar($target_time);

//表示させるカレンダーの数を取得	、nullの場合は3
$show_month= $_GET["show"];
if (empty($show_month)) $show_month  = 3;
$pmonth = -floor($show_month/2);
if ($show_month % 2 == 1) {
	$nmonth = 	floor($show_month/2);
} else{
	$nmonth = floor($show_month/2) -1;
}

//nヶ月分のタイムスタンプ・年・月を配列化
$year_months = array();
for ($i = $pmonth; $i <= $nmonth; $i++) { 
	$year_months[$i] = array(
	'time' => $cal->jumptime($i, $target_time),
	'year' => $cal->jumpyear($i, $target_time),
	'month' => $cal->jumpmonth($i, $target_time),
	);
}

//何曜日から表示させるかを取得、nullの場合は0（日曜）
$start_days_of_the_week= $_GET["startweek"];
if (empty($start_days_of_the_week)) $start_days_of_the_week  = 0;

//曜日名を配列化
$daysname = array(
	(7  - $start_days_of_the_week) % 7 => '日', 
	(8  - $start_days_of_the_week) % 7 => '月',
	(9  - $start_days_of_the_week) % 7 => '火',
	(10 - $start_days_of_the_week) % 7 => '水',
	(11 - $start_days_of_the_week) % 7 => '木',
	(12 - $start_days_of_the_week) % 7 => '金',
	(13 - $start_days_of_the_week) % 7 => '土',
	);


//祝日を調べる範囲
$start_holiday  = date('Y-m-d',$cal->jumptime($pmonth, $target_time));
$finish_holiday = date('Y-m-d',$cal->jumptime($nmonth+1, $target_time));

//祝日を取得
$holiday = $cal->getHolidays($start_holiday, $finish_holiday);

$auctopic = $cal->getauc();

$schedule = $cal->getdb($target_time, $pmonth, $nmonth+1);

?>

<!DOCTYPE html>
<html>
<head>
	<style>
	table {
		display: inline-table;
		border-collapse: collapse;
		margin: 3px;
	}
	td {
		border: solid 1px;
		padding: 0.5em;
		border-color: #000000;
		width: 70px;
		height: 70px;
	}

	.today{background-color: #FFFFAD;}
	.sunday{background-color: #FFADAD;}
	.saturday{background-color: #AFFFFF;}
	.holiday{background-color: #FFADFF;}

	.gtoday{background-color: #FFFFAD; color: #C8C8C8;}
	.gsunday{background-color: #FFADAD; color: #C8C8C8;}
	.gsaturday{background-color: #AFFFFF; color: #C8C8C8;}
	.gholiday{background-color: #FFADFF; color: #C8C8C8;}
	.glay{color: #C8C8C8;}

	.left{margin:3px; float:left;}
	.frame{border: solid 1px;padding: 0.5em;}
	
	</style>
	<title></title>
</head>
<body>

<form method="GET" action="">
	<select name="year">
		<?php for ($k = $this_year - 5; $k < $this_year + 6; $k++) : ?>
			<option value=<?php echo $k;
			if ($k == $this_year) {
				echo " selected";
			} ?>><?php echo $k.'年' ?>
			</option>
		<?php endfor ?>
	</select>
	<select name="month">
		<?php for ($k = 1; $k <= 12; $k++) : ?>
			<option value=<?php echo $k;
			if ($k == $this_month) {
				echo " selected";
			} ?>><?php echo $k.'月' ?>
			</option>
		<?php endfor ?>
	</select>
		<select name="show">
		<?php for ($k = 1; $k <= 12; $k++) : ?>
			<option value=<?php echo $k;
			if ($k == $show_month) {
				echo " selected";
			} ?>><?php echo $k.'月分表示' ?>
			</option>
		<?php endfor ?>
	</select>
	<select name="startweek">
		<?php for ($k = 0; $k < 7; $k++) : ?>
			<option value=<?php echo $k;
			if ($k == $start_days_of_the_week) {
				echo " selected";
			} ?>>
			<?php echo $daysname[($k - $start_days_of_the_week +7) %7].'曜日から表示' ?>
			</option>
		<?php endfor ?>
	</select>
	<input type="submit" value="反映する">
</form>


<form method="GET" action="">
	<input type="hidden" value=<?php echo $cal->jumpyear(-1, $target_time) ?> name="year">
	<input type="hidden" value=<?php echo $cal->jumpmonth(-1, $target_time) ?> name="month">
	<input type="hidden" value=<?php echo $show_month ?> name="show">
	<input type="hidden" value=<?php echo $start_days_of_the_week ?> name="startweek">
	<input type="submit" value="＜＜" class="left">
</form>
<form method="GET" action="">
	<input type="hidden" value=<?php echo date("Y") ?> name="year">
	<input type="hidden" value=<?php echo date("m") ?> name="month">
	<input type="hidden" value=<?php echo $show_month ?> name="show">
	<input type="hidden" value=<?php echo $start_days_of_the_week ?> name="startweek">
	<input type="submit" value="今月" class="left">
</form>
<form method="GET" action="">
	<input type="hidden" value=<?php echo $cal->jumpyear(1, $target_time) ?> name="year">
	<input type="hidden" value=<?php echo $cal->jumpmonth(1, $target_time) ?> name="month">
	<input type="hidden" value=<?php echo $show_month ?> name="show">
	<input type="hidden" value=<?php echo $start_days_of_the_week ?> name="startweek">
	<input type="submit" value="＞＞" >
</form>

<?php foreach ($year_months as $key => $value) :?>
	<table>
		<tr>
			<td colspan="7" align="center">
				<div style="font-size: 30px">
					<?php echo $value['year'] . '年' . $value['month'] . '月' ?>
				</div>
			</td>
		</tr>
		<tr>
			<?php for ($i=0; $i<7; $i++) : ?>
				<td class="<?php echo $cal->startcolor($i, $start_days_of_the_week) ?>" >
					<div align="center">
						<?php echo $daysname["$i"] ?>
					</div>
				</td>
			<?php endfor ?>
		</tr>
		<?php for($i=0, $c=$cal->countday($value['time'], $start_days_of_the_week); $i<$cal->countweek($value['time'], $start_days_of_the_week); $i++) : ?>
			<tr>
				<?php for ($j=0; $j<7; $j++) : ?>
					<td class="<?php echo $cal->addcolor($value['time'], $value['year'], $value['month'], $c, $holiday) ?>" align="left" valign="top">
						<?php if($c > 0 && $c <= $cal->lastday($value['time'])) : ?>
							<div>
								<a href="<?php echo htmlspecialchars('http://kensyu.aucfan.com/schedule.php?year=' . $value['year'] . '&month=' . $value['month'] . '&day=' . $c) ?>">
									<?php echo $c ?>
								</a>
					 		</div>
					 	<?php else :?>
					 		<div>
								 <?php echo date(j,strtotime($c-1 . ' day', $value['time'])) ?>
					 		</div>
					 	<?php endif ?>
					 		<div style="font-size: 8px">
					 			<?php echo $holiday[$cal->addholiday($value['time'], $value['year'], $value['month'], $c, $holiday)];?>
					 		</div>
					 		<div style="font-size: 8px">
					 			<a href="<?php echo htmlspecialchars($auctopic[$cal->addauc($value['time'], $value['year'], $value['month'], $c, $auctopic)][0]['link']);?>">
		                			<?php echo htmlspecialchars($auctopic[$cal->addauc($value['time'], $value['year'], $value['month'], $c, $auctopic)][0]['title']);?>
		                		</a>
	                        </div>
	                        <?php if (! empty($schedule[date(Ymd, mktime(0,0,0,$value['month'],$c,$value['year']))])) foreach ($schedule[date(Ymd, mktime(0,0,0,$value['month'],$c,$value['year']))] as $schedule_array) : ?>
	                        	<?php if (!isset($schedule_array['deleted_at'])) : ?>
		                        	<div style="font-size: 8px">
			                        	<a href="<?php echo htmlspecialchars('http://kensyu.aucfan.com/schedule.php?year=' . $value['year'] . '&month=' . $value['month'] . '&day=' . $c . '&id=' .  $schedule_array['schedule_id'] ) ?>">
							 				<?php echo htmlspecialchars($schedule_array['title'])?>
						 				</a>
							 		</div>
						 		<?php endif ?>
						 	<?php endforeach ?>
				 		<?php $c++ ?>
					</td>
				<?php endfor ?>
			</tr>
		<?php endfor ?>
	</table>
<?php endforeach ?>
</body>
</html>