<?php


namespace SSC\Item;


use pocketmine\item\Item;

class NavigationStick implements SpaceServerItem {

	public static function get(int $amount = 1):Item {
		$item=Item::get(76,0);
		$item->getNamedTag()->setString("navi","navi");
		$item->setCustomName("§aナビゲーター§bコスモ§a");
		$item->setLore(["ナビゲーターのコスモを呼び出せます"]);
		return $item;
	}
}