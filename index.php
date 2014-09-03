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



$test = $_POST['title'];


//トークン
$tok = new Token();

session_start();

$token = $tok->get_csrf_token();

$_SESSION["sdata"] = $token;

//echo $token;


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

	.inputform
	{
		position:fixed;
		top:50%;
		left:50%;
		height:150px;
		width:600px;
		margin-top:-90px;
		margin-left:-315px;
		background-color: #FFFFAD;
		padding: 30px;
		visibility: hidden;

	}

	.active
	{
		visibility: visible;
	}
	
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
					<td class="<?php echo $cal->addcolor($value['time'], $value['year'], $value['month'], $c, $holiday) ?> <?php echo($cal->date_id($value['year'], $value['month'], $c)) ?>" align="left" valign="top">
						<?php if($c > 0 && $c <= $cal->lastday($value['time'])) : ?>
							<div>
								<a href="<?php echo htmlspecialchars('http://kensyu.aucfan.com/schedule.php?year=' . $value['year'] . '&month=' . $value['month'] . '&day=' . $c) ?>" class="schedule">
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
			                        	<a href="<?php echo htmlspecialchars('http://kensyu.aucfan.com/schedule.php?year=' . $value['year'] . '&month=' . $value['month'] . '&day=' . $c . '&id=' .  $schedule_array['schedule_id'] ) ?>" class="schedule">
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

<!-- JS処理 -->
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script>

//alert("JSアラートテスト");

$(function($)
{
    var ac_sche;

	$.get("schedule.php?", 
	
	function(data)
	{
	  //alert(data);
	});



    /*$.ajax({
        type: 'GET',
        url: "http://kensyu.aucfan.com/schedule.php",
        dataType: "html",
        //cache: false,
        
        data:{
        	year:,
        	month:,
        	day:,
        	id:,

        }

        success: function(data, status) {
        	console.debug(data);
            $('.popup').empty().append(data);
           
        }
    });*/




	var count = 0;

	$("body").on("click", ".schedule",function(e) 
    {
		ac_sche = $(e.target);
    
    	//クリックしたURL先を取得
    	var href = $(e.target).attr('href');
    	//$($(e.target).attr('href')).show();
    	//alert(href);

    	//リンク先の該当する部分から値を取得し、入力フォームへ挿入	
    	$.get(href, function(data){
    		var jstitle = $(data).find('input[name=title]').val();
    		$('#title').val(jstitle);
    		var jss_year = $(data).find('input[name=s_year]').val();
    		$('input[name=s_year]').val(jss_year);
    		var jss_month = $(data).find('input[name=s_month]').val();
    		$('input[name=s_month]').val(jss_month);
    		var jss_day = $(data).find('input[name=s_day]').val();
    		$('input[name=s_day]').val(jss_day);
    		var jss_sche_time = $(data).find('input[name=s_sche_time]').val();
    		$('input[name=s_sche_time]').val(jss_sche_time);
    		var jsf_year = $(data).find('input[name=f_year]').val();
    		$('input[name=f_year]').val(jsf_year);
    		var jsf_month = $(data).find('input[name=f_month]').val();
    		$('input[name=f_month]').val(jsf_month);
    		var jsf_day = $(data).find('input[name=f_day]').val();
    		$('input[name=f_day]').val(jsf_day);
    		var jsf_sche_time = $(data).find('input[name=f_sche_time]').val();
    		$('input[name=f_sche_time]').val(jsf_sche_time);
    		var jsplace = $(data).find('input[name=place]').val();
    		$('#place').val(jsplace);
    		var jsremark = $(data).find('input[name=remark]').val();
    		$('#remark').val(jsremark);
    		var jsid = $(data).find('input[name=id]').val();
    		$('input[name=id]').val(jsid);

    		console.debug(jsid);
    		
    		//新規と編集で入力フォームをわける
    		if(typeof jsid === 'undefined'){
				//$("#delete").toggleClass("inactive");
				$('.edit').css('display','none');
				$('.new').css('display','');
			}
			else
			{
				$('.edit').css('display','');
				$('.new').css('display','none');
			}

    	});



		count++;
	    $("span").text('clicks: ' + count);
	    $("#inputform").addClass("active");

	    
    	//クリックしてもリンク先に飛ばない
    	return false;
    });



	$("#close").click(function(e) 
    {
		$("#inputform").removeClass("active");
    });

 
	

	$(".register").click(function(e) {
		var result_type =  $(e.target).attr('id');
		$form = result_type == 1 ? $('form.new') : $('form.edit');

		//$form.find('input[name=.....]').val();

		/*if (result_type == 1) {
                                   	var title_type = $('#title1').val();
                                   	var place_type = $('#place1').val();
                                   	var remark_type = $('#remark1').val();




                               	   }
                               	   else
                               	   {
                               	   	var title_type = $('#title').val();
                                   	var place_type = $('#place').val();
                                   	var remark_type = $('#remark').val();
                               	   }*/
        var title_type = $form.find('input[name=title]').val();
        var place_type = $form.find('input[name=place]').val();
        var remark_type = $form.find('input[name=remark]').val();

    	var regi_year = $form.find('input[name=s_year]').val();
    	var regi_month = $form.find('input[name=s_month]').val();
    	var regi_day = $form.find('input[name=s_day]').val();

    	var s_id = $form.find('input[name=id]').val();

		var post_vars = {
                                   title:       title_type,
                                   place:       place_type,
                                   remark:      remark_type,
								   s_year:      regi_year,
                                   s_month:     regi_month,
                                   s_day:       regi_day,
                                   s_sche_time: $form.find('input[name=s_sche_time]').val(),
                                   f_year:      $form.find('input[name=f_year]').val(),
                                   f_month:     $form.find('input[name=f_month]').val(),
                                   f_day:       $form.find('input[name=f_day]').val(),
                                   f_sche_time: $form.find('input[name=f_sche_time]').val(),
                                   id:          s_id,
                                   result: result_type, 
                                   csrf_token: '<?php echo "$token";?>',
                        };
       /*if() {
       		post_vars.id = $("").val();
       }*/

		$.ajax({
                    type     : 'POST',
                    dataType : 'text',
                    url      : 'result.php',
                    data     : post_vars
                }).done(function(data) {
                    // ajax ok
                    //$("#disp_area").html(data);


                    //新規作成だったら
                    //ajaxでid取得→var idに代入
                    if (result_type == 1) 
                    {
                    s_id = Number($(data).text());
                    };


                	if (result_type != 3) 
                	{
	                    var search_id = "." + (regi_year * 10000 + regi_month * 100 + regi_day * 1);
					    //var a_tag = $('<a></a>').attr("href", 'http://kensyu.aucfan.com/schedule.php?year=' + regi_year + '&month=' + regi_month + '&day=' + regi_day + '&id=' + id);
					    //$(search_id).append('<div></div>').append(a_tag);
					    var url = 'http://kensyu.aucfan.com/schedule.php?year=' + regi_year + '&month=' + regi_month + '&day=' + regi_day + '&id=' + s_id ;
					    $(search_id).append("<div style='font-size: 8px'><a href=" + url + " class=\"schedule\">" + title_type + "</a></div>");
    				};

    				if (result_type != 1) 
    				{
    				ac_sche.remove();
    				};

                    $("#inputform").removeClass("active");
                }).fail(function(data) {
                    // ajax error
                    $("#disp_area").html('Error');
                }).always(function(data) {
                    // ajax complete
                });





   /* $.ajax({
        url: 'calendar.php',
        type:'post'
    }).then(function(data,status){
        // 成功時
        console.log(status);
        $('body').append(data);
    },function(data,status){
        // 失敗時
        console.log(status);
    });*/

    


	    //URL先に飛ばない
	    return false;

	});








});


</script>

<div class="inputform" id="inputform">
	    
	<form method="post" action="result.php" class="new">
		<div>
		タイトル：
		<input type="text" name="title" maxlength="64" id="title1"/>
		</div>
		<div>
			予定開始時間：
			<input type="text" name="s_year"  size="3" maxlength="4" />年
			<input type="text" name="s_month" size="1" maxlength="2" />月
			<input type="text" name="s_day"   size="1" maxlength="2" />日
			<input type="time" name="s_sche_time" step="1" />
		</div>
		<div>
			予定終了時間：
			<input type="text" name="f_year"  size="3" maxlength="4" />年
			<input type="text" name="f_month" size="1" maxlength="2" />月
			<input type="text" name="f_day"   size="1" maxlength="2" />日
			<input type="time" name="f_sche_time" step="1" />
		</div>
		<div>
			場所：
			<input type="text" name="place" maxlength="64" id="place1"/>
		</div>
		<div>
			備考：
			<input name="remark" size="100" maxlength="128" id="remark1"/>
		</div>

		<input type="hidden" name="result" value="0"/>

		<input type="hidden" name="csrf_token" value="<?php echo $token ?>"/>

		<input type="submit" value="作成する" style="margin:0px; float:left;" class="register" id="1"/>

	</form>



	<form method="post" action="result.php" class="edit" >
		<div>
		タイトル：
		<input type="text" name="title" maxlength="64" id="title"/>
		</div>
		<div>
			予定開始時間：
			<input type="text" name="s_year"  size="3" maxlength="4" />年
			<input type="text" name="s_month" size="1" maxlength="2" />月
			<input type="text" name="s_day"   size="1" maxlength="2" />日
			<input type="time" name="s_sche_time" step="1" />
		</div>
		<div>
			予定終了時間：
			<input type="text" name="f_year"  size="3" maxlength="4" />年
			<input type="text" name="f_month" size="1" maxlength="2" />月
			<input type="text" name="f_day"   size="1" maxlength="2" />日
			<input type="time" name="f_sche_time" step="1" />
		</div>
		<div>
			場所：
			<input type="text" name="place" maxlength="64" id="place"/>
		</div>
		<div>
			備考：
			<input name="remark" size="100" maxlength="128" id="remark"/>
		</div>	

		<input type="hidden" name="id" value="<?php echo htmlspecialchars($id) ?>"/>

		<input type="hidden" name="result" value="1"/>

		<input type="hidden" name="csrf_token" value="<?php echo $token ?>"/>

		<input type="submit" value="編集する" style="margin:0px; float:left;" class="register" id="2"/>

	</form>
	<form method="post" action="result.php" class="edit">
		<input type="hidden" name="id" value="<?php echo htmlspecialchars($id) ?>"/>
		<input type="hidden" name="result" value="2"/>

		<input type="hidden" name="csrf_token" value="<?php echo $token ?>"/>

		<input type="submit" value="削除する" style="margin:0px; float:left;" class="register" id="3"/>
	</form>

		<form>
		<input type="button" value="閉じる" id="close" style="margin:0px; float:left;"/>
		</form>
</div>


</body>
</html>