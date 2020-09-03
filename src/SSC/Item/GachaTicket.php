<?php


namespace SSC\Item;


use pocketmine\item\Item;

class GachaTicket implements SpaceServerItem {


	public static function get(int $amount=1):Item {
		$item=Item::get(339,0,$amount);
		$item->setCustomName("§aガチャチケット");
		$nbt=$item->getNamedTag();
		$nbt->setInt("EventGacha",1);
		$item->setNamedTag($nbt);
		return $item;
	}

}