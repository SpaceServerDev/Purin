<?php


namespace SSC\Item;


use pocketmine\item\Item;
use SSC\Gun\GunManager;

class Item_AWM implements SpaceServerItem {

	public static function get(int $amount = 1) {
		$item = Item::get(465, 0, 1);
		$item->setCustomName("§bAWM");
		$item->getNamedTag()->setString("gun", "AWM");
		$serial=GunManager::getSerial();
		$item->getNamedTag()->setString("serial", $serial);
		$item->setLore(["シリアルナンバー:".$serial]);
		$item->getNamedTag()->setString("fullauto", "sniper");
		return $item;
	}
}