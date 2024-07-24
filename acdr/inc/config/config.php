<?php

return array(
	### Підключення до бази даних
	'db' => array(
		# Тип бази, який підтримується PDO. Наприклад: mysql, pgsql
		'type' => 'mysql',
		# Хост
		'host' => 'localhost',
		# Порт
		'port' => '3306',
		# Користувач
		'user' => 'root',
		# Ім'я бази
		'name' => 'asteriskcdrdb',
		# Пароль
		'pass' => 'Vomi5TLrQ6522RIr',
		# Назва таблиці
		'table' => 'cdr',
		# Дод. опції підключення
		'options' => array(
			//PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
		),
	),
	
	### Системне
	'system' => array(
		### Режим роботи сервера телефонної платформи
		## Якщо 0, то Asterisk + FreeSWITCH. У фільтрах пошуку тощо буде показано, що стосується і Asterisk, і FreeSWITCH
		# Наприклад: У фільтрі пошуку "Статус дзвінка" будуть показані значення і для Asterisk, і для FreeSWITCH
		
		## Якщо 1, то Asterisk. У фільтрах пошуку тощо буде показано тільки те, що стосується Asterisk
		# Наприклад: У фільтрі пошуку "Статус дзвінка" будуть показані значення тільки для Asterisk
		
		## Якщо 2, то FreeSWITCH. У фільтрах пошуку тощо буде показано тільки те, що стосується FreeSWITCH
		# Наприклад: У фільтрі пошуку "Статус дзвінка" будуть показані значення тільки для FreeSWITCH
		'server_mode' => 1,
		
		## Назва стовпця в БД, у якому зберігається назва запису дзвінка
		'column_name' => 'filename',		
		
		## Шлях до папки для тимчасових файлів
		'tmp_dir' => '/tmp',
		
		## Шлях до папки, де знаходяться записи Asterisk. БЕЗ слеша на кінці
		'monitor_dir' => '/var/ftp/mp3',
		
		### Формат зберігання файлів записів Asterisk
		## Якщо 1, то файли записів повинні розподілятися скриптом по папках відповідно до дати "/home/calls/2015/2015-01/2015-01-01-01".
		# Записи за сьогодні знаходяться в "/home/calls", записи за минулі дати в папках відповідно до дати "/home/calls/2015/2015-01/2015-01-01-01"

		## Якщо 2, то файли записів повинні розподілятися скриптом по папках відповідно до дати "/home/calls/2015/12/01".
		# Записи за сьогодні знаходяться в "/home/calls", записи за минулі дати в папках відповідно до дати "/home/calls/2015/12/01"

		# Якщо 3, то файли записів повинні розподілятися по папках Asterisk-ом відповідно до дати "/home/calls/2015/2015-01/2015-01-01".
		# Записи за всі дати знаходяться в папках відповідно до дати "/home/calls/2015/2015-01/2015-01-01"

		## Якщо 4, то файли записів повинні розподілятися по папках Asterisk-ом відповідно до дати "/home/calls/2015/12/01".
		# Записи за всі дати знаходяться в папках відповідно до дати "/home/calls/2015/12/01"
		
		## Якщо 5, то файли записів мають розподілятися по папках Asterisk-ом.
		# Повний або відносний шлях (відносно "monitor_dir") разом із назвою файлу та розширенням мають бути збережені в стовпці "column_name". Наприклад: "2015/2015-01/2015-01-01/in/filename.mp3"

		## Якщо 6, то файли записів мають розподілятися по папках Asterisk-ом.
		# Повний або відносний шлях (відносно "monitor_dir") разом із назвою файлу та БЕЗ розширення мають бути збережені в стовпці "column_name". Наприклад: "2015/2015-01/2015-01-01/in/filename"
		
		## Якщо ін. значення, то всі записи зберігаються в одній папці "/home/calls"
		'storage_format' => 4,
		
		## Розмір файлу в Кілобайтах, більший за який вважається, що файл існує
		'fsize_exists' => 10,
		
		## Формат аудіо, в якому записуються записи дзвінків
		# Плеєр не відтворює WAV в Enternet Explorer. В останніх версіях Firefox і Chrome все працює
		# Наприклад: mp3, wav		
		'audio_format' => 'mp3',
		
		## Відкладена конвертація записів дзвінків. Корисно для зниження навантаження на сервер
		# У цьому режимі Asterisk має записувати записи дзвінків у WAV, потім щодня о 00.01 годині файли з WAV мають бути конвертовані в MP3 за допомогою скрипта (див. у папці docs + Readme.txt)
		# Файли за сьогоднішній день зберігаються в WAV, за минулі дні в MP3. В "audio_format" має бути задано: mp3. У базу в поле 'filename' буде записано ім'я файлу з розширенням wav (ім'я_файлу.wav)
		# Якщо 0 - вимкнути, 1 - увімкнути
		'audio_defconv' => 0,
		
		## Якщо записи дзвінків/факсів через деякий час архівуються, розкоментуйте рядок нижче і вкажіть формат архіву (zip gz rar bz2 і т.д.)
		# Ім'я архіву має бути "ім'я_файлу.mp3.zip". Тобто до імені файлу з бази має бути додано розширення архіву, наприклад: zip
		//'archive_format' => 'zip',
		
		## Роздільник у CSV файлі звіту
		# Зазвичай використовується кома ",". Але за замовчуванням у Microsoft Office для російської мови встановлено роздільник крапка з комою ";"
		'csv_delim' => ';',
		
		## Імена користувачів, яким дозволено доступ до сайту. Працює, тільки якщо налаштована Basic-Auth аутентифікація (htpasswd файл) на веб-сервері
		# Додавати імена користувачів у вигляді масиву. Наприклад: 'admins' => array( 'admin1', 'admin2', 'admin3' );
		# Якщо масив порожній, то дозволено всім. Тобто якщо задано: 'admins' => array( );
		'admins' => array(

		),
		
		## Використані плагіни
		# Якщо плагін не потрібен, закоментувати відповідний рядок
		# Назва плагіна => ім'я файлу
		'plugins' => array(
			'Витрати коштів' => 'my_callrates',
		),
	),

	### Тарифи на дзвінки
	'callrate' => array(
		## Увімкнення / вимкнення функціоналу підрахунку тарифів. Якщо вимкнено, то працюватиме трохи швидше за великої кількості записів у виведенні
		# Якщо 0 - вимкнути, 1 - увімкнути
		'enabled' => 0,
		
		## Нетарифікований інтервал у секундах
		'free_interval' => 3,
		
		## Шлях до CSV файлу з тарифами
		# Задається для розрахунку тарифів під час пошуку в базі та плагіна
		'csv_file' =>  'inc/plugins/my_callrates.csv',
		
		## Назва валюти, яка використовується під час тарифікації
		# Наприклад: Буде показано не "1.29", а "1.29 назва_валюти"."
		'currency' =>  'UAH',
	),
	
	### Відображення
	'display' => array(
		'lookup' => array(
			## URL сервісу інформації про номер
			# Де "%n" буде замінено на номер телефону
			'url' => 'https://zvonit.com.ua/number/' . '%n',
			
			## Мінімальна довжина номера, для якого буде підставлено URL з інфо про номер
			'num_length' => 7,
		),
		
		'main' => array(
			## Кількість записів для показу на сторінці за замовчуванням
			'result_limit' => 10000,
			## Кількість показаних записів, після яких знову буде показана шапка (Дата, Статус...)
			'header_step' => 3000,
			
			### Якщо 0 - вимкнути, 1 - увімкнути
			## Показ без дубльованих записів в Asterisk 13 і вище
			# Щоб працювало правильно, у вашій базі CDR обов'язково має бути колонка "uniqueid". Якщо колонки немає, її необхідно створити, або встановити параметр = 0
			'duphide' => 1,
			
			## Показ кнопки - Відтворення запису дзвінка
			'rec_play' => 1,			
			
			## Показ кнопки - Видалення запису дзвінка
			'rec_delete' => 1,
			
			## Можливість редагувати поле "Коментар" (userfield)
			'userfield_edit' => 1,
			
			## Показ контекстного пункту меню - Видалення рядка з бази
			'entry_delete' => 1,
			
			## Показати Вх. / Вих. канал повністю
			# У колонках Вх. / Вих. канал, Наприклад, замість "SIP" буде показано "SIP/123"
			'full_channel' => 0,
			
			## Показати при наведенні на "Вх. / Вих. канал", канал повністю з його ID
			# При наведенні на колонки Вх. / Вих. канал, у спливаючій підказці, Наприклад, замість "SIP" або "SIP/100" буде показано "SIP/123-00000025"
			'full_channel_tooltip' => 0,			
		),
		
		### Увімкнення / вимкнення показу фільтрів пошуку
		# Якщо 0 - завжди приховувати, 1 - завжди показувати, 2 - показати під час натискання на кнопку "Додаткові фільтри"	
		'search' => array(
			## Хто дзвонив
			'src' => 1,
			## Куди дзвонили
			'dst' => 1,
			## Статус дзвінка
			'disposition' => 1,
			## Тривалість обробки дзвінка
			'billsec' => 2,
			## Тривалість повна
			'duration' => 0,
			## Вхідний канал
			'channel' => 2,
			## Ім'я абонента, що дзвонить
			'clid' => 2,
			## DID (Зовнішній номер)
			'did' => 2,
			## Вихідний канал
			'dstchannel' => 2,
			## Код акаунта
			'accountcode' => 0,
			## Коментар (userfield)
			'userfield' => 2,
			## Додаток
			'lastapp' => 0,
			## Паралельні дзвінки
			'chart_cc' => 2,
			## ASR та ACD (Коефіцієнт відповідей на виклики / Середня тривалість виклику)
			'asr_report' => 1,
			## CSV файл
			'csv' => 2,
			## Графік дзвінків
			'chart' => 2,
			## Витрата хвилин
			'minutes_report' => 1,
		),
		
		### Увімкнення / вимкнення показу деяких колонок
		# Якщо 0 - приховати, 1 - показати
		'column' => array(
			## DID
			'did' => 0,
			## Тривалість очікування відповіді
			'durwait' => 1,				
			## Тривалість обробки дзвінка
			'billsec' => 1,		
			## Тривалість повна (очікування відповіді + обробка дзвінка)
			'duration' => 0,
			## CallerID
			'clid' => 0,
			## Акаунт
			'accountcode' => 0,
			## Тариф
			'callrates' => 0,
			## Напрямок дзвінка
			'callrates_dst' => 0,
			## Вхідний канал
			'channel' => 0,
			## Вихідний канал
			'dstchannel' => 0,
			## Додаток
			'lastapp' => 0,
			## Запис
			'file' => 1,			
			## Коментар (userfield)
			'userfield' => 1,			
		),
	),

	### Параметри сайту
	'site' => array(
		'main' => array(
			## Meta - Title
			'title' => 'Деталізація дзвінків',
			
			## Meta - Description
			'desc' => 'Деталізація дзвінків',
			
			## Meta - Robots
			'robots' => 'noindex, nofollow',
			
			## Текст у шапці
			'head' => 'Деталізація дзвінків',
			
			## Шлях до зображення з вашим логотипом, яке буде показано в шапці замість тексту
			# Якщо потрібно залишити текст, то закоментувати рядок нижче або задати значення ''			
			'logo_path' => '',
			
			## Шлях до основного розділу сайту
			# Щоб стрілка (поруч із текстом або логотипом у шапці) не показувалася, закоментувати рядок нижче або задати значення ''
			'main_section' => '../',
			
			## Мінімальна ширина шаблону сайту
			# Мінімальна ширина стиснення по горизонталі. Наприклад: 900px
			'min_width' => '1024px',
			
			## Максимальна ширина шаблону сайту
			# Максимальна ширина розтягування по горизонталі. Наприклад: 100%, 1200px
			'max_width' => '1400px',
		),
		
		'js' => array(
			## Автовідтворення запису дзвінка. Якщо 0 - вимкнути, 1 - увімкнути
			'player_autoplay' => 1,
			
			## Показ дати запису дзвінка над плеєром. Якщо 0 - приховати, 1 - показати
			'player_title' => 1,
			
			## Символ, який буде додано до Meta-Title сторінки під час відтворення запису дзвінка
			'player_symbol' => '&#9835;&#9835;&#9835;',
			
			## Показ стрілок для швидкої навігації праворуч. Якщо 0 - приховати, 1 - показати
			'scroll_show' => 1,
		),		
	),
	
	### CDN
	# Шляхи до деяких CSS і JS файлів. Можна вказати URL і завантажувати, наприклад, jQuery з Google CDN
	'cdn' => array(
		'css' => array(
			## Tooltips
			'tooltips' => 'img/simptip.min.css',
			## jQuery contextMenu
			'jquery_contextmenu' => 'img/jquery-contextmenu/jquery.contextMenu.min.css',
		),
		
		'js' => array(
			## Плеєр
			'player' => 'img/player.js',
			## Скін для плеєра
			'player_skin' => 'img/player_skin.js',
			## jQuery
			'jquery' => 'img/jquery.min.js',
			## jQuery query object
			'jquery_object' => 'img/jquery.query-object.min.js',
			## Clipboard JS
			'clipboard_js' => 'img/clipboard.min.js',
			## jQuery contextMenu
			'jquery_contextmenu' => 'img/jquery-contextmenu/jquery.contextMenu.min.js',
			## jQuery UI position
			'jquery_ui_position' => 'img/jquery-contextmenu/jquery.ui.position.min.js',
			## Moment JS
			'moment_js' => 'img/moment.js/moment.min.js',
			## Moment JS - Locale RU
			'moment_js_locale' => 'img/moment.js/ru.js',			
		),		
	),	
	

);
