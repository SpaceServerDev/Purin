<?php

namespace SSC\Gacha\Event;

use pocketmine\item\Item;
use SSC\Gacha\Gacha;

class NormalRareEventGacha implements Gacha {

	public function turn(): array {
		$meta = (mt_rand(0, 3));
		$item = Item::get(17, $meta, 64);
		return [$item,1,"原木"];
	}
}