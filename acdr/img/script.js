
$(document).on('ready', function() {
	initClipboard();
	selectRange( $('#id_range').val() );
	
	// Стрілки навігації
	$('#scroll-box').on('click', '#scroll-up', function() {
		$('html, body').animate({ scrollTop: 0 }, 100);
		return false;
	});
	$('#scroll-box').on('click', '#scroll-down', function() {
		$('html, body').animate({ scrollTop: $(document).height() - $(window).height() }, 100);
		return false;
	});
	
	// Швидкий вибір періоду
	$('#id_range').on('change', function() {
		 selectRange( $(this).val() );
	});	
	
	// Показати плеєр
	$('body').on('click', '.img_play', function() {
		var $player = $(playerId),
			$overlay = $(playerOverlayId),
			autoplay = (playerAutoplay === true) ? 'play' : '',
			docTitle = document.title,
			$title = (playerTitle === true) ? $(this).data('title') : '',
			$config = $.query.get('config'),
			$config = $config != false ? '&config=' + $config : '',
			$link = 'dl.php?f=' + $(this).closest('tr').data('filepath') + $config,
			content = 
				'<div class="plTitle">'+$title+'</div>' +
				'<div class="plStyle" id="player"></div>'
		;
		//link = encodeURIComponent(link);	
		$overlay.css({
			'opacity': 1,
			'visibility': 'visible',
		});
		$player.show();
		$('title').first().html(playerSymbol + ' ' + docTitle);
		$player.html(content);
		this.aplayer = new Uppod({
			m:"audio",
			st:"uppodaudio",
			uid:"player",
			auto:autoplay,
			file:$link,
		});
	});
	
	// Приховати плеєр
	$('body').on('click', '#playerOverlay', function() {
		var $player = $(playerId),
			$overlay = $(playerOverlayId),
			docTitle = document.title;
		$overlay.css({
			'visibility': 'hidden',
			'opacity': 0,
		});
		$player.hide();
		document.title = docTitle.match(/\s(.*?)$/)[1];
		$player.html('');
	});
	
	// Завантажити запис
	$('body').on('click', '.img_dl', function() {
		$config = $.query.get('config');
		$config = $config != false ? '&config=' + $config : '';
		$link = 'dl.php?f=' + $(this).closest('tr').data('filepath') + $config;
		window.location.href = $link;
	});	
	
	// Завантажити CSV звіт
	$('body').on('click', '.dl_csv', function() {
		$config = $.query.get('config');
		$config = $config != false ? '&config=' + $config : '';
		window.location.href = 'dl.php?csv=' + $(this).data('filepath') + $config;
	});
	
	// Перевірка оновлень
	$('#check-updates').on('click', function() {
		$.ajax ({
			type: 'post',
			url: '',
			data: 'check_updates=1',
			dataType: 'json',
			timeout: 7000,
			cache: false,
			success: function(data) {
				if (data['success'] === true) {
					alert(data['message']);
				} else {
					alert('Не вдалося перевірити оновлення!');
				}
			},
			error: function(xhr, str) {
				alert('Не вдалося перевірити оновлення!');
			},			
		});
	});
	
	// Видалити запис
	$('body').on('click', '.img_delete', function() {
		$elem = $(this);
		$str = $elem.closest('tr');
		$id = $str.data('id');
		$path = $str.data('filepath');
		$params = {
			'id' : $id,
			'path' : $path,
		};		
		if ( confirm('Ви дійсно хочете видилити цей запис?') ) {
			$.ajax ({
				type: 'post',
				url: '',
				data: 'delete_record=' + JSON.stringify($params),
				dataType: 'json',
				timeout: 7000,
				cache: false,
				success: function(data) {
					if (data['success'] === true) {
						$elem.closest('.record_col').hide().html('<div class="img_notfound"></div>').fadeIn('slow');
					} else {
						alert('Помилка вилучення: ' + data['message']);
					}
				},
				error: function(xhr, str) {
					alert('Не вдалося видалити запис!');
				},			
			});
		}
	});
	
	// Надсилання форми
	$('#form_submit').on('click', function() {
		$form = $('form');
		$config = $.query.get('config');
		$config = $config != false ? '?config=' + $config : '';
		$.ajax ({
			type: 'post',
			url: '' + $config,
			data: $form.serialize(),
			cache: false,
			beforeSend: function(data) {
				$('#form-loader').show();
			},
			success: function(data) {
				if ( data.trim() != '' ) {
					$('#content').html(data);
				} else {
					$('#content').html('<div id="content-msg">Немає даних з вибраними параметрами</div>');
				}
				showScroll();
			},
			error: function(xhr, str) {
				$('#content').html('<div id="content-msg">Не вдалося отримати дані</div>');
			},
			complete: function(data) {
				$('#form-loader').hide();
			}
		});
		return false;
	});
	
	// Показати спойлери
	$('#show_spoilers span').on('click', function() {
		$('.spoilers').toggle('fast');
		showScroll();
		return false;
	});
	
	// Зміна коментаря
	$('body').on('click', '.userfield', function() {
		if (userfieldEdit === true) {
			$elem = $(this);
			$text = $elem.text().trim();
			$elem.html(
				'<div class="userfield-box">' +
					'<input data-oldtext="'+$text+'" value="'+$text+'" type="text">' +
					'<br>' +
					'<button class="btn btn-default userfield-save">&#10003;</button>' + 
					'<button class="btn btn-default userfield-cancel">&#215;</button>' +
				'</div>'
			);
			$elem.removeClass('userfield');
		}
	});
	$('body').on('click', '.userfield-save', function() {
		$elem = $(this);
		$userfield = $elem.closest('td');
		$id = $elem.closest('tr').data('id');
		$text = $userfield.find('input').val().trim();
		$params = {
			'id' : $id,
			'text' : $text,
		};
		$.ajax ({
			type: 'post',
			url: '',
			data: 'edit_userfield=' + JSON.stringify($params),
			dataType: 'json',
			timeout: 7000,
			cache: false,
			success: function(data) {
				if (data['success'] === true) {
					$userfield.find('input').data('oldtext', $text);
					$userfield.text($text).addClass('userfield');
				} else {
					$elem.removeClass('btn-default').addClass('btn-danger');
				}
			},
			error: function(xhr, str) {
				$elem.removeClass('btn-default').addClass('btn-danger');
			},
		});
	});	
	$('body').on('click', '.userfield-cancel', function() {
		$userfield = $(this).closest('td');
		$text = $userfield.find('input').data('oldtext');
		$userfield.text($text).addClass('userfield');
	});
	
	// Контекстне меню - Видалення рядка з бази
	if (entryDelete === true) {
		$.contextMenu({
			selector: '.record_cdr', 
			items: {
				'delete': {
					name: 'Видалити рядок',
					icon: 'delete',
					callback: function(key, options) {
						$elem = $(this);
						$str = $elem.closest('tr');
						$id = $str.data('id');
						$path = $str.data('filepath');
						$params = {
							'id' : $id,
							'path' : $path,
						};
						$.ajax ({
							type: 'post',
							url: '',
							data: 'delete_entry=' + JSON.stringify($params),
							dataType: 'json',
							timeout: 7000,
							cache: false,
							success: function(data) {
								if (data['success'] === true) {
									$str.hide();
								} else {
									alert('Не вдалося вилучити рядок!');
								}
							},
							error: function(xhr, str) {
								alert('Не вдалося видалити рядок!');
							},			
						});					
					},
				},
			}
		});
	}
	
});

// Показати навігацію
function showScroll() {
	if (scrollShow === true) {
		var $bodyHeight = $('body').height(),
			$docHeight = $(window).height(),
			$scroll = $('#scroll-box');
		
		if ($bodyHeight > $docHeight) {
			$scroll.show('fast');
		} else {
			$scroll.hide('fast');
		}
	}
}

// Швидкий вибір періоду
function selectRange(range) {
	switch (range) {
		// Сьогодні
		case 'td':
			var date = moment().subtract(0, 'day'),
				startDay = date.format('D'),
				startMonth = date.format('MM'),
				startYear = date.format('YYYY'),
				endDay = startDay,
				endMonth = startMonth,
				endYear = startYear
			;
			break;
		// Вчора
		case 'yd':
			var date = moment().subtract(1, 'day'),
				startDay = date.format('D'),
				startMonth = date.format('MM'),
				startYear = date.format('YYYY'),
				endDay = startDay,
				endMonth = startMonth,
				endYear = startYear
			;
			break;
		// Останні 3 дні	
		case '3d':
			var dateStart = moment().subtract(2, 'day'),
				startDay = dateStart.format('D'),
				startMonth = dateStart.format('MM'),
				startYear = dateStart.format('YYYY'),
				dateEnd = moment().subtract(0, 'day'),
				endDay = dateEnd.format('D'),
				endMonth = dateEnd.format('MM'),
				endYear = dateEnd.format('YYYY')
			;
			break;
		// Поточний тиждень
		case 'tw':
			var dateStart = moment().startOf('week'),
				startDay = dateStart.format('D'),
				startMonth = dateStart.format('MM'),
				startYear = dateStart.format('YYYY'),
				dateEnd = moment().endOf('week'),
				endDay = dateEnd.format('D'),
				endMonth = dateEnd.format('MM'),
				endYear = dateEnd.format('YYYY')
			;
			break;
		// Попередній тиждень
		case 'pw':
			var dateStart = moment().subtract(7, 'day').startOf('week'),
				startDay = dateStart.format('D'),
				startMonth = dateStart.format('MM'),
				startYear = dateStart.format('YYYY'),
				dateEnd = moment().subtract(7, 'day').endOf('week'),
				endDay = dateEnd.format('D'),
				endMonth = dateEnd.format('MM'),
				endYear = dateEnd.format('YYYY')
			;
			break;
		// Останні 3 тижні
		case '3w':
			var dateStart = moment().subtract(2, 'week').startOf('week'),
				startDay = dateStart.format('D'),
				startMonth = dateStart.format('MM'),
				startYear = dateStart.format('YYYY'),
				dateEnd = moment().subtract(0, 'day').endOf('week'),
				endDay = dateEnd.format('D'),
				endMonth = dateEnd.format('MM'),
				endYear = dateEnd.format('YYYY')
			;
			break;
		// Поточний місяць
		case 'tm':
			var dateStart = moment().startOf('month'),
				startDay = dateStart.format('D'),
				startMonth = dateStart.format('MM'),
				startYear = dateStart.format('YYYY'),
				dateEnd = moment().endOf('month'),
				endDay = dateEnd.format('D'),
				endMonth = dateEnd.format('MM'),
				endYear = dateEnd.format('YYYY')
			;
			break;
		// Попередній місяць
		case 'pm':
			var dateStart = moment().subtract(1, 'month').startOf('month'),
				startDay = dateStart.format('D'),
				startMonth = dateStart.format('MM'),
				startYear = dateStart.format('YYYY'),
				dateEnd = moment().subtract(1, 'month').endOf('month'),
				endDay = dateEnd.format('D'),
				endMonth = dateEnd.format('MM'),
				endYear = dateEnd.format('YYYY')
			;
			break;
		// Останні 3 місяці
		case '3m':
			var dateStart = moment().subtract(2, 'month').startOf('month'),
				startDay = dateStart.format('D'),
				startMonth = dateStart.format('MM'),
				startYear = dateStart.format('YYYY'),
				dateEnd = moment().endOf('month'),
				endDay = dateEnd.format('D'),
				endMonth = dateEnd.format('MM'),
				endYear = dateEnd.format('YYYY')
			;
			break;
		// За замовчуванням - Сьогодні і до кінця місяця
		default:
			var dateStart = moment().subtract(0, 'day'),
				startDay = dateStart.format('D'),
				startMonth = dateStart.format('MM'),
				startYear = dateStart.format('YYYY'),
				dateEnd = moment().subtract(0, 'day').endOf('month'),
				endDay = dateEnd.format('D'),
				endMonth = dateEnd.format('MM'),
				endYear = dateEnd.format('YYYY')
			;
	}

	changeStateSelect('#startday', startDay);
	changeStateSelect('#startmonth', startMonth);
	changeStateSelect('#startyear', startYear);
	changeStateSelect('#endday', endDay);
	changeStateSelect('#endmonth', endMonth);
	changeStateSelect('#endyear', endYear);
}

// Змінити стан HTML елемента "select"
function changeStateSelect(selector, findValue) {
	var $selector = $(selector);
	$selector.find('option').each(function(index, element) {
		if ( element.value == findValue ) {
			$selector.prop('selectedIndex', index);
			return false;
		}
	});	
}

// Копіювання в буфер
function initClipboard() {
	var clipboard = new Clipboard('[data-clipboard]');
	clipboard.on('success', function (e) {
		html_pulse(e.trigger, '<span class="copied">Copied!</span>');
	});
}

// Змінити текст елемента на newtext і повернути назад з імпульсом. elem - ID елемента
function html_pulse( elem, newtext ) {
	$oldtext = $(elem).html();
	$(elem).fadeTo(
		'normal',
		0.01,
		function() {
			$(elem)
			.html(newtext)
			.css('opacity', 1)
			.fadeTo(
				'slow', 1,
				function() {
					$(elem).fadeTo('normal', 0.01, function() { $(elem).html( $oldtext ).css('opacity', 1); });
				}
			);
		}
	);
}