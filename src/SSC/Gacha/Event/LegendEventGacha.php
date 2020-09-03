<?php

namespace SSC\Gacha\Event;

use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\Item;
use pocketmine\nbt\tag\IntTag;
use SSC\PlayerEvent;
use SSC\Gacha\Gacha;

class LegendEventGacha implements Gacha{

	/**
	 * @var string
	 */
	private $rare;

	public function __construct(string $rare) {
		$this->rare=$rare;
	}

	public function turn() :array {
		if ($this->rare === "UR") {
			$rand = mt_rand(1, 13);
			switch ($rand) {
				case 1:
					$item = Item::get(218, 0, 1);
					return [$item,3,"§bシュルカーボックス"];
				case 2:
					$item = Item::get(298, 0, 1);
					$colorcode = 0x00ffdd;
					$item->setNamedTagEntry(new IntTag("customColor", $colorcode));
					$item->setCustomName("§aエフェクト防具頭：暗視");
					$enchantment = Enchantment::getEnchantment(17);
					$item->addEnchantment(new EnchantmentInstance($enchantment, 20));
					return [$item,3,"§aエフェクト防具頭：暗視"];
				case 3:
					$item = Item::get(299, 0, 1);
					$colorcode = 0x00ffdd;
					$item->setNamedTagEntry(new IntTag("customColor", $colorcode));
					$item->setCustomName("§aエフェクト防具胴：火炎耐性");
					$enchantment = Enchantment::getEnchantment(17);
					$item->addEnchantment(new EnchantmentInstance($enchantment, 20));
					return [$item,3,"§aエフェクト防具胴：火炎耐性"];
				case 4:
					$item = Item::get(300, 0, 1);
					$colorcode = 0x00ffdd;
					$item->setNamedTagEntry(new IntTag("customColor", $colorcode));
					$item->setCustomName("§aエフェクト防具腰：跳躍力上昇");
					$enchantment = Enchantment::getEnchantment(17);
					$item->addEnchantment(new EnchantmentInstance($enchantment, 20));
					return [$item,3,"§aエフェクト防具腰：跳躍力上昇"];
				case 5:
					$item = Item::get(301, 0, 1);
					$colorcode = 0x00ffdd;
					$item->setNamedTagEntry(new IntTag("customColor", $colorcode));
					$item->setCustomName("§aエフェクト防具足：移動速度上昇");
					$enchantment = Enchantment::getEnchantment(17);
					$item->addEnchantment(new EnchantmentInstance($enchantment, 20));
					return[$item,3,"§aエフェクト防具足：移動速度上昇"];
				case 6:
					$item = Item::get(310, 0, 1);
					$item->setCustomName("§eハデスの兜");
					$enchantment = Enchantment::getEnchantment(0);
					$item->addEnchantment(new EnchantmentInstance($enchantment, 1));
					$enchantment = Enchantment::getEnchantment(5);
					$item->addEnchantment(new EnchantmentInstance($enchantment, 2));
					$enchantment = Enchantment::getEnchantment(17);
					$item->addEnchantment(new EnchantmentInstance($enchantment, 10));
					return[$item,3,"§eハデスの兜"];
				case 7:
					$item = Item::get(311, 0, 1);
					$item->setCustomName("§cアレキウスの鎧 上");
					$enchantment = Enchantment::getEnchantment(17);
					$item->addEnchantment(new EnchantmentInstance($enchantment, 10));
					$enchantment = Enchantment::getEnchantment(1);
					$item->addEnchantment(new EnchantmentInstance($enchantment, 3));
					return[$item,3,"§cアレキウスの鎧 上"];
				case 8:
					$item = Item::get(312, 0, 1);
					$item->setCustomName("§cアレキウスの鎧 下");
					$enchantment = Enchantment::getEnchantment(17);
					$item->addEnchantment(new EnchantmentInstance($enchantment, 10));
					$enchantment = Enchantment::getEnchantment(1);
					$item->addEnchantment(new EnchantmentInstance($enchantment, 3));
					return [$item,3,"§cアレキウスの鎧 下"];
				case 9:
					$item = Item::get(313, 0, 1);
					$item->setCustomName("§bヘルメスの靴");
					$enchantment = Enchantment::getEnchantment(0);
					$item->addEnchantment(new EnchantmentInstance($enchantment, 3));
					$enchantment = Enchantment::getEnchantment(5);
					$item->addEnchantment(new EnchantmentInstance($enchantment, 2));
					$enchantment = Enchantment::getEnchantment(17);
					$item->addEnchantment(new EnchantmentInstance($enchantment, 10));
					return[$item,3,"§bヘルメスの靴"];
				case 10:
					$item = Item::get(261, 0, 1);
					$item->setCustomName("§aイチイバル");
					$enchantment = Enchantment::getEnchantment(12);
					$item->addEnchantment(new EnchantmentInstance($enchantment, 1));
					$enchantment = Enchantment::getEnchantment(9);
					$item->addEnchantment(new EnchantmentInstance($enchantment, 2));
					$enchantment = Enchantment::getEnchantment(17);
					$item->addEnchantment(new EnchantmentInstance($enchantment, 10));
					$enchantment = Enchantment::getEnchantment(13);
					$item->addEnchantment(new EnchantmentInstance($enchantment, 1));
					return[$item,3,"§aイチイバル"];
				case 11:
					$item = Item::get(277, 0, 1);
					$item->setCustomName("§aよもぎのつめ");
					$enchantment = Enchantment::getEnchantment(16);
					$item->addEnchantment(new EnchantmentInstance($enchantment, 3));
					$enchantment = Enchantment::getEnchantment(17);
					$item->addEnchantment(new EnchantmentInstance($enchantment, 10));
					return[$item,3,"§aよもぎの爪"];
				case 12:
					$item = Item::get(279, 0, 1);
					$item->setCustomName("§aバトルアックス");
					$enchantment = Enchantment::getEnchantment(9);
					$item->addEnchantment(new EnchantmentInstance($enchantment, 2));
					$enchantment = Enchantment::getEnchantment(15);
					$item->addEnchantment(new EnchantmentInstance($enchantment, 2));
					$enchantment = Enchantment::getEnchantment(17);
					$item->addEnchantment(new EnchantmentInstance($enchantment, 15));
					return[$item,3,"§aバトルアックス"];
				case 13:
					$item = Item::get(421, 0, 1);
					return[$item,3,"§a名札"];
			}
		}else{
			$rand=mt_rand(1,2);
			switch ($rand) {
				case 1:
					$item = Item::get(283, 0, 1);
					$item->setCustomName("§eエクスカリバー");
					$enchantment = Enchantment::getEnchantment(17);
					$item->addEnchantment(new EnchantmentInstance($enchantment, 10));
					return[$item,4,"§eエクスカリバー"];
				case 2:
					$item = Item::get(257, 0, 1);
					$item->setCustomName("§aラッキーな気がする");
					$enchantment = Enchantment::getEnchantment(15);
					$item->addEnchantment(new EnchantmentInstance($enchantment, 1));
					$enchantment = Enchantment::getEnchantment(17);
					$item->addEnchantment(new EnchantmentInstance($enchantment, 10));
					$enchantment = Enchantment::getEnchantment(18);
					$item->addEnchantment(new EnchantmentInstance($enchantment, 1));
					return[$item,4,"§aFortune"];
			}
		}
		return [];
	}
}