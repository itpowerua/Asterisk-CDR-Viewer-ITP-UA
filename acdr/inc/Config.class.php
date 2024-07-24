<?php

/**
* Статичний клас використання файлу конфігурації
*
* @param $path Шлях до конфіг-файлу
* @param $default Повертається значення за замовчуванням, якщо параметра в конфігурації немає
*
*/

class Config {
	/** @var string */
    protected static $path = './inc/config.php';
	/** @var array */
    protected static $data;
	/** @var array */
    protected static $cache = array();
	/** @var */
    protected static $default = null;
	
	/**
	* Ініціалізація конфіг-файлу
	*
	* @return array Масив параметрів
	*/
	private static function init() {
		if ( !isset(self::$data) ) {
			if ( is_file(self::$path) ) {
				self::$data = require_once self::$path;
			} else {
				exit('File '.self::$path.' not exists'.PHP_EOL);
			}
		}		
		return self::$data;
	}
	
	/**
	* Задати шлях до конфіг-файлу
	*
	* @param string $path Шлях до конфіг-файлу
	*
	* @return boolean TRUE Якщо вдалося встановити новий шлях до конфіг-файлу
	*/
	public static function setPath($path) {
		if ( isset($path) && is_file($path) ) {
			self::$path = $path;
			return true;
		}
		return false;
	}

	/**
	* Отримати значення параметра
	*
	* @param string $key Параметр, значення якого необхідно отримати
	* @param $default Значення, що повертається, якщо такий параметр відсутній
	*
	* @return Значення конфіга
	*/
    public static function get($key, $default = null) {
		self::$default = $default;
		if (self::exists($key)) {
			return self::$cache[$key];
		}
    }
	
	/**
	* Встановити значення параметра
	*
	* @param string $key Параметр, значення якого необхідно встановити
	* @param $value Значення, яке потрібно встановити для параметра
	*
	* @return void
	*/	
    public static function set($key, $value) {
        $segs = explode('.', $key);
        $data = &self::$data;
        $cacheKey = '';
        while ($part = array_shift($segs)) {
            if ($cacheKey != '') {
                $cacheKey .= '.';
            }
            $cacheKey .= $part;
            if (!isset($data[$part]) && count($segs)) {
                $data[$part] = array();
            }
            $data = &$data[$part];
            // Видалити старий кеш
            if (isset(self::$cache[$cacheKey])) {
                unset(self::$cache[$cacheKey]);
            }
            // Видалити старий кеш у масиві
            if (count($segs) == 0) {
                foreach (self::$cache as $cacheLocalKey => $cacheValue) {
                    if (substr($cacheLocalKey, 0, strlen($cacheKey)) === $cacheKey) {
                        unset(self::$cache[$cacheLocalKey]);
                    }
                }
            }
        }
        self::$cache[$key] = $data = $value;
    }	
	
	/**
	* Чи існує такий параметр
	*
	* @param string $key Параметр, значення якого необхідно перевірити
	* @param $default Значення, що повертається, якщо такий параметр відсутній
	*
	* @return boolean TRUE Якщо такий параметр існує
	*/
    public static function exists($key, $default = null) {
		self::$default = $default;
        if (isset(self::$cache[$key])) {
            return true;
        }
        $segments = explode('.', $key);
        $data = self::init();
        foreach ($segments as $segment) {
            if (array_key_exists($segment, $data)) {
                $data = $data[$segment];
                continue;
            } else {
                return self::$default;
            }
        }
        self::$cache[$key] = $data;
        return true;		
    }
	
	/**
	* Отримати всі параметри
	*
	* @return array Всі параметри
	*/	
    public static function all() {
        return self::init();
    }
	
}