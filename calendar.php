<?php

class Calendar
{
     private $target_time;


     public function __construct($target_time)
     {
          $this->target_time = $target_time;
     }

     /**
      * 「$countヶ月後の1日のタイムスタンプ」を出力する
      *
      * @access public
      * @param integer $count 第一引数
      * @return integer
      * @author Shota Takaki
      */
     public function jumptime($count, $target_time = null)
     {
          if (is_null($target_time)) 
          {
               $target_time = $this->target_time;
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
     public function jumpyear($count, $target_time)
     {
          return date("Y", $this->jumptime($count, $target_time));
     }

     /**
      * 「$countヶ月後の月の値」を出力する
      *
      * @access public
      * @param integer $count 第一引数
      * @return integer
      * @author Shota Takaki
      */
     public function jumpmonth($count, $target_time)
     {
          return date("m", $this->jumptime($count, $target_time));
     }

     /**
      * 「参照月から$targetヶ月後の日数」を出力する
      *
      * @access public
      * @param integer $target 第一引数
      * @return integer
      * @author Shota Takaki
      */
     public function lastday($target)
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
     public function startday($target)
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
     public function countday($target, $start_days_of_the_week)
     {
          return 1 - ($this->startday($target) - $start_days_of_the_week + 7) % 7;
     }

     /**
      * 「カレンダーの週数」を出力する
      *
      * @access public
      * @param integer $target 第一引数
      * @return integer
      * @author Shota Takaki
      */
     public function countweek($target, $start_days_of_the_week)
     {
          return ceil(($this->lastday($target) - $this->countday($target, $start_days_of_the_week) + 1 ) / 7);
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
     public function addcolor($time, $year, $month, $day, $holiday)
     {
          $this_day = strtotime($day-1 . ' day', $time);
          if ($day > 0 && $day <= $this->lastday($time))
          {
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
          }else
          {
               if (date("Y/m/d",$this_day) == date("Y/m/d")) 
               {
                    return "gtoday ";
               }elseif (date(w,$this_day) == 0) 
               {
                    return "gsunday ";
               }elseif (date(w,$this_day) == 6) 
               {
                    return "gsaturday ";
               }elseif ($holiday[date("Ymd",$this_day)] !== null)
               {
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
     public function startcolor($day, $start_days_of_the_week)
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
     public function getHolidays($start, $finish) 
     {
          $holidays = array();
      
          //Googleカレンダーから、指定年の祝日情報をJSON形式で取得するためのURL
          $url = sprintf
          (
               'http://www.google.com/calendar/feeds/%s/public/full?alt=json&%s&%s',
               'outid3el0qkcrsuf89fltf7a4qbacgt9@import.calendar.google.com',
               'start-min=' . $start,
               'start-max=' . $finish
          );

          //JSON形式で取得した情報を配列に変換
          $results = json_decode(file_get_contents($url), true);

          //年月日（例：20120512）をキーに、祝日名を配列に格納
          foreach ($results['feed']['entry'] as $value)
          {
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
     public function addholiday($time, $year, $month, $day, $holiday)
     {
          $this_day = strtotime($day-1 . ' day', $time);
          if ($holiday[date("Ymd",$this_day)] !== null){
               return date("Ymd",$this_day);
          }
     }

     /**
      *オクトピを[日付][記事数][タイトルorURL]で取得
      *
      * @access public
      * @param 無し
      * @return integer
      * @author Shota Takaki
      */
     public function getauc()
     {
          $rss ='http://aucfan.com/article/feed/';
          //ファイルの中の整形式 XML ドキュメントをオブジェクトに変換
          $xml = simplexml_load_file($rss);
          
          //xmlから年月日とtitle、linkを取得
          foreach ($xml->channel->item as $value) 
          {
               $pub_date = $value->pubDate;
               $date = date('Ymd', strtotime($pub_date));
               
               $aucdata[$date][] = array(
               'title' => (string)mb_strimwidth($value->title, 0, 24, "…", utf8),
               'link' => (string)$value->link
               );
          }
          return $aucdata;
     }

     /**
      *オクトピが書かれている場合、その日の値を「date("Ymd"）」で出力する
      *
      * @access public
      * @param integer $time
      * @param integer $year
      * @param integer $month
      * @param integer $day
      * @param integer $auctopic
      * @return integer
      * @author Shota Takaki
      */
     public function addauc($time, $year, $month, $day, $auctopic)
     {
          $this_day = strtotime($day-1 . ' day', $time);
          if ($auctopic[date("Ymd",$this_day)][0]['title'] !== null){
               return date("Ymd",$this_day);
          }
     }

     public function getdb($target_time, $pmonth, $nmonth)
     {
          //予定取得開始日
          //$start_date  = date('Y-m-d H:i:s', strtotime($pmonth . ' month', $target_time));
          $start_date  = date('Y-m-d H:i:s', strtotime($pmonth . ' month', $target_time));

          //予定取得終了日
          $finish_date = date('Y-m-d H:i:s', strtotime($nmonth . ' month' . '-1 second', $target_time ));

          $url = 'localhost';
          $user = 'root';
          $pass  ='';

          //MySQLに接続
          $link = mysqli_connect($url, $user, $pass);

          //接続状態チェック
          if (mysqli_connect_errno()) 
          {
               echo '接続に失敗しました';
          }

          $db_selected = mysqli_select_db($link, 'calendar');
          if (!$db_selected)
          {
              die('データベース選択失敗です。'.mysqli_error());
          }
          
          $result = mysqli_query($link, 'select * from schedules');
          if (!$result) 
          {
              die('クエリーが失敗しました。'.mysqli_error());
          }

          while ($row = mysqli_fetch_assoc($result))
          {

               $start = date("Ymd", strtotime($row["start_at"]));

               $sche[$start][] = $row;

          }

          return $sche;

     }


     public function insert_sche($title, $start_at, $finish_at, $place, $remark)
     {
          $url = 'localhost';
          $user = 'root';
          $pass  ='';

          //MySQLに接続
          $link = mysqli_connect($url, $user, $pass);

          //接続状態チェック
          if (mysqli_connect_errno()) 
          {
               echo '接続に失敗しました';
          }

          $db_selected = mysqli_select_db($link, 'calendar');
          if (!$db_selected)
          {
              die('データベース選択失敗です。'.mysqli_error());
          }

          $time = date("Y-m-d H:i:s");

          $stmt = mysqli_prepare($link, 
               "insert into schedules
               (title, start_at, finish_at, place, remark, update_at, created_at) 
               value
               (?, ?, ?, ?, ?, ?, ?)"
          );
          mysqli_stmt_bind_param($stmt, "sssssss", $title, $start_at, $finish_at, $place, $remark, $time, $time);
          mysqli_stmt_execute($stmt);
          $lastid = mysqli_insert_id($link);
          mysqli_stmt_close($stmt);

          return $lastid;
     }

     public function select_sche($id, $text)
     {
          $url = 'localhost';
          $user = 'root';
          $pass  ='';

          //MySQLに接続
          $link = mysqli_connect($url, $user, $pass);

          //接続状態チェック
          if (mysqli_connect_errno()) 
          {
               echo '接続に失敗しました';
          }

          $db_selected = mysqli_select_db($link, 'calendar');
          if (!$db_selected)
          {
              die('データベース選択失敗です。'.mysqli_error());
          }

          $id = mysqli_real_escape_string($link,$id);

          $query = mysqli_query($link, 
               "select $text from schedules
               where
               schedule_id = $id"
          );

          return mysqli_fetch_assoc($query);
     }

     public function update_sche($title, $start_at, $finish_at, $place, $remark, $id)
     {
          $url = 'localhost';
          $user = 'root';
          $pass  ='';

          //MySQLに接続
          $link = mysqli_connect($url, $user, $pass);

          //接続状態チェック
          if (mysqli_connect_errno()) 
          {
               echo '接続に失敗しました';
          }

          $db_selected = mysqli_select_db($link, 'calendar');
          if (!$db_selected)
          {
              die('データベース選択失敗です。'.mysqli_error());
          }

          $remark = mysqli_real_escape_string($link,$remark);

          $time = date("Y-m-d H:i:s");

          $stmt = mysqli_prepare($link, 
               "update schedules set
               title = ?, start_at = ?, finish_at = ?, place = ?, remark = ?, update_at = ?
               where schedule_id = ?"
          );
          mysqli_stmt_bind_param($stmt, "ssssssi", $title, $start_at, $finish_at, $place, $remark, $time, $id);
          mysqli_stmt_execute($stmt);
          $lastid = mysqli_insert_id($link);
          mysqli_stmt_close($stmt);

          return $lastid;
     }

     public function delete_sche($id)
     {
          $url = 'localhost';
          $user = 'root';
          $pass  ='';

          //MySQLに接続
          $link = mysqli_connect($url, $user, $pass);

          //接続状態チェック
          if (mysqli_connect_errno()) 
          {
               echo '接続に失敗しました';
          }

          $db_selected = mysqli_select_db($link, 'calendar');
          if (!$db_selected)
          {
              die('データベース選択失敗です。'.mysqli_error());
          }

          $time = date("Y-m-d H:i:s");

          $stmt = mysqli_prepare($link, 
               "update schedules set
               deleted_at = ?
               where schedule_id = ?"
          );
          mysqli_stmt_bind_param($stmt, "si", $time, $id);
          mysqli_stmt_execute($stmt);
          mysqli_stmt_close($stmt);

     }


     public function last_update()
     {
          $url = 'localhost';
          $user = 'root';
          $pass  ='';

          //MySQLに接続
          $link = mysqli_connect($url, $user, $pass);

          //接続状態チェック
          if (mysqli_connect_errno()) 
          {
               echo '接続に失敗しました';
          }

          $db_selected = mysqli_select_db($link, 'calendar');
          if (!$db_selected)
          {
              die('データベース選択失敗です。'.mysqli_error());
          }

          $query = mysqli_query($link, 
               "select * from schedules
               order by update_at desc limit 1"
          );

          return mysqli_fetch_assoc($query);
     }


     public function last_id()
     {
          $url = 'localhost';
          $user = 'root';
          $pass  ='';

          //MySQLに接続
          $link = mysqli_connect($url, $user, $pass);

          //接続状態チェック
          if (mysqli_connect_errno()) 
          {
               echo '接続に失敗しました';
          }

          $db_selected = mysqli_select_db($link, 'calendar');
          if (!$db_selected)
          {
              die('データベース選択失敗です。'.mysqli_error());
          }

          return mysqli_insert_id();
     }


     public function date_id($year, $month, $day)
     {

          return date(Ymd, mktime(0, 0, 0, $month, $day, $year));

     }

}

class Token
{
     public function get_csrf_token() 
     {
          $TOKEN_LENGTH = 16;
          $bytes = openssl_random_pseudo_bytes($TOKEN_LENGTH);

          // ?
          $_SESSION['csrf_tokens'][$bytes] = ture;

          return bin2hex($bytes);
     }

}
 
?>