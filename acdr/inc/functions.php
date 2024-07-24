<?php

/* Recorded file */
function formatFiles($row, $file_params) {
	# Кнопка прослухати запис
	$tpl['btn_record'] = '
		<div class="img_play" data-title="'.$row['calldate'].'"></div>
	';
	
	# Кнопка завантажити запис
	$tpl['btn_download'] = '
		<div class="img_dl"></div>
	';	
	
	# Кнопка видалити запис
	$tpl['btn_delete'] = '
		<div class="img_delete"></div>
	';
	
	# Файл не знайдено
	$tpl['error'] = '
		<td class="record_col">
			<div class="img_notfound"></div>
		</td>
	';

	# Прослуховування, скачування, видалення
	$tpl['record'] = '
		<td class="record_col">
			<div class="recordBox">
				[_btn_record]
				[_btn_download]
				[_btn_delete]
			</div>
		</td>
	';
	
	# Скачування, видалення
	$tpl['download'] = '
		<td class="record_col">
			<div class="recordBox">
				[_btn_download]
			</div>
		</td>
	';
	
	$tpl['record'] = str_replace(
		array(
			'[_btn_record]',
			'[_btn_download]',
			'[_btn_delete]',
		),
		array(
			Config::get('display.main.rec_play') == 1 ? $tpl['btn_record'] : '',
			$tpl['btn_download'],
			Config::get('display.main.rec_delete') == 1 ? $tpl['btn_delete'] : '',
		),
		$tpl['record']
	);
	$tpl['download'] = str_replace('[_btn_download]', $tpl['btn_download'], $tpl['download']);
	
	# Файл не існує
	$tmp['result'] = $tpl['error'];
	# Аудіо
	if ( $file_params['type'] == 'audio' ) {
		$tmp['result'] = $tpl['record'];
	}
	# Архів
	else if ( $file_params['type'] == 'archive' ) {
		$tmp['result'] = $tpl['download'];
	}
	# Факс
	else if ( $file_params['type'] == 'fax' ) {
		$tmp['result'] = $tpl['download'];
	}

	echo $tmp['result'];
}

# Отримати параметри файлу запису дзвінка
function getFileParams($row) {
	# uniq_name.mp3
	$recorded_file = '';
	$tmp['result'] = array(
		# Тип файлу. false, якщо файл не існує
		'type' => false,
		# Шлях до файлу в base64. Те, що після dl.php?f=
		'path' => '',
	);
	$tmp['system_audio_format'] = Config::get('system.audio_format');
	# У базі є колонка з ім'ям запису розмови
	if ( isset($row[Config::get('system.column_name')]) ) {
		$recorded_file = $row[Config::get('system.column_name')];
	}
	
	$mycalldate_ymd		= substr($row['calldate'], 0, 10); // ymd
	$mycalldate_ym		= substr($row['calldate'], 0, 7); // ym
	$mycalldate_y		= substr($row['calldate'], 0, 4); // y
	$mycalldate_m		= substr($row['calldate'], 5, 2); // m
	$mycalldate_d		= substr($row['calldate'], 8, 2); // d
	$mydate				= date('Y-m-d');

	# Ім'я файлу в разі відкладеної конвертації
	if ( Config::get('system.audio_defconv') == 1 && $recorded_file ) {
		if ( $mycalldate_ymd < $mydate ) {
			$recorded_file = preg_replace('#(.+)\.(wav|mp3|wma|ogg|aac)$#i', '${1}.'.$tmp['system_audio_format'], $recorded_file);
		} else {
			$tmp['system_audio_format'] = 'wav';
		}
	}	
	
	# Отримання імені файлу та шляху
	if ( $mycalldate_ymd < $mydate && Config::get('system.storage_format') === 1 ) {
		$rec['filename'] = "$mycalldate_y/$mycalldate_ym/$mycalldate_ymd/$recorded_file";
	} else if ( $mycalldate_ymd < $mydate && Config::get('system.storage_format') === 2 ) {
		$rec['filename'] = "$mycalldate_y/$mycalldate_m/$mycalldate_d/$recorded_file";
	} else if ( Config::get('system.storage_format') === 3 ) {
		$rec['filename'] = "$mycalldate_y/$mycalldate_ym/$mycalldate_ymd/$recorded_file";
	} else if ( Config::get('system.storage_format') === 4 ) {
		$rec['filename'] = "$mycalldate_y/$mycalldate_m/$mycalldate_d/$recorded_file";
	} else if ( Config::get('system.storage_format') === 5 ) {
		$rec['filename'] = $recorded_file;
	} else if ( Config::get('system.storage_format') === 6 ) {
		$rec['filename'] = $recorded_file.'.'.$tmp['system_audio_format'];
	} else {
		$rec['filename'] = $recorded_file;
	}
	
	$rec['path'] = Config::get('system.monitor_dir').'/'.$rec['filename'];
	
	# Аудіо
	if ( is_file($rec['path'])
		&& $recorded_file
		&& filesize($rec['path'])/1024 >= Config::get('system.fsize_exists')
		&& preg_match('#(.+)\.'.$tmp['system_audio_format'].'$#i', $rec['filename'])
	) {
		$tmp['result'] = array(
			'type' => 'audio',
			'path' => base64_encode($rec['filename']),
		);
	}
	# Архів
	else if ( Config::exists('system.archive_format')
		&& $recorded_file
		&& is_file($rec['path'].'.'.Config::get('system.archive_format'))
		&& filesize($rec['path'].'.'.Config::get('system.archive_format'))/1024 >= Config::get('system.fsize_exists')
	) {
		$tmp['result'] = array(
			'type' => 'archive',
			'path' => base64_encode( $rec['filename'].'.'.Config::get('system.archive_format') ),
		);
	}
	# Факс
	//else if (file_exists($rec['path']) && preg_match('#(.*)\.tiff?$#i', $rec['filename']) && $rec['filesize'] >= Config::get('system.fsize_exists')) {
	else if ( is_file($rec['path'])
		&& $recorded_file
		&& filesize($rec['path'])/1024 >= Config::get('system.fsize_exists')
	) {
		$tmp['result'] = array(
			'type' => 'fax',
			'path' => base64_encode($rec['filename']),
		);
	}

	return $tmp['result'];
}

/* CDR Table Display Functions */
function formatCallDate($calldate, $uniqueid) {
	//$calldate = date('d.m.Y H:i:s', strtotime($calldate));
	echo '<td class="record_col"><abbr class="simptip-position-top simptip-smooth simptip-fade" data-clipboard data-clipboard-text="'.$uniqueid.'" data-tooltip="UID: '.$uniqueid.'">'.$calldate.'</abbr></td>' . PHP_EOL;
}

function formatChannel($channel) {
	$chan['short'] = preg_replace('#(.*)\/[^\/]+$#', '$1', $channel);
	$chan['full'] = preg_replace('#(.*)-[^-]+$#', '$1', $channel);
	$chan['tooltip'] = $chan['full'];
	$chan['txt'] = $chan['short'];
	if ( Config::exists('display.main.full_channel_tooltip') && Config::get('display.main.full_channel_tooltip') == 1 ) {
		$chan['tooltip'] = $channel;
	}
	if ( Config::exists('display.main.full_channel') && Config::get('display.main.full_channel') == 1 ) {
		$chan['txt'] = $chan['full'];
	}
	echo '<td class="record_col"><abbr class="simptip-position-top simptip-smooth simptip-fade" data-clipboard data-clipboard-text="'.$chan['tooltip'].'" data-tooltip="Канал: '.$chan['tooltip'].'">'.$chan['txt'].'</abbr></td>' . PHP_EOL;
}

function formatClid($clid) {
	$clid_only = explode(' <', $clid, 2);
	$clid_only = htmlspecialchars($clid_only[0]);
	$clid = htmlspecialchars($clid);
	echo '<td class="record_col"><abbr class="simptip-position-top simptip-smooth simptip-fade" data-clipboard data-clipboard-text="'.$clid.'" data-tooltip="CallerID: '.$clid.'">'.$clid_only.'</abbr></td>' . PHP_EOL;
}

function formatSrc($src, $clid) {
	if ( empty($src) ) {
		echo '<td class="record_col">Невідомо</td>' . PHP_EOL;
	} else {
		$src = htmlspecialchars($src);
		$clid = htmlspecialchars($clid);
		$src_show = $src;
		$clipboard = 'data-clipboard data-clipboard-text="'.$clid.'"';
		if ( is_numeric($src) && strlen($src) >= Config::get('display.lookup.num_length') && strlen(Config::get('display.lookup.url')) > 0 ) {
			$rev = str_replace( '%n', $src, Config::get('display.lookup.url') );
			$src_show = '<a href="'.$rev.'" target="reverse">'.$src.'</a>';
			$clipboard = '';
		}
		echo '<td class="record_col"><abbr class="simptip-position-top simptip-smooth simptip-fade" '.$clipboard.' data-tooltip="CallerID: '.$clid.'">'.$src_show.'</abbr></td>' . PHP_EOL;
	}
}

function formatApp($app, $lastdata) {
	$tooltip = $app . '(' . $lastdata . ')';
	echo '<td class="record_col"><abbr class="simptip-position-top simptip-smooth simptip-fade" data-clipboard data-clipboard-text="'.$tooltip.'" data-tooltip="Додаток: '.$tooltip.'">'.$app.'</abbr></td>' . PHP_EOL;
}

function formatDst($dst, $dcontext) {
	$dst_show = $dst;
	$clipboard = 'data-clipboard data-clipboard-text="'.$dcontext.'"';
	if ( is_numeric($dst) && strlen($dst) >= Config::get('display.lookup.num_length') && strlen(Config::get('display.lookup.url')) > 0 ) {
		$rev = str_replace( '%n', $dst, Config::get('display.lookup.url') );
		$dst_show = '<a href="'.$rev.'" target="reverse">'.$dst.'</a>';
		$clipboard = '';
	}
	echo '<td class="record_col"><abbr class="simptip-position-top simptip-smooth simptip-fade" '.$clipboard.' data-tooltip="Контекст призначення: '.$dcontext.'">'.$dst_show.'</abbr></td>' . PHP_EOL;
}

function formatDisposition($disposition, $amaflags) {
	switch ($amaflags) {
		case 0:
			$amaflags = 'DOCUMENTATION';
			break;
		case 1:
			$amaflags = 'IGNORE';
			break;
		case 2:
			$amaflags = 'BILLING';
			break;
		case 3:
		default:
			$amaflags = 'DEFAULT';
	}
	// Стиль тексту для викликів
	$style = '';
	switch ($disposition) {
		case 'ANSWERED':
			$dispTxt = 'Прийнято';
			$style = 'answer';
			break;
		case 'NO ANSWER':
			$dispTxt = 'Відхилино';
			$style = 'noanswer';
			break;
		case 'BUSY':
			$dispTxt = 'Зайнято';
			$style = 'busy';
			break;
		case 'FAILED':
			$dispTxt = 'Помилка';
			$style = 'failed';
			break;
		case 'CONGESTION':
			$dispTxt = 'Перенавантаження';
			$style = 'congestion';
			break;
		// FreeSWITCH
		case 'NORMAL_CLEARING':
			$dispTxt = 'Прийнято FS';
			$style = 'answer';
			break;
		case 'NORMAL_UNSPECIFIED':
			$dispTxt = 'Прийнято FS (можливо перерваний)';
			$style = 'answer';
			break;
		case 'RECOVERY_ON_TIMER_EXPIRE':
			$dispTxt = 'Відхилино FS';
			$style = 'noanswer';
			break;
		case 'ORIGINATOR_CANCEL':
			$dispTxt = 'Абонент скасував FS';
			$style = 'noanswer';
			break;
		case 'USER_BUSY':
			$dispTxt = 'Зайнято FS';
			$style = 'busy';
			break;
		case 'CALL_REJECTED':
			$dispTxt = 'Помилка FS';
			$style = 'failed';
			break;
		case 'USER_NOT_REGISTERED':
			$dispTxt = 'Користувач не зареєстрований FS';
			$style = 'failed';
			break;
		case 'NO_USER_RESPONSE':
			$dispTxt = 'Немає відповіді FS';
			$style = 'failed';
			break;
		case 'UNALLOCATED_NUMBER':
			$dispTxt = 'Неіснуючий номер FS';
			$style = 'failed';
			break;
		case 'NORMAL_TEMPORARY_FAILURE':
			$dispTxt = 'Перенавантаження FS';
			$style = 'congestion';
			break;
		default:
			$dispTxt = $disposition;
	}
	echo '<td class="record_col '.$style.'"><div class="status status-'.$style.'"></div><abbr class="simptip-position-top simptip-smooth simptip-fade" data-clipboard data-clipboard-text="'.$amaflags.'" data-tooltip="AMA прапор: '.$amaflags.'">'.$dispTxt.'</abbr></td>' . PHP_EOL;
}

function formatDuration($duration, $billsec) {
	$duration = sprintf( '%02d', intval($duration/60) ).':'.sprintf( '%02d', intval($duration%60) );
	$billduration = sprintf( '%02d', intval($billsec/60) ).':'.sprintf( '%02d', intval($billsec%60) );
	echo '<td class="record_col"><abbr class="simptip-position-top simptip-smooth simptip-fade" data-clipboard data-clipboard-text="'.$billduration.'" data-tooltip="Обробка дзвінка: '.$billduration.'">'.$duration.'</abbr></td>' . PHP_EOL;
}

function formatDurWait($duration, $billsec) {
	$durwait = $duration - $billsec;
	$durwait = sprintf( '%02d', intval($durwait/60) ).':'.sprintf( '%02d', intval($durwait%60) );
	$duration = sprintf( '%02d', intval($duration/60) ).':'.sprintf( '%02d', intval($duration%60) );
	echo '<td class="record_col"><abbr class="simptip-position-top simptip-smooth simptip-fade" data-clipboard data-clipboard-text="'.$duration.'" data-tooltip="Тривалість: '.$duration.'">'.$durwait.'</abbr></td>' . PHP_EOL;
}

function formatBillSec($duration, $billsec) {
	$duration = sprintf( '%02d', intval($duration/60) ).':'.sprintf( '%02d', intval($duration%60) );
	$billduration = sprintf( '%02d', intval($billsec/60) ).':'.sprintf( '%02d', intval($billsec%60) );
	echo '<td class="record_col"><abbr class="simptip-position-top simptip-smooth simptip-fade" data-clipboard data-clipboard-text="'.$duration.'" data-tooltip="Тривалість: '.$duration.'">'.$billduration.'</abbr></td>' . PHP_EOL;
}

function formatUserField($userfield) {
	echo '<td class="record_col userfield">'.$userfield.'</td>' . PHP_EOL;
}

function formatAccountCode($accountcode) {
	echo '<td class="record_col"><abbr data-clipboard data-clipboard-text="'.$accountcode.'">'.$accountcode.'</abbr></td>' . PHP_EOL;
}

/* Asterisk RegExp parser */
function asteriskregexp2sqllike($source_data, $user_num) {
	$number = $user_num;
	if (strlen($number) < 1) {
		$number = $_REQUEST[$source_data];
	}
	if ('__' == substr($number,0,2)) {
		$number = substr($number,1);
	} elseif ('_' == substr($number,0,1)) {
		$number_chars = preg_split('//', substr($number,1), -1, PREG_SPLIT_NO_EMPTY);
		$number = '';
		foreach ($number_chars as $chr) {
			if ($chr == 'X') {
				$number .= '[0-9]';
			} elseif ($chr == 'Z') {
				$number .= '[1-9]';
			} elseif ($chr == 'N') {
				$number .= '[2-9]';
			} elseif ($chr == '.') {
				$number .= '.+';
			} elseif ($chr == '!') {
				$_REQUEST[ $source_data .'_neg' ] = 'true';
			} else {
				$number .= $chr;
			}
		}
		$_REQUEST[$source_data .'_mod'] = 'asterisk-regexp';
	}
	return $number;
}

/* empty() wrapper */
function is_blank(&$value) {
	if ( isset($value) ) {
		return empty($value) && !is_numeric($value);
	}
	return true;
}

/* 
	Money format

*/
// cents: 0=never, 1=if needed, 2=always
// title: title to show
function formatMoney($number, $cents = 2, $title = '') {
	if ( is_numeric($number) ) {
		// whole number
		if ( floor($number) == $number ) {
			$money = number_format( $number, ($cents == 2 ? 2 : 0) ); // format
		} else { // cents
			$money = number_format( round($number, 2), ($cents == 0 ? 0 : 2) ); // format
		} // integer or decimal
		
		if ( $title ) {
			$title = ' class="simptip-position-top simptip-smooth simptip-fade" data-clipboard data-clipboard-text="'.$title.'" data-tooltip="'.$title.'"';
		}
		echo '<td class="chart_data"><span'.$title.'>'.$money.'</span>'.Config::get('callrate.currency').'</td>' . PHP_EOL;
	} else {
		echo '<td class="chart_data"></td>' . PHP_EOL;
	}
}

/* 
	CallRate
	return callrate array [ areacode, rate, description, bill type, total_rate] 
*/
function callrates($dst, $duration, $file) {
	global $callrate_cache;
	if (strlen($file) == 0) {
		$file = Config::get('callrate.csv_file');
		if (strlen($file) == 0) {
			return array('', '', '', '', '');
		}
	}
	
	if (!array_key_exists($file, $callrate_cache)) {
		$callrate_cache[$file] = array();
		$fr = fopen($file, 'r') or exit('Не вдалося відкрити файл з тарифами ('.$file.')');
		while (($fr_data = fgetcsv($fr, 1000, ',')) !== false) {
			if ($fr_data[0] !== null) {
				// Не вказано дод. тариф
				if ( !isset($fr_data[4]) ) {
					$fr_data[4] = null;
				}
				$callrate_cache[$file][$fr_data[0]] = array($fr_data[1], $fr_data[2], $fr_data[3], $fr_data[4]);
			}
		}
		fclose($fr);
	}

	for ($i = strlen($dst); $i > 0; $i--) {
		if (array_key_exists(substr($dst,0,$i), $callrate_cache[$file])) {
			$call_rate = 0;
			if ($callrate_cache[$file][substr($dst,0,$i)][2] == 's') {
				// per second
				if ( $duration >= Config::get('callrate.free_interval') ) {
					$call_rate = $duration * ($callrate_cache[$file][substr($dst,0,$i)][0] / 60);
				}
			} elseif ($callrate_cache[$file][substr($dst,0,$i)][2] == 'c') {
				// per call
				if ( $duration >= Config::get('callrate.free_interval') ) {
					$call_rate = $callrate_cache[$file][substr($dst,0,$i)][0];
				}
			} elseif ($callrate_cache[$file][substr($dst,0,$i)][2] == '1m+s') {
				// 1 minute + per second
				if ( $duration < Config::get('callrate.free_interval') ) {}
				else if ( $duration < 60 ) {
					$call_rate = $callrate_cache[$file][substr($dst,0,$i)][0];
				} else {
					// вказано дод. тариф
					if (isset($callrate_cache[$file][substr($dst,0,$i)][3]) && $callrate_cache[$file][substr($dst,0,$i)][3] && $callrate_cache[$file][substr($dst,0,$i)][3] > 0) {
						$ext_rate = $callrate_cache[$file][substr($dst,0,$i)][3];
					} else {
						$ext_rate = $callrate_cache[$file][substr($dst,0,$i)][0]/60;
					}
					$call_rate = $callrate_cache[$file][substr($dst,0,$i)][0] + ( ($duration-60) * ($ext_rate) );
				}
			} elseif ($callrate_cache[$file][substr($dst,0,$i)][2] == '30s+s') {
				// 30 second + per second
				if ($duration > 0 && $duration <= 30) {
					$call_rate = ($callrate_cache[$file][substr($dst,0,$i)][0] / 2);
				} elseif ( $duration > 30 && $duration < 60) {
					$call_rate = ($callrate_cache[$file][substr($dst,0,$i)][0] / 2) + (($duration-30) * ($callrate_cache[$file][substr($dst,0,$i)][0] / 60));
				} else {
					$call_rate = $callrate_cache[$file][substr($dst,0,$i)][0] + (($duration-60) * ($callrate_cache[$file][substr($dst,0,$i)][0] / 60));
				}
			} elseif ($callrate_cache[$file][substr($dst,0,$i)][2] == '30s+6s') {
				// 30 second + 6 second
				if ($duration > 0 && $duration <= 30) {
					$call_rate = ($callrate_cache[$file][substr($dst,0,$i)][0] / 2);
				} else {
					$call_rate = ceil($duration / 6) * ($callrate_cache[$file][substr($dst,0,$i)][0] / 10);
				}
			} else {
				//( $callrate_cache[substr($dst,0,$i)][2] == 'm' ) {
				// per minute
				if ( $duration >= Config::get('callrate.free_interval') ) {
					// всього хвилин розмова
					$call_rate = ceil($duration/60);
					// вказано дод. тариф
					if (isset($callrate_cache[$file][substr($dst,0,$i)][3]) && $callrate_cache[$file][substr($dst,0,$i)][3] && $callrate_cache[$file][substr($dst,0,$i)][3] > 0) {
						$call_rate = $callrate_cache[$file][substr($dst,0,$i)][0] + ( ($call_rate-1) * $callrate_cache[$file][substr($dst,0,$i)][3] );
					} else {
						$call_rate = $call_rate*$callrate_cache[$file][substr($dst,0,$i)][0];
					}					
				}
			}
			return array(substr($dst,0,$i),$callrate_cache[$file][substr($dst,0,$i)][0],$callrate_cache[$file][substr($dst,0,$i)][1],$callrate_cache[$file][substr($dst,0,$i)][2],$call_rate);
		}
	}
	return array (0, 0, 'Невідомо', 'Невідомо', 0);
}


/* 
	Connect to DB. Return PDO object
	$errors - show connection errors
*/
function dbConnect( $errors = true ) {
	$dbh = null;
	try {
		$dbh = new PDO(
			Config::get('db.type') .
			':host=' . Config::get('db.host') .
			';port=' . Config::get('db.port') . 
			';dbname=' . Config::get('db.name'),
			Config::get('db.user'),
			Config::get('db.pass'),
			Config::get('db.options') 
		);
	}
	catch (PDOException $e) {
		if ( $errors === true ) {
			echo "\nPDO::errorInfo():\n";
			print $e->getMessage();
		}
	}
	return $dbh;
}