<?php

//新規：値をGET（押した日付）
//DB接続
//日付をデフォルト値に
//入力
//送信&元の画面に戻る

require_once dirname(__FILE__). '/calendar.php';

//年月を取得、nullの場合は今月の値
$this_year = $_GET["year"];
$this_month= $_GET["month"];
if (empty($this_year)) $this_year   = date(Y);
if (empty($this_month)) $this_month = date(m);

$target_time = mktime(0,0,0,$this_month,1,$this_year);

$cal = new Calendar($target_time);



$this_day= $_GET["day"];
if (empty($this_day)) $this_day  = date(d);



//編集：値をGET（押した日付、id、




?>


<form method="post" action="result.php">
	タイトル：
	<input type="text" name="title" maxlength="64"/>
	予定開始時間：
	<input type="text" name="s_year"  size="3" maxlength="4" value="<?php echo ($this_year) ?>"/>年
	<input type="text" name="s_month" size="1" maxlength="2" value="<?php echo ($this_month) ?>"/>月
	<input type="text" name="s_day"   size="1" maxlength="2" value="<?php echo ($this_day) ?>"/>日
	<input type="time" name="s_sche_time" step="1" value="00:00:00"/>
	予定終了時間：
	<input type="text" name="f_year"  size="3" maxlength="4" value="<?php echo ($this_year) ?>"/>年
	<input type="text" name="f_month" size="1" maxlength="2" value="<?php echo ($this_month) ?>"/>月
	<input type="text" name="f_day"   size="1" maxlength="2" value="<?php echo ($this_day) ?>"/>日
	<input type="time" name="f_sche_time" step="1" value="00:00:00"/>
	場所：
	<input type="text" name="place" maxlength="64"/>
	備考：
	<input type="text" name="remark" size="100" maxlength="128"/>

	<input type="submit" value="予定を反映させる"/>

		<?php 

		//関数呼び出し
		//MySQLにデータ送信
		//↓これをべつのページで
		//$cal->insert_sche("翌営業日から本気出す", "2014-08-04 00:00:00", "2014-08-04 23:59:59", "デスク", "早く帰りたい");

		//関数呼び出し
		//ページ遷移	

		?>

</form>



<?php

//idを持っているか判定

//持っていたら「予定削除」ボタン表示

//MySQLのdeleted_atに現在の時刻を入れる

?>