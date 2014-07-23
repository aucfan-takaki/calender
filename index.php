<?php

/**
     * 「$countヶ月後の1日のタイムスタンプ」を出力する
     *
     * @access public
     * @param integer $count 第一引数
     * @return integer
     * @author Shota Takaki
     */
function jumptime($count, $target_time = null)
{
	if (is_null($target_time)) {
		$target_time = strtotime("now");
	}
	return strtotime($count . ' month', $target_time);
}

/**
     * 「$countヶ月後の年の値」を出力する
     *
     * @access public
     * @param integer $count 第一引数
     * @return integer
     * @author Shota Takaki
     */
function jumpyear($count, $target_time)
{
	return date("Y", jumptime($count, $target_time));
}

/**
     * 「$countヶ月後の月の値」を出力する
     *
     * @access public
     * @param integer $count 第一引数
     * @return integer
     * @author Shota Takaki
     */
function jumpmonth($count, $target_time)
{
	return date("n", jumptime($count, $target_time));
}

/**
     * 「参照月から$targetヶ月後の日数」を出力する
     *
     * @access public
     * @param integer $target 第一引数
     * @return integer
     * @author Shota Takaki
     */
function lastday($target)
{
	return date(t, $target);
}

/**
     * 「参照月から$targetヶ月後の1日の曜日の値(0~6:日~土)」を出力する
     *
     * @access public
     * @param integer $target 第一引数
     * @return integer
     * @author Shota Takaki
     */
function startday($target)
{
	return date(w,$target);
}

/**
     * 「カレンダーの1マス目の日数」を出力する
     *
     * @access public
     * @param integer $target 第一引数
     * @return integer
     * @author Shota Takaki
     */
function countday($target, $start_days_of_the_week)
{
	return 1 - (startday($target) - $start_days_of_the_week + 7) % 7;
}

/**
     * 「カレンダーの週数」を出力する
     *
     * @access public
     * @param integer $target 第一引数
     * @return integer
     * @author Shota Takaki
     */
function countweek($target, $start_days_of_the_week)
{
	return ceil((lastday($target) - countday($target, $start_days_of_the_week) + 1 ) / 7);
}

/**
     * 「カレンダーの色付け（今日、土、日、祝日）のためのclassの文字列」を出力する
     *
     * @access public
     * @param integer $time 第一引数
     * @param integer $year 第二引数
     * @param integer $month 第三引数
     * @param integer $day 第四引数
     * @return string
     * @author Shota Takaki
     */
function addcolor($time, $year, $month, $day, $holiday)
{
	$this_day = strtotime($day-1 . ' day', $time);
	if ($day > 0 && $day <= lastday($time)){
		if (date("Y/m/d",$this_day) == date("Y/m/d")) {
			return "today ";
		}
		if (date(w,$this_day) == 0) {
			return "sunday ";
		}
		if (date(w,$this_day) == 6) {
			return "saturday ";
		}
		if ($holiday[date("Ymd",$this_day)] !== null){
			return "holiday";
		}
	}else{
		if (date("Y/m/d",$this_day) == date("Y/m/d")) {
			return "gtoday ";
		}elseif (date(w,$this_day) == 0) {
			return "gsunday ";
		}elseif (date(w,$this_day) == 6) {
			return "gsaturday ";
		}elseif ($holiday[date("Ymd",$this_day)] !== null){
			return "gholiday";
		}else{
			return "glay";
		}
	}
}


/**
     * 「曜日の色付け（日本語用）のためのclassの文字列」を出力する
     *
     * @access public
     * @param integer $day 第一引数
     * @return string
     * @author Shota Takaki
     */
function startcolor($day, $start_days_of_the_week)
{
	$judge = ($day + $start_days_of_the_week) % 7;
	if ($judge == 0) {
		return "sunday ";
	}
	if ($judge == 6) {
		return "saturday ";
	}
}

/**
     * 「祝日」を取得する（ネット参照）
     *
     * @access public
     * @param integer $count 第一引数
     * @return integer
     * @author Shota Takaki
     */
function getHolidays($start, $finish) {
	$holidays = array();
 
	//Googleカレンダーから、指定年の祝日情報をJSON形式で取得するためのURL
	$url = sprintf(
		'http://www.google.com/calendar/feeds/%s/public/full?alt=json&%s&%s',
		'outid3el0qkcrsuf89fltf7a4qbacgt9@import.calendar.google.com',
		'start-min=' . $start,
		'start-max=' . $finish
	);

	//JSON形式で取得した情報を配列に変換
	$results = json_decode(file_get_contents($url), true);

 	//年月日（例：20120512）をキーに、祝日名を配列に格納
	foreach ($results['feed']['entry'] as $value) {
		$date = str_replace('-', '', $value['gd$when'][0]['startTime']);
		$title = $value['title']['$t'];
		$word = explode( ' / ', $title);
		$holidays[$date] = $word[0];
	}
 
	//祝日の配列を早い順に並び替え
	ksort($holidays);
 
	//配列として祝日を返す
	return $holidays;
}

/**
     *祝日の場合、その日の値を「date("Ymd"）」で出力する
     *
     * @access public
     * @param integer $time 第一引数
     * @param integer $year 第二引数
     * @param integer $month 第三引数
     * @param integer $day 第四引数
     * @return integer
     * @author Shota Takaki
     */
function addholiday($time, $year, $month, $day, $holiday)
{
	$this_day = strtotime($day-1 . ' day', $time);
	if ($holiday[date("Ymd",$this_day)] !== null){
		return date("Ymd",$this_day);
	}
}

//年月を取得、nullの場合は今月の値
$this_year = $_GET["year"];
$this_month= $_GET["month"];
if (empty($this_year)) $this_year   = date(Y);
if (empty($this_month)) $this_month = date(n);

$target_time = mktime(0,0,0,$this_month,1,$this_year);

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
	'time' => jumptime($i, $target_time),
	'year' => jumpyear($i, $target_time),
	'month' => jumpmonth($i, $target_time),
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
$start_holiday  = date('Y-m-d',jumptime($pmonth, $target_time));
$finish_holiday = date('Y-m-d',jumptime($nmonth+1, $target_time));

//祝日を取得
$holiday = getHolidays($start_holiday, $finish_holiday);

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
	<input type="hidden" value=<?php echo jumpyear(-1, $target_time) ?> name="year">
	<input type="hidden" value=<?php echo jumpmonth(-1, $target_time) ?> name="month">
	<input type="hidden" value=<?php echo $show_month ?> name="show">
	<input type="hidden" value=<?php echo $start_days_of_the_week ?> name="startweek">
	<input type="submit" value="＜＜" class="left">
</form>
<form method="GET" action="">
	<input type="hidden" value=<?php echo date("Y") ?> name="year">
	<input type="hidden" value=<?php echo date("n") ?> name="month">
	<input type="hidden" value=<?php echo $show_month ?> name="show">
	<input type="hidden" value=<?php echo $start_days_of_the_week ?> name="startweek">
	<input type="submit" value="今月" class="left">
</form>
<form method="GET" action="">
	<input type="hidden" value=<?php echo jumpyear(1, $target_time) ?> name="year">
	<input type="hidden" value=<?php echo jumpmonth(1, $target_time) ?> name="month">
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
				<td class="<?php echo startcolor($i, $start_days_of_the_week) ?>" >
					<div align="center">
						<?php echo $daysname["$i"] ?>
					</div>
				</td>
			<?php endfor ?>
		</tr>
		<?php for($i=0, $c=countday($value['time'], $start_days_of_the_week); $i<countweek($value['time'], $start_days_of_the_week); $i++) : ?>
			<tr>
				<?php for ($j=0; $j<7; $j++) : ?>
					<td class="<?php echo addcolor($value['time'], $value['year'], $value['month'], $c, $holiday) ?>" align="left" valign="top">
						<?php if($c > 0 && $c <= lastday($value['time'])) : ?>
							<div>
								 <?php echo $c ?>
					 		</div>
					 	<?php else :?>
					 		<div>
								 <?php echo date(j,strtotime($c-1 . ' day', $value['time'])) ?>
					 		</div>
					 	<?php endif ?>
					 		<div style="font-size: 8px">
					 			<?php echo $holiday[addholiday($value['time'], $value['year'], $value['month'], $c, $holiday)];?>
					 		</div>
				 		<?php $c++ ?>
					</td>
				<?php endfor ?>
			</tr>
		<?php endfor ?>
	</table>
<?php endforeach ?>
</body>
</html>