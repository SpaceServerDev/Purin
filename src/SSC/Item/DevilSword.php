<?php


namespace SSC\Item;


use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\Item;

class DevilSword implements SpaceServerItem {

	public static function get(int $amount = 1) :Item{
		$item = Item::get(743, 0, $amount);
		$item->setCustomName("§4悪魔の魔剣 -レーヴァテイン-");
		$enchantment = Enchantment::getEnchantment(9);
		$item->addEnchantment(new EnchantmentInstance($enchantment, 3));
		$enchantment = Enchantment::getEnchantment(17);
		$item->addEnchantment(new EnchantmentInstance($enchantment, 2));
		$enchantment = Enchantment::getEnchantment(10);
		$item->addEnchantment(new EnchantmentInstance($enchantment, 10));
		$enchantment = Enchantment::getEnchantment(31);
		$item->addEnchantment(new EnchantmentInstance($enchantment, 3));
		$item->getNamedTag()->setString("DevilSword", "true");
		return $item;
	}
}