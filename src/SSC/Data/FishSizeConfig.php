<?php


namespace SSC\Data;


use pocketmine\Server;
use pocketmine\utils\Config;
use SSC\main;

class FishSizeConfig extends Config {

	public function __construct() {
		parent::__construct(main::getMain()->getDataFolder() . "fishsize.yml", Config::YAML, array(
			"シーラカンス" => array(),
			"メガロドン" => array(),
			"リュウグウノツカイ" => array(),
			"オキナ" => array(),
			"クラーケン" => array(),
			"ダイオウイカ" => array(),
			"赤えい" => array(),
			"カメロケラス" => array(),
			"アスピドケロン" => array(),
			"マグロ" => array(),
			"ホオジロザメ" => array(),
			"カジキ" => array(),
			"シャチ" => array(),
			"ドリアスピス" => array(),
			"エデスタス" => array(),
			"ヘリコプリオン" => array(),
			"リオプレウロドン" => array(),
			"ニューネッシー" => array(),
			"キャディ" => array(),
			"ウナギ" => array(),
			"ホッケ" => array(),
			"ネッシー" => array(),
			"イッシー" => array(),
			"クッシー" => array(),
			"モッシー" => array(),
			"チュッシー" => array(),
			"アッシー" => array(),
			"ナイルパーチ" => array(),
			"ヘラチョウザメ" => array(),
			"オオメジロザメ" => array(),
			"メコンオオナマズ" => array(),
			"デンキウナギ" => array(),
			"アハイア・グランディ" => array(),
			"アマゾンカワイルカ" => array(),
			"アリゲーターガー" => array(),
		));
	}

	public function getTopRank(string $fish,int $amount = 100) {
		$this->reload();
		$rank = array();
		$data = $this->getAll();
		$data=$data[$fish];
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

	public function getPlayerRank(string $fish,string $player): array {
		if (isset($this->get($fish)[$player])) {
			$data = $this->getAll();
			$data=$data[$fish];
			arsort($data);
			$ary = array_keys($data);
			$i = 0;
			foreach ($ary as $key) {
				if ($key == $player) break;
				$i++;
			}
			return [$i + 1, $this->get($fish)[$player]];
		}
		return [-1,0];
	}
}