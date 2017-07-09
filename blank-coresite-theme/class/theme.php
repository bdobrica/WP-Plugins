<?php
class ST_Theme {
	const NAME = 'Stupul Tau';
	const ASSETS = 'assets';
	const BUFFER = 128;
	const CAPABILITY = 'administrator';
	const MENU = 'menu';

	private $assets;

	public function get ($what = null, $opts = null) {
		switch ((string) $what) {
			case 'assets':
				$out = [];

				$path = dirname (dirname (__FILE__)) . DIRECTORY_SEPARATOR . self::ASSETS . DIRECTORY_SEPARATOR;
				$folders = [ 'js', 'css' ];

				foreach ($folders as $folder) {
					$search_path = $path . $folder;
					if (!is_dir ($search_path)) continue;
					if (($dh = opendir ($search_path)) === FALSE) continue;
					while (($file = readdir ($dh)) !== FALSE) {
						if ($file == '.' || $file == '..') continue;
						$header = $this->get ('header', $search_path . DIRECTORY_SEPARATOR . $file);
						if (is_null ($header)) continue;
						$out[] = [
							'type' => $folder,
							'path' => get_template_directory_uri () . DIRECTORY_SEPARATOR . self::ASSETS . DIRECTORY_SEPARATOR . $folder . DIRECTORY_SEPARATOR . $file,
							'name' => $header['name'] ? : $file,
							'dependencies' => $header['dependencies'] ? explode (',', $header['dependencies']) : [],
							'version' => $header['version'] ? : '0.1',
							'footer' => strtolower($header['footer']) == 'true' ? TRUE : FALSE,
							'media' => $header['media'] ? : 'all',
							'scope' => $header['scope'] ? : ''
							];
						}
					closedir ($dh);
					}

				return $out;
				break;
			case 'header':
				$out = [];

				$state = 0;
				
				if (!file_exists ($opts)) return null;
				if (($fh = fopen ($opts, 'r')) === FALSE) return null;
				
				while ((($line = fgets ($fh, self::BUFFER)) !== FALSE) && ($state < 2)) {
					$line = trim ($line);
					if (strpos ($line, '/*') === 0) { $state = 1; continue; }
					if (strpos ($line, '*/') === 0) break;
					if ($state < 1) continue;
					list ($key, $value) = explode (':', $line);
					$out[str_replace (' ', '_', trim(strtolower($key)))] = trim($value);
					}

				fclose ($fh);
				return $out;
				break;
			case 'content':
				if (!is_array ($opts) && is_string ($opts) && is_numeric ($opts)) {
					$opts = [ 'id' => (int) $opts, 'echo' => TRUE ];
					}
				$page = get_post ($opts);
				$content = apply_filters ('the_content', $page->post_content);
				$content = str_replace (']]>', ']]&gt;', $content);
				if (!$opts['echo']) return $content;
				echo $content;
				break;
			}
		return null;
		}

	public function main_scripts () {
		if (empty ($this->assets)) $this->assets = $this->get ('assets');

		if (!empty ($this->assets))
		foreach ($this->assets as $asset) {
			if ($asset['scope'] != '') continue;
			if ($asset['type'] == 'js') wp_enqueue_script ($asset['name'], $asset['path'], $asset['dependencies'], $asset['version'], $asset['footer']);
			if ($asset['type'] == 'css') wp_enqueue_style ($asset['name'], $asset['path'], $asset['dependencies'], $asset['version'], $asset['media']);
			}
		}

	public function admin_menu () {
		add_menu_page (self::NAME . ' Menu', self::NAME . ' Menu', self::CAPABILITY, ST_Options::PREFIX . self::MENU, [$this, 'admin_page']);

		$options = new ST_Options ();
		$options->register (self::MENU, self::CAPABILITY);
		}

	public function admin_page () {
		}

	public function admin_scripts () {
		if (empty ($this->assets)) $this->assets = $this->get ('assets');

		if (!empty ($this->assets))
		foreach ($this->assets as $asset) {
			if ($asset['scope'] == '') continue;
			if (	$asset['scope'] == '' ||
				$asset['scope'] != 'admin' ||
				((strpos ($assets['scope'], 'page=') === 0) && !(isset ($_GET['page']) && in_array ($_GET['page'], explode (',', substr($assets['scope'], 5)))))
				) continue;
			if (!empty ($asset['dependencies'])) {
				foreach ($asset['dependencies'] as $index => $dependency) {
					if ($dependency != 'media') continue;
					unset ($asset['dependencies'][$index]);
					wp_enqueue_media ();
					}
				}
			if ($asset['type'] == 'js') wp_enqueue_script ($asset['name'], $asset['path'], $asset['dependencies'], $asset['version'], $asset['footer']);
			if ($asset['type'] == 'css') wp_enqueue_style ($asset['name'], $asset['path'], $asset['dependencies'], $asset['version'], $asset['media']);
			}
		}

	public function __construct ($menus = [], $sidebars = []) {
		$this->assets = [];

		remove_action('wp_head', 'rsd_link');
		remove_action('wp_head', 'wlwmanifest_link');
		remove_action('wp_head', 'index_rel_link');
		remove_action('wp_head', 'wp_generator');

		add_action ('wp_enqueue_scripts', [$this, 'main_scripts']);
		add_action ('admin_enqueue_scripts', [$this, 'admin_scripts']);
		add_action ('admin_menu', [$this, 'admin_menu']);
		}

	public function __destruct () {
		}
	}
?>
