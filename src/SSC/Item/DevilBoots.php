<?php


namespace SSC\Item;


use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\Item;

class DevilBoots implements SpaceServerItem {

	public static function get(int $amount = 1):Item {
		$item = Item::get(751, 0, $amount);
		$item->setCustomName("§4悪魔の靴 -ベルゼブブの靴-");
		$enchantment = Enchantment::getEnchantment(0);
		$item->addEnchantment(new EnchantmentInstance($enchantment, 3));
		$enchantment = Enchantment::getEnchantment(1);
		$item->addEnchantment(new EnchantmentInstance($enchantment, 2));
		$enchantment = Enchantment::getEnchantment(32);
		$item->addEnchantment(new EnchantmentInstance($enchantment, 10));
		$enchantment = Enchantment::getEnchantment(17);
		$item->addEnchantment(new EnchantmentInstance($enchantment, 3));
		$enchantment = Enchantment::getEnchantment(6);
		$item->addEnchantment(new EnchantmentInstance($enchantment, 5));
		return $item;
	}
}