<?php


namespace SSC\Gacha\Normal;


use pocketmine\item\Item;
use SSC\Gacha\Gacha;

class NNormalGacha implements Gacha {

	public function turn(): array {
		$meta = (mt_rand(0, 3));
		$item = Item::get(17, $meta, 64);
		return [$item,1,"原木"];
	}
}