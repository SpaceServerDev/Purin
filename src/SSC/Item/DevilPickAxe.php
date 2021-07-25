<?php


namespace SSC\Item;


use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\Item;

class DevilPickAxe implements SpaceServerItem {

	public static function get(int $amount = 1):Item {
		$item = Item::get(745, 0, 1);
		$item->setCustomName("§4悪魔のツルハシ -鬼の棍棒-");
		$enchantment = Enchantment::getEnchantment(31);
		$item->addEnchantment(new EnchantmentInstance($enchantment, 10));
		$enchantment = Enchantment::getEnchantment(17);
		$item->addEnchantment(new EnchantmentInstance($enchantment, 10));
		$enchantment = Enchantment::getEnchantment(15);
		$item->addEnchantment(new EnchantmentInstance($enchantment, 3));
		return $item;
	}
}