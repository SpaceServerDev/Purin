<?php


namespace SSC\Item;


use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\Item;

class DevilHelmet implements SpaceServerItem {

	public static function get(int $amount = 1):Item {
		$item = Item::get(748, 0, $amount);
		$item->setCustomName("§4悪魔の兜 -堕天使ルシファーの兜-");
		$enchantment = Enchantment::getEnchantment(0);
		$item->addEnchantment(new EnchantmentInstance($enchantment, 3));
		$enchantment = Enchantment::getEnchantment(1);
		$item->addEnchantment(new EnchantmentInstance($enchantment, 2));
		$enchantment = Enchantment::getEnchantment(32);
		$item->addEnchantment(new EnchantmentInstance($enchantment, 10));
		$enchantment = Enchantment::getEnchantment(17);
		$item->addEnchantment(new EnchantmentInstance($enchantment, 3));
		$enchantment = Enchantment::getEnchantment(8);
		$item->addEnchantment(new EnchantmentInstance($enchantment, 3));
		return $item;
	}
}