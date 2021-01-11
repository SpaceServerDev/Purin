<?php


namespace SSC\Item;


use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\Item;

class JimSniperV2 implements SpaceServerItem {

	public static function get(int $amount = 1): Item {
		$item = Item::get(261, 0, 1);
		$item->setCustomName("§bジムのスナイパーライフルv2");
		$enchantment = Enchantment::getEnchantment(17);
		$item->addEnchantment(new EnchantmentInstance($enchantment, 10));
		return $item;
	}
}