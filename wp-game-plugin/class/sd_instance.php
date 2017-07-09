<?php
/**
 * Core of SD_*
 */

/**
 * Abstract class for defining connections between objects and files that store data
 *
 * @category Abstract
 * @package SD
 * @subpackage None
 * @copyright Core Security Advisers SRL
 * @author Bogdan Dobrica <bdobrica @ gmail.com>
 * @version 0.1
 *
 */
abstract class SD_Instance {
	const EXT		= '.php';
	/**
	 * Version string a.b.c
	 * a # major release: paradigm changed
	 * b # minor release: added removed methods/properties/altered tables
	 * c # review: fixed bugs in already implemented methods/properties
	 * @var string
	 */
	public static $version = '1.0.0';

	/**
	 * Human redable name of this object
	 * @var string
	 */
	public static $human = 'Instance';

	/**
	 * Db Scheme
	 * @var array
	 */
	public static $scheme = [];
	/**
	 * The attached file, no prefix
	 * @var string
	 */
	public static $T;
	/**
	 * The attached data structure
	 * @var array
	 */
	protected static $K = [];

	/**
	 * The object's database ID
	 * @var int
	 */
	protected $ID;
	/**
	 * The object's data. Represented as a hash table.
	 * @var array
	 */
	protected $data;
	/**
	 * The path to this file
	 * @var string
	 */
	protected $path;
	private $debug;

	public function __construct ($path = null, $data = null) {
		if (is_null ($path) || !is_string ($path) || !is_dir ($path))
			throw new SD_Exception ();

		$path .= DIRECTORY_SEPARATOR . static::$T;

		if (is_string ($data) && file_exists ($path . DIRECTORY_SEPARATOR . $data . static::EXT)) {
			$info = include ($path . DIRECTORY_SEPARATOR . $data . static::EXT);
			if ($info === 1)
				$data = null;
			else {
				$this->ID = $data;
				$data = $info;
				}
			}
	
		$this->path = $path;

		if (empty ($data))
			throw new SD_Exception ();

		if (isset ($data['slug'])) {
			$this->ID = $data['slug'];
			unset ($data['slug']);
			}

		if (!empty ($data) && is_array ($data))
			foreach ($data as $key => $value)
				if (in_array ($key, static::$K))
					$this->data[$key] = $value;
		}

	public function set ($key = null, $value = null) {
		if (is_string ($key)) {
			if (in_array ($key, static::$K))
				$this->data[$key] = $value;
			}
		else
		if (is_array ($key)) {
			foreach ($key as $_k => $_v) {
				if (in_array ($_k, static::$K))
					$this->data[$_k] = $_v;
				}
			}
		$this->save ();
		}

	public function get ($key = null, $opts = null) {
		if (is_null($key)) return $this->ID;
		$slug = static::slug ($key);

		if ($slug == 'self')
			return get_class ($this) . '-' . $this->ID;
		if ($slug == 'keys')
			return static::$K;
		if ($slug == 'class')
			return get_class ($this);
		if ($slug == 'url') {
			$url = $_SERVER['REQUEST_URI'];

			$get = $_GET;
			$get[static::GET] = $this->ID;
			if (isset ($opts) && is_string ($opts) && in_array ($opts, SD_Theme::$A))
				$get[SD_Theme::ACTION] = $opts;

			if (($pos = strpos ($url, '?')) !== FALSE)
				$url = substr ($url, 0, $pos + 1) . http_build_query ($get);
			else
				$url .= '?' . http_build_query ($get);

			return $url;
			}
		if ($slug == 'path')
			return dirname ($this->path);
		if ($slug == 'file_path')
			return $this->path . DIRECTORY_SEPARATOR . $this->ID . static::EXT;
		if ($slug == 'file_path_noext')
			return $this->path . DIRECTORY_SEPARATOR . $this->ID;

		if (isset($this->data[$slug]))
			return $this->data[$slug];

		return $this->ID;
		}

	public function out ($key = null, $opts = null, $callback = null) {
		$content = $this->get ($key, $opts);
		if (!is_null ($callback) && is_callable ($callback))
			$content = call_user_func ($callback, $content);
		echo $content;
		}

	public function save () {
		if (!isset ($this->ID)) {
			if (isset ($this->data['name']) && !empty ($this->data['name']))
				$this->ID = static::slug ($this->data['name']);
			}
		if (!is_dir ($this->path) && !@mkdir ($this->path, 0755, TRUE))
			throw new SD_Exception ();

		if (($fh = fopen ($this->path . DIRECTORY_SEPARATOR . $this->ID . static::EXT, 'w+')) === FALSE) {
			echo "\n\n" . $this->path . DIRECTORY_SEPARATOR . $this->ID . static::EXT . "\n\n";
			throw new SD_Exception ();
			}

		$content = '<' . '?php return ' . var_export ($this->data, true) . ';?' . '>';
		fwrite ($fh, $content);
		fclose ($fh);
		}

	public function remove () {
		$file = $this->path . DIRECTORY_SEPARATOR . $this->ID . static::EXT;
		if (!file_exists ($file)) throw new SD_Exception ();
		if (!@unlink ($file)) throw new SD_Exception ();

		return TRUE;
		}

	public static function slug ($key) {
		return trim (preg_replace('/[^a-z0-9]+/', '_', strtolower(trim($key))), '_');
		}

	public static function scan ($path = null, $filter = null) {
		if (!is_dir ($path)) return [];
		$path .= DIRECTORY_SEPARATOR . static::$T;
		if (!is_dir ($path)) return [];
		if (($dh = opendir ($path)) === FALSE) return [];
		$out = [];

		while (($file = readdir ($dh)) !== FALSE) {
			if ($file[0] == '.') continue;
			if (($pos = strpos ($file, static::EXT)) !== strlen ($file) - 4) continue;
			$data = include ($path . DIRECTORY_SEPARATOR . $file);
			$data = $data === 1 ? [] : $data;
			if (empty ($data)) continue;

			$out[substr($file, 0, $pos)] = $data;
			}

		closedir ($dh);

		return $out;
		}
	};
?>
