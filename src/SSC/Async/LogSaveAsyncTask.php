<?php


namespace SSC\Async;


use pocketmine\scheduler\AsyncTask;
use pocketmine\Server;

class LogSaveAsyncTask extends AsyncTask {

	/**
	 * @var false|resource
	 */
	private $resource;

	/**
	 * @var
	 */
	private $file;

	private $key;
	private $value;
	/**
	 * LogSaveAsyncTask constructor.
	 * @param $path
	 * @param int $x
	 * @param int $y
	 * @param int $z
	 * @param string $world
	 * @param int $id
	 * @param int $damage
	 * @param string $name
	 * @param string $eventname
	 */
	public function __construct($path, int $x, int $y, int $z, string $world, int $id, int $damage, string $name, string $eventname) {
		$this->file=$path;
		$this->key="{$x},{$y},{$z},{$world}";
		$this->value="{$id},{$damage},{$name},{$eventname},".date("Y/m/d G:i:s");
	}

	/**
	 * Actions to execute when run
	 *
	 * @return void
	 */
	public function onRun() {
		$parse=$this->resource=yaml_parse_file($this->file);
		$parse[$this->key]=$this->value;
		yaml_emit_file($this->file,$parse);
	}
}