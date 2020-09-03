<?php


namespace SSC\Item;


use pocketmine\item\Item;

class Item_RPG7 implements SpaceServerItem {

	public static function get(int $amount = 1) {
		$item = Item::get(280, 0, 1);
		$item->setCustomName("§bRPG7");
		$item->getNamedTag()->setString("gun", "RPG7");
		$serial=GunManager::getSerial();
		$item->getNamedTag()->setString("serial", $serial);
		$item->setLore(["シリアルナンバー:".$serial]);
		$item->getNamedTag()->setString("fullauto", "rocket");
		return $item;
	}
}