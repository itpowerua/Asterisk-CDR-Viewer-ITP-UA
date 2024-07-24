<?php

# Перевірка оновлень
define( 'VERSION', getCurrentVersion() );

function getCurrentVersion() {
	$ver = '?';
	$path = 'inc/version.txt';
	if ( file_exists($path) ) {
		$f = trim( file_get_contents($path) );
		if ($f) {
			$ver = $f;
		}
	}
	return $ver;
}

function checkUpdates() {
	$res = false;
	$url  = 'https://github.com/itpowerua/Asterisk-CDR-Viewer-ITP-UA';
	if ( VERSION != '?' ) {
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_ENCODING, '');
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64; Trident/7.0; rv:11.0) like Gecko');
		curl_setopt($ch, CURLOPT_TIMEOUT, 5);
		curl_setopt($ch, CURLOPT_MAXREDIRS, 10);	
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);	
		$content = curl_exec($ch);
		$hc = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		$err = curl_errno($ch);
		$errmsg = curl_error($ch);
		curl_close($ch);	
		if ( !$err && !$errmsg && in_array($hc, array(200)) ) {
			$content = trim($content);
			if ($content) {
				$res = json_decode($content);
			}
		}
	}
	return $res;
}

if ( isset($_POST['check_updates']) ) {
	if ( strlen($cdr_user_name) > 0 ) {
		header('HTTP/1.1 403 Forbidden');
		exit;
	}
	$upd = checkUpdates();
	if ( $upd !== false ) {
		$msg = 'Немає оновлень';
		if ( $upd->name > VERSION ) {
			$msg = 'Доступна нова версія: ' . $upd->name . PHP_EOL .
					'Поточна версія: ' . VERSION . PHP_EOL . PHP_EOL .
					'У цьому релізі:' . PHP_EOL .
					$upd->body;
		}
		echo json_encode(array(
			'success' => true,
			'message' => $msg,
		));
	} else {
		echo json_encode(array(
			'success' => false,
			'message' => 'Не вдалося перевірити оновлення',
		));		
	}
	exit;
}

# Видалення запису дзвінка
if ( isset($_POST['delete_record']) ) {
	if ( strlen($cdr_user_name) > 0 || Config::get('display.main.rec_delete') == 0 ) {
		header('HTTP/1.1 403 Forbidden');
		exit;
	}
	$res = false;
	$data = json_decode($_POST['delete_record']);
	$path = Config::get('system.monitor_dir') . '/' . base64_decode($data->path);
	if ( file_exists($path) && is_file($path) ) {
		if ( @unlink($path) ) {
			// Очищення поля з ім'ям файлу запису дзвінка
			if ( $dbh = dbConnect(false) ) {
				$sth = $dbh->prepare('
					UPDATE '.Config::get('db.table').'
					SET '.Config::get('system.column_name').' = NULL
					WHERE id = :id
				');
				$sth = $sth->execute(array(
					'id' => $data->id,
				));
				if ($sth) {
					$res = true;
				}
			}
			echo json_encode(array(
				'success' => $res,
				'message' => $res === true ? 'Успішно вилучено' : 'Файл запису видалено, але не вдалося видалити назву файлу з бази',
			));			
		} else {
			echo json_encode(array(
				'success' => false,
				'message' => 'Немає прав на папку з файлом',
			));			
		}
	} else {
		echo json_encode(array(
			'success' => false,
			'message' => 'Файл не існує',
		));			
	}
	exit;
}
	
# Зміна поля "Коментар" (userfield)	
if ( isset($_POST['edit_userfield']) ) {
	if ( strlen($cdr_user_name) > 0 || Config::get('display.main.userfield_edit') == 0 ) {
		header('HTTP/1.1 403 Forbidden');
		exit;
	}
	$res = false;
	if ( $dbh = dbConnect(false) ) {
		$data = json_decode($_POST['edit_userfield']);
		$sth = $dbh->prepare('
			UPDATE '.Config::get('db.table').'
			SET userfield = :text
			WHERE id = :id
		');
		$sth = $sth->execute(array(
			'id' => $data->id,
			'text' => htmlspecialchars($data->text),
		));
		if ($sth) {
			$res = true;
		}
	}
	echo json_encode(array(
		'success' => $res,
	));
	exit;
}

# Видалення рядка з бази
if ( isset($_POST['delete_entry']) ) {
	if ( strlen($cdr_user_name) > 0 || Config::get('display.main.entry_delete') == 0 ) {
		header('HTTP/1.1 403 Forbidden');
		exit;
	}
	$res = false;
	if ( $dbh = dbConnect(false) ) {
		$data = json_decode($_POST['delete_entry']);
		$sth = $dbh->prepare('
			DELETE FROM '.Config::get('db.table').'
			WHERE id = :id
		');
		$sth = $sth->execute(array(
			'id' => $data->id,
		));
		if ($sth) {
			// Видалення файла запису дзвінка
			$path = Config::get('system.monitor_dir') . '/' . base64_decode($data->path);
			if ( file_exists($path) && is_file($path) ) {
				@unlink($path);
			}
			$res = true;
		}
	}
	echo json_encode(array(
		'success' => $res,
	));
	exit;	
}
