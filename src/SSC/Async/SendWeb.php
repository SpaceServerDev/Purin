<?php

namespace SSC\Async;

use pocketmine\scheduler\AsyncTask;

class SendWeb extends AsyncTask {

	private $name;

	private $money;

	public function __construct(string $name,int $money) {
		$this->name=$name;
		$this->money=$money;
	}

	/**
	 * Actions to execute when run
	 *
	 * @return void
	 */
	public function onRun() {
		$ch = curl_init("https://yurisi.space/playerdata.php");
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
			"name" => $this->name,
			"money" => $this->money,
		]));
		curl_exec($ch);
	}
}