<?php

function my_callrates() {
	global
	$dbh,
	$db_table_name,
	$group_by_field,
	$where,
	$result_limit,
	$graph_col_title;

	/**************************** Config ****************************************************/
	$my_call_rates = array(
		"Міські" 		=> "(dst LIKE '2%' OR dst LIKE '7%') and (LENGTH(dst)=7)",
		"Мобільні"			=> "(dst LIKE '09%') and (LENGTH(dst)=10)",
		"По області" 			=> "(dst LIKE '0%') and (LENGTH(dst)=10)",
		"Столиця"			=> "(dst LIKE '044%' OR dst LIKE '044%' OR dst LIKE '044%') and (LENGTH(dst)=10)",
		"Україна" 			=> "(dst LIKE '380%') and (dst NOT LIKE '38%' and dst NOT LIKE '38%' and dst NOT LIKE '380%' and dst NOT LIKE '380%' and dst NOT LIKE '380%' and dst NOT LIKE '380%' and dst NOT LIKE '8800%') and (LENGTH(dst)=12)",
	);

	/****************************************************************************************/

	$my_bill_tototal_q = "SELECT $group_by_field AS group_by_field FROM $db_table_name $where GROUP BY group_by_field ORDER BY group_by_field ASC LIMIT $result_limit";

	$my_callrates_total = array();
	foreach ( array_keys($my_call_rates) as $key ) {
		$my_call_rates_total[$key] = 0;
	}
	$my_call_rates_total['summ'] = 0;

	echo '
		<p class="center title">Витрати коштів</p>
		<table class="cdr">
			<tr>
				<th>'.$graph_col_title.'</th>
				<th colspan="5">Напрямок</th>
			</tr>
			<tr><th>&nbsp;</th>
	';

	foreach ( array_keys($my_call_rates) as $key ) {
		echo "<th>$key</th>";
	}
	
	echo '<th>Разом</th></tr>';

	try {
		$sth = $dbh->query($my_bill_tototal_q);
		if (!$sth) {
			echo "\nPDO::errorInfo():\n";
			print_r($dbh->errorInfo());
		}

		$result = $sth->fetchAll(PDO::FETCH_NUM);

		$sth = null;

		foreach ( $result as $row ) {
			$summ = 0;
			echo '<tr class="record">';
			echo '<td style="text-align:center;">'. $row[0] .'</td>';
			foreach ( array_keys($my_call_rates) as $key ) {
				$my_bill_ch_q = "SELECT dst, billsec FROM $db_table_name $where and $group_by_field = '". $row[0] ."' and " . $my_call_rates[$key];
				$summ_local = 0;
				
				$sth2 = $dbh->query($my_bill_ch_q);
				if ( !$sth2 ) {
					echo "\nPDO::errorInfo():\n";
					print_r($dbh->errorInfo());
				}
				
				while ( $bill_row = $sth2->fetch(PDO::FETCH_NUM) ) {
					$rates = callrates( $bill_row[0], $bill_row[1], Config::get('callrate.csv_file') );
					$summ_local += $rates[4];
				}
				$sth2 = null;

				$my_call_rates_total[$key] += $summ_local;
				$summ += $summ_local;
				formatMoney($summ_local);
			}
			$my_call_rates_total['summ'] += $summ;
			formatMoney($summ);
			echo '</tr>';

		}
	}
	catch (PDOException $e) {
		print $e->getMessage();
	}

	echo '<tr class="chart_data total">';
	echo '<td>Всього</td>';
	foreach ( array_keys($my_call_rates_total) as $key ) {
		formatMoney($my_call_rates_total[$key]);
	}
	echo '</tr>';

	echo '</table>';
}


