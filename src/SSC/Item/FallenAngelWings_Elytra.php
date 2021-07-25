<?php


namespace SSC\Item;


use pocketmine\item\Item;

class FallenAngelWings_Elytra implements SpaceServerItem {
	public static function get(int $amount = 1): Item {
		$item = Item::get(444, 0, 1);
		$item->setCustomName("§a堕天使の翼");
		return $item;
	}
}