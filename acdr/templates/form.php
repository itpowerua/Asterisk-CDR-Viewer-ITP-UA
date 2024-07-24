<div id="main">

<div id="form-container">
	<div id="form-loader">
		<div class="cssload-loader">
			<div class="cssload-inner cssload-one"></div>
			<div class="cssload-inner cssload-two"></div>
			<div class="cssload-inner cssload-three"></div>
		</div>				
	</div>
<table class="cdr cdr-main">
<tr>
<td>

<form method="post" enctype="application/x-www-form-urlencoded" action="">
<fieldset>
<legend class="title">Перегляд записів про вхідні/вихідні дзвінки</legend>
<table width="100%">
<tr>
	<th>Сортування</th>
	<th>Фільтри</th>
	<th>&nbsp;</th>
</tr>
<tr>
<td><input checked="checked" id="id_order_calldate" type="radio" name="order" value="calldate">&nbsp;<label for="id_order_calldate">Дата</label></td>
<td>С&nbsp;
<select name="startday" id="startday">
	<?php
	for ($i = 1; $i <= 31; $i++) {
		if ( date('d', time()) == $i ) {
			echo '<option value="'.$i.'" selected="selected">'.$i.'</option>';
		} else {
			echo '<option value="'.$i.'">'.$i.'</option>';
		}
	}
	?>
</select>
<select name="startmonth" id="startmonth">
<?php
$months = array('01' => 'Січень', '02' => 'Лютий', '03' => 'Березень', '04' => 'Квітень', '05' => 'Травень', '06' => 'Червень', '07' => 'Липень', '08' => 'Серпень', '09' => 'Вересень', '10' => 'Жовтень', '11' => 'Листопад', '12' => 'Грудень');
foreach ($months as $i => $month) {
	if ( date('m') == $i ) {
		echo '<option value="'.$i.'" selected="selected">'.$month.'</option>';
	} else {
		echo '<option value="'.$i.'">'.$month.'</option>';
	}
}
?>
</select>
<select name="startyear" id="startyear">
	<?php
	for ( $i = 2000; $i <= date('Y'); $i++) {
		if ( date('Y') == $i ) {
			echo '<option value="'.$i.'" selected="selected">'.$i.'</option>';
		} else {
			echo '<option value="'.$i.'">'.$i.'</option>';
		}
	}
	?>
</select>&nbsp;
<select name="starthour" id="starthour">
	<?php
	for ($i = 0; $i <= 23; $i++) {
		if ( $i == 0 ) {
			echo '<option value="'.$i.'" selected="selected">'.$i.'</option>';
		} else {
			echo '<option value="'.$i.'">'.$i.'</option>';
		}
	}
	?>
</select>
:
<select name="startmin" id="startmin">
	<?php
	for ($i = 0; $i <= 59; $i++) {
		if ( $i == 0 ) {
			echo '<option value="'.sprintf('%02d', $i).'" selected="selected">'.sprintf('%02d', $i).'</option>';
		} else {
			echo '<option value="'.sprintf('%02d', $i).'">'.sprintf('%02d', $i).'</option>';
		}
	}
	?>
</select>
&ensp;
По&ensp;
<select name="endday" id="endday">
	<?php
	for ($i = 1; $i <= 31; $i++) {
		if ( $i == 31 ) {
			echo '<option value="'.$i.'" selected="selected">'.$i.'</option>';
		} else {
			echo '<option value="'.$i.'">'.$i.'</option>';
		}
	}
	?>
</select>
<select name="endmonth" id="endmonth">
	<?php
	foreach ($months as $i => $month) {
		if ( date('m') == $i ) {
			echo '<option value="'.$i.'" selected="selected">'.$month.'</option>';
		} else {
			echo '<option value="'.$i.'">'.$month.'</option>';
		}
	}
	?>
</select>
<select name="endyear" id="endyear">
	<?php
	for ( $i = 2000; $i <= date('Y'); $i++) {
		if ( date('Y') == $i ) {
			echo '<option value="'.$i.'" selected="selected">'.$i.'</option>';
		} else {
			echo '<option value="'.$i.'">'.$i.'</option>';
		}
	}
	?>
</select>&nbsp;
<select name="endhour" id="endhour">
	<?php
	for ($i = 0; $i <= 23; $i++) {
		if ( $i == 23 ) {
			echo '<option value="'.$i.'" selected="selected">'.$i.'</option>';
		} else {
			echo '<option value="'.$i.'">'.$i.'</option>';
		}
	}
	?>
</select>
:
<select name="endmin" id="endmin">
	<?php
	for ($i = 0; $i <= 59; $i++) {
		if ( $i == 59 ) {
			echo '<option value="'.sprintf('%02d', $i).'" selected="selected">'.sprintf('%02d', $i).'</option>';
		} else {
			echo '<option value="'.sprintf('%02d', $i).'">'.sprintf('%02d', $i).'</option>';
		}
	}
	?>
</select>
&emsp;
<select id="id_range" name="range">
	<option class="head">Вибрати період...</option>
	<option value="td">Сьогодні</option>
	<option value="yd">Вчора</option>
	<option value="3d">Останні 3 дні</option>
	<option value="tw">Поточний тиждень</option>
	<option value="pw">Попередній тиждень</option>
	<option value="3w">Останні 3 тижні</option>
	<option value="tm">Поточний місяць</option>
	<option value="pm">Попередній місяць</option>
	<option value="3m">Останні 3 місяці</option>
</select>
</td>
<td rowspan="13" valign='top' align='right'>
<fieldset>
<legend class="title">Додаткові опції</legend>
<table>
<tr>
	<td>Тип відліку&ensp;</td>
	<td>
		<input checked="checked" type="checkbox" id="id_need_html" name="need_html" value="true">&ensp;<label for="id_need_html">Пошук у базі</label><br>
		<?php if ( Config::get('display.search.csv') == (1||2) ) { ?>
		<div class="<?php if ( Config::get('display.search.csv') == 2 ) { echo 'spoilers'; } ?>">
			<input type="checkbox" id="id_need_csv" name="need_csv" value="true">&ensp;<label for="id_need_csv">CSV файл</label><br>
		</div>
		<?php } ?>
		<?php if ( Config::get('display.search.chart') == (1||2) ) { ?>
		<div class="<?php if ( Config::get('display.search.chart') == 2 ) { echo 'spoilers'; } ?>">
			<input type="checkbox" id="id_need_chart" name="need_chart" value="true">&ensp;<label for="id_need_chart">Графік дзвінків</label><br>
		</div>
		<?php } ?>
		<?php if ( Config::get('display.search.minutes_report') == (1||2) ) { ?>
		<div class="<?php if ( Config::get('display.search.minutes_report') == 2 ) { echo 'spoilers'; } ?>">		
			<input type="checkbox" id="id_need_minutes_report" name="need_minutes_report" value="true">&ensp;<label for="id_need_minutes_report">Витрати хвилин</label><br>
		</div>
		<?php } ?>
		<?php if ( Config::get('display.search.chart_cc') == (1||2) ) { ?>
		<div class="<?php if ( Config::get('display.search.chart_cc') == 2 ) { echo 'spoilers'; } ?>">
			<input type="checkbox" id="id_need_chart_cc" name="need_chart_cc" value="true">&ensp;<label for="id_need_chart_cc">Паралельні дзвінки</label><br>
		</div>
		<?php } ?>
		<?php if ( Config::get('display.search.asr_report') == (1||2) ) { ?>
		<div class="<?php if ( Config::get('display.search.asr_report') == 2 ) { echo 'spoilers'; } ?>">
			<input type="checkbox" id="id_need_asr_report" name="need_asr_report" value="true">&ensp;<label for="id_need_asr_report">ASR та ACD</label><br> 
		</div>
		<?php } ?>
	</td>
</tr>

<?php if ( Config::exists('system.plugins') && Config::get('system.plugins') && count( Config::get('system.plugins') ) > 0 ) { ?>
<tr>
	<td label for="Plugins">Плагіни&ensp;</td>
	<td>
		<hr>
		<?php
		foreach ( Config::get('system.plugins') as $p_key => $p_val ) {
			echo '<input id="id_need_'.$p_val.'" type="checkbox" name="need_'.$p_val.'" value="true">&ensp;<label for="id_need_'.$p_val.'">'. $p_key .'</label><br>';
		}
		?>
	</td>
</tr>
<?php } ?>

<tr>
	<td>
		<label for="id_result_limit">Кількість рядків</label>&ensp;
	</td>
	<td>
		<hr>
		<input id="id_result_limit" list="list_result_limit" value="<?php echo Config::get('display.main.result_limit'); ?>" name="limit" type="number" min="0" step="1" autocomplete="off">
		<datalist id="list_result_limit">
			<option value="10"></option>
			<option value="50"></option>
			<option value="100"></option>
			<option value="500"></option>
			<option value="2000"></option>
		</datalist>
	</td>
</tr>
</table>
</fieldset>
</td>
</tr>

<?php if ( Config::get('display.search.channel') == (1||2) ) { ?>
<tr class="<?php if ( Config::get('display.search.channel') == 2 ) { echo 'spoilers'; } ?>">
	<td>
		<input type="radio" id="id_order_channel" name="order" value="channel">&nbsp;<label for="id_order_channel">Вхідний канал</label>
	</td>
	<td>
		<input class="margin-left0" type="text" name="channel" id="channel">
		<input type="checkbox" name="channel_neg" value="true" id="id_channel_neg"> <label for="id_channel_neg">Не</label> &ensp;
		<input checked="checked" type="radio" name="channel_mod" value="begins_with" id="id_channel_mod1"> <label for="id_channel_mod1">Починається з</label> &ensp;
		<input type="radio" name="channel_mod" value="contains" id="id_channel_mod2"> <label for="id_channel_mod2">Містить</label> &ensp;
		<input type="radio" name="channel_mod" value="ends_with" id="id_channel_mod3"> <label for="id_channel_mod3">Закінчується на</label> &ensp;
		<input type="radio" name="channel_mod" value="exact" id="id_channel_mod4"> <label for="id_channel_mod4">Дорівнює</label>
	</td>
</tr>
<?php } ?>

<?php if ( Config::get('display.search.src') == (1||2) ) { ?>
<tr class="<?php if ( Config::get('display.search.src') == 2 ) { echo 'spoilers'; } ?>">
	<td>
		<input type="radio" id="id_order_src" name="order" value="src">&nbsp;<label for="id_order_src">Хто дзвонив</label>
	</td>
	<td>
		<input class="margin-left0" type="text" name="src" id="src">
		<input type="checkbox" name="src_neg" value="true" id="id_src_neg"> <label for="id_src_neg">Не</label> &ensp;
		<input checked="checked" type="radio" name="src_mod" value="begins_with" id="id_src_mod1"> <label for="id_src_mod1">Починається з</label> &ensp;
		<input type="radio" name="src_mod" value="contains" id="id_src_mod2"> <label for="id_src_mod2">Містить</label> &ensp; 
		<input type="radio" name="src_mod" value="ends_with" id="id_src_mod3"> <label for="id_src_mod3">Закінчується на</label> &ensp;
		<input type="radio" name="src_mod" value="exact" id="id_src_mod4"> <label for="id_src_mod4">Дорівнює</label> 
	</td>
</tr>
<?php } ?>

<?php if ( Config::get('display.search.clid') == (1||2) ) { ?>
<tr class="<?php if ( Config::get('display.search.clid') == 2 ) { echo 'spoilers'; } ?>">
	<td>
		<input type="radio" id="id_order_clid" name="order" value="clid">&nbsp;<label for="id_order_clid">Ім'я абонента</label>
	</td>
	<td>
		<input class="margin-left0" type="text" name="clid" id="clid">
		<input type="checkbox" name="clid_neg" value="true" id="id_clid_neg"> <label for="id_clid_neg">Не</label> &ensp;
		<input checked="checked" type="radio" name="clid_mod" value="begins_with" id="id_clid_mod1"> <label for="id_clid_mod1">Починається з</label> &ensp;
		<input type="radio" name="clid_mod" value="contains" id="id_clid_mod2"> <label for="id_clid_mod2">Містить</label> &ensp; 
		<input type="radio" name="clid_mod" value="ends_with" id="id_clid_mod3"> <label for="id_clid_mod3">Закінчується на</label> &ensp;
		<input type="radio" name="clid_mod" value="exact" id="id_clid_mod4"> <label for="id_clid_mod4">Дорівнює</label> 
	</td>
</tr>
<?php } ?>

<?php if ( Config::get('display.search.dst') == (1||2) ) { ?>
<tr class="<?php if ( Config::get('display.search.dst') == 2 ) { echo 'spoilers'; } ?>">
	<td>
		<input type="radio" id="id_order_dst" name="order" value="dst">&nbsp;<label for="id_order_dst">Куди дзвонили</label>
	</td>
	<td>
		<input class="margin-left0" type="text" name="dst" id="dst">
		<input type="checkbox" name="dst_neg" value="true" id="id_dst_neg"> <label for="id_dst_neg">Не</label> &ensp;
		<input checked="checked" type="radio" name="dst_mod" value="begins_with" id="id_dst_mod1"> <label for="id_dst_mod1">Починається з</label> &ensp;
		<input type="radio" name="dst_mod" value="contains" id="id_dst_mod2"> <label for="id_dst_mod2">Містить</label> &ensp; 
		<input type="radio" name="dst_mod" value="ends_with" id="id_dst_mod3"> <label for="id_dst_mod3">Закінчується на</label> &ensp;
		<input type="radio" name="dst_mod" value="exact" id="id_dst_mod4"> <label for="id_dst_mod4">Дорівнює</label> 
	</td>
</tr>
<?php } ?>

<?php if ( Config::get('display.search.did') == (1||2) ) { ?>
<tr class="<?php if ( Config::get('display.search.did') == 2 ) { echo 'spoilers'; } ?>">
	<td>
		<input type="radio" id="id_order_did" name="order" value="did">&nbsp;<label for="id_order_did">DID (якщо є)</label>
	</td>
	<td>
		<input class="margin-left0" type="text" name="did" id="did">
		<input type="checkbox" name="did_neg" value="true" id="id_did_neg"> <label for="id_did_neg">Не</label> &ensp;
		<input checked="checked" type="radio" name="did_mod" value="begins_with" id="id_did_mod1"> <label for="id_did_mod1">Починається з</label> &ensp;
		<input type="radio" name="did_mod" value="contains" id="id_did_mod2"> <label for="id_did_mod2">Містить</label> &ensp; 
		<input type="radio" name="did_mod" value="ends_with" id="id_did_mod3"> <label for="id_did_mod3">Закінчується на</label> &ensp;
		<input type="radio" name="did_mod" value="exact" id="id_did_mod4"> <label for="id_did_mod4">Дорівнює</label> 
	</td>
</tr>
<?php } ?>

<?php if ( Config::get('display.search.dstchannel') == (1||2) ) { ?>
<tr class="<?php if ( Config::get('display.search.dstchannel') == 2 ) { echo 'spoilers'; } ?>">
	<td>
		<input type="radio" id="id_order_dstchannel" name="order" value="dstchannel">&nbsp;<label for="id_order_dstchannel">Вихідний канал</label>
	</td>
	<td>
		<input class="margin-left0" type="text" name="dstchannel" id="dstchannel">
		<input type="checkbox" name="dstchannel_neg" value="true" id="id_dstchannel_neg"> <label for="id_dstchannel_neg">Не</label> &ensp;
		<input checked="checked" type="radio" name="dstchannel_mod" value="begins_with" id="id_dstchannel_mod1"> <label for="id_dstchannel_mod1">Починається з</label> &ensp;
		<input type="radio" name="dstchannel_mod" value="contains" id="id_dstchannel_mod2"> <label for="id_dstchannel_mod2">Містить</label> &ensp; 
		<input type="radio" name="dstchannel_mod" value="ends_with" id="id_dstchannel_mod3"> <label for="id_dstchannel_mod3">Закінчується на</label> &ensp;
		<input type="radio" name="dstchannel_mod" value="exact" id="id_dstchannel_mod4"> <label for="id_dstchannel_mod4">Дорівнює</label> 
	</td>
</tr>
<?php } ?>

<?php if ( Config::get('display.search.accountcode') == (1||2) ) { ?>
<tr class="<?php if ( Config::get('display.search.accountcode') == 2 ) { echo 'spoilers'; } ?>">
	<td>
		<input type="radio" id="id_order_accountcode" name="order" value="accountcode">&nbsp;<label for="id_order_accountcode">Код акаунту</label>
	</td>
	<td>
		<input class="margin-left0" type="text" name="accountcode" id="accountcode">
		<input type="checkbox" name="accountcode_neg" value="true" id="id_accountcode_neg"> <label for="id_accountcode_neg">Не</label> &ensp;
		<input checked="checked" type="radio" name="accountcode_mod" value="begins_with" id="id_accountcode_mod1"> <label for="id_accountcode_mod1">Починається з</label> &ensp;
		<input type="radio" name="accountcode_mod" value="contains" id="id_accountcode_mod2"> <label for="id_accountcode_mod2">Містить</label> &ensp; 
		<input type="radio" name="accountcode_mod" value="ends_with" id="id_accountcode_mod3"> <label for="id_accountcode_mod3">Закінчується на</label> &ensp;
		<input type="radio" name="accountcode_mod" value="exact" id="id_accountcode_mod4"> <label for="id_accountcode_mod4">Дорівнює</label> 
	</td>
</tr>
<?php } ?>

<?php if ( Config::get('display.search.userfield') == (1||2) ) { ?>
<tr class="<?php if ( Config::get('display.search.userfield') == 2 ) { echo 'spoilers'; } ?>">
	<td>
		<input type="radio" id="id_order_userfield" name="order" value="userfield">&nbsp;<label for="id_order_userfield">Коментар</label>
	</td>
	<td>
		<input class="margin-left0" type="text" name="userfield" id="userfield">
		<input type="checkbox" name="userfield_neg" value="true" id="id_userfield_neg"> <label for="id_userfield_neg">Не</label> &ensp;
		<input checked="checked" type="radio" name="userfield_mod" value="begins_with" id="id_userfield_mod1"> <label for="id_userfield_mod1">Починається з</label> &ensp;
		<input type="radio" name="userfield_mod" value="contains" id="id_userfield_mod2"> <label for="id_userfield_mod2">Містить</label> &ensp; 
		<input type="radio" name="userfield_mod" value="ends_with" id="id_userfield_mod3"> <label for="id_userfield_mod3">Закінчується на</label> &ensp;
		<input type="radio" name="userfield_mod" value="exact" id="id_userfield_mod4"> <label for="id_userfield_mod4">Дорівнює</label> 
	</td>
</tr>
<?php } ?>

<?php if ( Config::get('display.search.billsec') == (1||2) ) { ?>
<tr class="<?php if ( Config::get('display.search.billsec') == 2 ) { echo 'spoilers'; } ?>">
	<td>
		<input type="radio" id="id_order_billsec" name="order" value="billsec">&nbsp;<label for="id_order_billsec">Тривалість обробки дзвінка</label>
	</td>
	<td>
		<input type="number" min="0" step="1" name="bill_min" id="id_bill_min" placeholder="от">
		&ensp;&ndash;&ensp;
		<input type="number" min="0" step="1" name="bill_max" id="id_bill_max" placeholder="до">
		&nbsp;<label for="id_bill_max">сек.</label>
	</td>
</tr>
<?php } ?>

<?php if ( Config::get('display.search.duration') == (1||2) ) { ?>
<tr class="<?php if ( Config::get('display.search.duration') == 2 ) { echo 'spoilers'; } ?>">
	<td>
		<input type="radio" id="id_order_duration" name="order" value="duration">&nbsp;<label for="id_order_duration">Тривалість повна</label>
	</td>
	<td>
		<input type="number" min="0" step="1" name="dur_min" id="id_dur_min" placeholder="от">
		&ensp;&ndash;&ensp;
		<input type="number" min="0" step="1" name="dur_max" id="id_dur_max" placeholder="до">
		&nbsp;<label for="id_dur_max">сек.</label>
	</td>
</tr>
<?php } ?>

<?php if ( Config::get('display.search.lastapp') == (1||2) ) { ?>
<tr class="<?php if ( Config::get('display.search.lastapp') == 2 ) { echo 'spoilers'; } ?>">
	<td><input type="radio" id="id_order_lastapp" name="order" value="lastapp">&nbsp;<label for="id_order_lastapp">Додаток</label></td>
	<td>
		<input class="margin-left0" type="checkbox" name="lastapp_neg" id="id_lastapp_neg" value="true"> <label for="id_lastapp_neg">Не</label>&nbsp;
		<select name="lastapp" id="lastapp">
			<option selected="selected" value="all">Будь-яке</option>
			<option value="Dial">Набір номера</option>
			<option value="RetryDial">Повторний набір</option>
			<option value="Queue">Черга</option>
			<option value="Hangup">Роз'єднання</option>
			<option value="Playback">Відтворення</option>
			<option value="VoiceMail">Голосова пошта</option>
		</select>
	</td>
</tr>
<?php } ?>

<?php if ( Config::get('display.search.disposition') == (1||2) ) { ?>
<tr class="<?php if ( Config::get('display.search.disposition') == 2 ) { echo 'spoilers'; } ?>">
	<td><input type="radio" id="id_order_disposition" name="order" value="disposition">&nbsp;<label for="id_order_disposition">Статус дзвінка</label></td>
	<td>
		<input class="margin-left0" type="checkbox" name="disposition_neg" id="id_disposition_neg" value="true"> <label for="id_disposition_neg">Не</label>&nbsp;
		<select name="disposition" id="disposition">
			<option selected="selected" value="all">Будь-який</option>
			<!-- Asterisk -->
			<?php if ( Config::get('system.server_mode') == 0 || Config::get('system.server_mode') == 1 ) { ?>
				<?php if ( Config::get('system.server_mode') == 0 ) { ?>
				<optgroup label="Asterisk">
				<?php } ?>			
					<option value="ANSWERED">Прийнятий</option>
					<option value="NO ANSWER">Пропущений</option>
					<option value="BUSY">Зайнято</option>
					<option value="FAILED">Помилка</option>
					<option value="CONGESTION">Перенавантаження</option>
				<?php if ( Config::get('system.server_mode') == 0 ) { ?>
				</optgroup>
				<?php } ?>				
			<?php } ?>
			<!-- / Asterisk -->
			<!-- FreeSWITCH -->
			<?php if ( Config::get('system.server_mode') == 0 || Config::get('system.server_mode') == 2 ) { ?>
				<?php if ( Config::get('system.server_mode') == 0 ) { ?>
				<optgroup label="FreeSWITCH">
				<?php } ?>				
					<option value="NORMAL_CLEARING">Прийнятий FS</option>
					<option value="NORMAL_UNSPECIFIED">Прийнятий FS (можливо перерваний)</option>
					<option value="RECOVERY_ON_TIMER_EXPIRE">Пропущений FS</option>
					<option value="ORIGINATOR_CANCEL">Телефонуючий скасував FS</option>
					<option value="USER_BUSY">Зайнято FS</option>
					<option value="CALL_REJECTED">Помилка FS</option>
					<option value="USER_NOT_REGISTERED">Користувач не зареєстрований FS</option>
					<option value="NO_USER_RESPONSE">Нема відповіді FS</option>
					<option value="UNALLOCATED_NUMBER">Неіснуючий номер FS</option>
					<option value="NORMAL_TEMPORARY_FAILURE">Перенавантаження FS</option>
					<?php if ( Config::get('system.server_mode') == 0 ) { ?>
					</optgroup>
					<?php } ?>
			<?php } ?>
			<!-- / FreeSWITCH -->
		</select>
	</td>
</tr>
<?php } ?>

<tr>
	<td>
		<select name="sort" id="sort">
		<option value="ASC">за зростанням</option>
		<option selected="selected" value="DESC">по спаданню</option>
		</select>
	</td>
	<td>
		<label for="group">Групувати за</label>&nbsp;
		<select name="group" id="group">
			<optgroup label="Інформація про акаунт</option>
				<option value="userfield">Коментар</option>
			</optgroup>
			<optgroup label="Дата / Час">
				<option value="minutes1">Хвилина</option>
				<option value="minutes10">10 хвилин</option>
				<option value="hour">Година</option>
				<option value="hour_of_day">Одна година дня</option>
				<option value="day_of_week">День тижня</option>
				<option selected="selected" value="day">День</option>
				<option value="week">Тиждень (ПН-НД)</option>
				<option value="month">Місяць</option>
			</optgroup>
			<optgroup label="Номер телефону">
				<option value="clid">Ім'я абонента</option>
				<option value="src">Хто дзвонив</option>
				<option value="did">DID</option>
				<option value="dst">Куди дзвонили</option>
			</optgroup>
			<optgroup label="Тех. інформація">
				<option value="disposition">Статус</option>
				<option value="disposition_by_day">Статус по днях</option>
				<option value="disposition_by_hour">Статус з годинником</option>
				<option value="dcontext">Контекст</option>
			</optgroup>
		</select>
	</td>
</tr>
<tr>
	<td>
	&nbsp;
	</td>
	<td>
		<div id="show_spoilers">
			<span>Додаткові фільтри</span>
		</div>
	</td>
</tr>
<tr>
	<td>
	&nbsp;
	</td>
	<td>
		<input type="hidden" name="form_submitted" value="1">
		<button id="form_submit" class="submit btn btn-info margin-left0">Шукати</button>
		<input checked="checked" type="radio" id="id_search_mode_all" name="search_mode" value="all"> <label for="id_search_mode_all">По всіх фільтрах</label>&ensp;
		<input type="radio" id="id_search_mode_any" name="search_mode" value="any"> <label for="id_search_mode_any">По будь-якому з фільтрів</label>
	</td>
</tr>
</table>
</fieldset>
</form>
</td>
</tr>
</table>
</div>

