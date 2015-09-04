<?php

namespace Parvula\Core;

/**
 * Component manager [@TODO]
 *
 * @package Parvula
 * @version 0.5.0
 * @since 0.5.0
 * @author Fabien Sa
 * @license MIT License
 */
class Component {

	/**
	 * @var string Components folder
	 */
	private static $basePath = 'components/';

	/**
	 * @var array<string, boolean> If the package is loaded
	 */
    private static $isLoaded = [];

    private static $components = [];

	/**
	 * Get the URI of a package from the component folder
	 *
	 * <code>Component::load('jquery'); will return .../components/jquery/dist/jquery.js</code>
	 *
	 * @param string $packageName Package name
	 * @param string ($path) The path to the main source (try to read bower.json if no path)
	 * @return string|boolean The main package source or false if nothing is load
	 */
	public static function load($packageName, $path = null) {
		$packageName = strtolower($packageName);

		if ($path === null) {
			$conf = static::readBowerConf($packageName);
			if (!$conf) {
				return false;
			}
			$path = '/' . $conf->main;
		} else {
			$path = '/' . ltrim($path, '/');
		}

		// $nameFolder = self::parseName($name);

		if (!isset(static::$isLoaded[$packageName])) {
			static::$isLoaded[$packageName] = true;

			return './' . Parvula::getRelativeURIToRoot() . static::$basePath . $packageName . $path;
		}

		return false;
	}

	public static function exists($packageName) {
		return is_readable(static::$basePath . $packageName);
	}

	//TODO
	// If zip -> save in folder ?
	public static function register($name, $file) {
		$name = self::parseName($name);

		if(!file_exists(static::$basePath . $name)) {
			// echo ">> $basePath - $name";
            mkdir($pathname . $name);
			$data = file_get_contents($file);
			file_put_contents($pathname . $name, $data);
		}
	}

	public static function registerCDN($name, $url) {
		$name = self::parseName($name);

		self::$components[$name] = $url;
	}

    public static function loadCDN($name) {
		$name = self::parseName($name);

		if(!isset(self::$isLoaded[$name], self::$components[$name])) {
            $ext = pathinfo(self::$basePath . $name, PATHINFO_EXTENSION);

            self::$isLoaded[$name] = true;

            return $components[$name];
        }
	}

	/**
	 * Read bower configuration (bower.json)
	 * @param string $packageName Package name
	 * @return object|boolean The configuration or false if the package does not exists
	 */
	private static function readBowerConf($packageName) {
		$filePath = static::$basePath . $packageName . '/bower.json';

		if(!is_file($filePath)) {
			return false;
		}

		$bowerJson = file_get_contents($filePath);
		return json_decode($bowerJson);
	}

	private static function parseName($name) {
		$token = explode(':', $name, 2);
		$name = str_replace(' ', '_', strtolower($token[0]));
		$version = 'last';
		if(sizeof($token) === 2) {
			$version = $token[1];
		}

		return $name;
	}


		// private static $bowerPackages = 'https://bower.herokuapp.com/packages/';

		// Component::registerCDN('jquery', 'http://lala.com/jq.js');
		// Component::register('jquery', $plugin . "jq.js"); // no ../.. to avoid hack


	// public static function install($packageName, $source = true) {
	// }

    // unload
    // loadMultiple
}
