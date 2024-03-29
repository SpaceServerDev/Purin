<?php


namespace SSC\Item;


use pocketmine\item\Item;
use SSC\Gun\GunManager;

class Item_RPG7 implements SpaceServerItem {

	public static function get(int $amount = 1):Item {
		$item = Item::get(422, 0, 1);
		$item->setCustomName("§bロケットランチャー");
		$item->getNamedTag()->setString("gun", "RPG7");
		$serial=GunManager::getSerial();
		$item->getNamedTag()->setString("serial", $serial);
		$item->setLore(["シリアルナンバー:".$serial]);
		$item->getNamedTag()->setString("fullauto", "rocket");
		return $item;
	}
}