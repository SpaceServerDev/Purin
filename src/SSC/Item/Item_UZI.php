<?php


namespace SSC\Item;


use pocketmine\item\Item;
use SSC\Gun\GunManager;

class Item_UZI implements SpaceServerItem {

	public static function get(int $amount = 1):Item {
		$item = Item::get(409, 0, 1);
		$item->setCustomName("§bサブマシンガン");
		$item->getNamedTag()->setString("gun", "UZI");
		$serial=GunManager::getSerial();
		$item->getNamedTag()->setString("serial", $serial);
		$item->setLore(["シリアルナンバー:" . $serial]);
		$item->getNamedTag()->setString("fullauto", "yes");
		return $item;
	}
}