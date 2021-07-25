<?php


namespace SSC\Item;


use pocketmine\item\Item;
use SSC\Gun\Gun;
use SSC\Gun\GunManager;

class Item_AK47 implements SpaceServerItem {

	public static function get(int $amount = 1):Item {
		$item = Item::get(467, 0, 1);
		$item->setCustomName("§bアサルトライフル");
		$item->getNamedTag()->setString("gun", "AK47");
		$serial=GunManager::getSerial();
		$item->getNamedTag()->setString("serial", $serial);
		$item->setLore(["シリアルナンバー:".$serial]);
		$item->getNamedTag()->setString("fullauto", "yes");
		return $item;
	}
}