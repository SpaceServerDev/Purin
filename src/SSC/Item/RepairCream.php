<?php


namespace SSC\Item;


use pocketmine\item\Item;

class RepairCream implements SpaceServerItem {

	public static function get(int $amount = 1):Item {
		$item = Item::get(378, 0, $amount);
		$item->setCustomName("§d修復クリーム");
		return $item;
	}
}