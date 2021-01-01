<?php


namespace SSC\Item;


use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\Item;

class DevilBow implements SpaceServerItem {

	public static function get(int $amount = 1):Item {
		$item = Item::get(261, 0, $amount);
		$item->setCustomName("§4悪魔の弓 -サキュバスの長弓-");
		$enchantment = Enchantment::getEnchantment(19);
		$item->addEnchantment(new EnchantmentInstance($enchantment, 3));
		$enchantment = Enchantment::getEnchantment(17);
		$item->addEnchantment(new EnchantmentInstance($enchantment, 3));
		$enchantment = Enchantment::getEnchantment(5);
		$item->addEnchantment(new EnchantmentInstance($enchantment, 3));
		$enchantment = Enchantment::getEnchantment(10);
		$item->addEnchantment(new EnchantmentInstance($enchantment, 10));
		$enchantment = Enchantment::getEnchantment(31);
		$item->addEnchantment(new EnchantmentInstance($enchantment, 3));
		$item->getNamedTag()->setString("DevilBow", "true");
		return $item;
	}
}