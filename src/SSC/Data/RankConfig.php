<?php


namespace SSC\Data;


use pocketmine\Server;
use pocketmine\utils\Config;

class RankConfig extends Config {

	public function __construct(string $file, int $type = Config::YAML, array $default = [], &$correct = null) {
		parent::__construct($file, $type, $default, $correct);
	}

	public function getTopRank(int $amount = 100) {
		$this->reload();
		$rank = array();
		$data = $this->getAll();
		arsort($data);
		$keys = array_keys($data);
		$val = array_values($data);
		for ($i = 0; $i < $amount; $i++) {
			$key = array_shift($keys);
			if (is_null($key)) break;
			$values = array_shift($val);
			if (is_null($values)) break;
			$rank[$key] = $values;
		}

		return $rank;
	}

    public function getPlayerRank(string $player):array {
		if ($this->exists($player)) {
			$data = $this->getAll();
			arsort($data);
			$ary = array_keys($data);
			$i = 0;
			foreach ($ary as $key) {
				if ($key == $player) break;
				$i++;
			}
			return [$i + 1, $this->get($player)];
		}
		return [-1,-1];
	}
}