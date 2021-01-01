<?php

namespace SSC\Gacha\Event;

use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\Item;
use SSC\Gacha\Gacha;
use SSC\Item\RepairCream;

class StarEventGacha implements Gacha {

	/**
	 * @var string
	 */
	private $rare;

	public function __construct(string $rare) {
		$this->rare = $rare;
	}

	public function turn(): array {
		if ($this->rare === "UR") {
			$rand = mt_rand(1, 9);
			switch ($rand) {
				case 1:
					$item = Item::get(278, 0, 1);
					$item->setCustomName("§b木星の力がみなぎる軽いツルハシ(TRAPPIST-1e専用)");
					$enchantment = Enchantment::getEnchantment(15);
					$item->addEnchantment(new EnchantmentInstance($enchantment, 5));
					$enchantment = Enchantment::getEnchantment(17);
					$item->addEnchantment(new EnchantmentInstance($enchantment, 7));
					return[$item,3,"§b木星の力がみなぎる軽いツルハシ"];
				case 2:
					$item = Item::get(278, 0, 1);
					$item->setCustomName("§8土星のガスと塵のツルハシ(TRAPPIST-1e専用)");
					$enchantment = Enchantment::getEnchantment(15);
					$item->addEnchantment(new EnchantmentInstance($enchantment, 4));
					$enchantment = Enchantment::getEnchantment(17);
					$item->addEnchantment(new EnchantmentInstance($enchantment, 7));
					return[$item,3,"§8土星のガスと塵のツルハシ"];
				case 3:
					$item = Item::get(279, 0, 1);
					$item->setCustomName("§b水星のように重い斧(TRAPPIST-1e専用)");
					$enchantment = Enchantment::getEnchantment(15);
					$item->addEnchantment(new EnchantmentInstance($enchantment, 5));
					$enchantment = Enchantment::getEnchantment(17);
					$item->addEnchantment(new EnchantmentInstance($enchantment, 7));
					return[$item,3,"§b水星のように重い斧"];
				case 4:
					$item = Item::get(277, 0, 1);
					$item->setCustomName("§c火星の嵐の力(TRAPPIST-1e専用)");
					$enchantment = Enchantment::getEnchantment(15);
					$item->addEnchantment(new EnchantmentInstance($enchantment, 4));
					$enchantment = Enchantment::getEnchantment(17);
					return[$item,3,"§c火星の嵐の力"];
				case 5:
					$item = Item::get(277, 0, 1);
					$item->setCustomName("§b地球を感じる奇跡のシャベル(TRAPPIST-1e専用)");
					$enchantment = Enchantment::getEnchantment(15);
					$item->addEnchantment(new EnchantmentInstance($enchantment, 5));
					$enchantment = Enchantment::getEnchantment(17);
					$item->addEnchantment(new EnchantmentInstance($enchantment, 7));
					return[$item,3,"§b地球を感じる奇跡のシャベル"];
				case 6:
					$item = Item::get(277, 0, 1);
					$item->setCustomName("§8冥王星の如く小さめのシャベル(TRAPPIST-1e専用)");
					$enchantment = Enchantment::getEnchantment(15);
					$item->addEnchantment(new EnchantmentInstance($enchantment, 1));
					$enchantment = Enchantment::getEnchantment(17);
					$item->addEnchantment(new EnchantmentInstance($enchantment, 10));
					return[$item,3,"§8冥王星の如く小さめのシャベル"];
				case 7:
					$item = Item::get(278, 0, 1);
					$item->setCustomName("§8月のような静寂のツルハシ(TRAPPIST-1e専用)");
					$enchantment = Enchantment::getEnchantment(15);
					$item->addEnchantment(new EnchantmentInstance($enchantment, 1));
					$enchantment = Enchantment::getEnchantment(17);
					$item->addEnchantment(new EnchantmentInstance($enchantment, 7));
					return[$item,3,"§8月のような静寂のツルハシ"];
				case 8:
					$item = Item::get(278, 0, 1);
					$item->setCustomName("§b海王星の氷のツルハシ(TRAPPIST-1e専用)");
					$enchantment = Enchantment::getEnchantment(15);
					$item->addEnchantment(new EnchantmentInstance($enchantment, 1));
					$enchantment = Enchantment::getEnchantment(17);
					$item->addEnchantment(new EnchantmentInstance($enchantment, 7));
					return[$item,3,"§b海王星の氷のツルハシ"];
				case 9:
					$item = Item::get(277, 0, 1);
					$item->setCustomName("§4天王星の縦の環のシャベル(TRAPPIST-1e専用)");
					$enchantment = Enchantment::getEnchantment(15);
					$item->addEnchantment(new EnchantmentInstance($enchantment, 1));
					$enchantment = Enchantment::getEnchantment(17);
					$item->addEnchantment(new EnchantmentInstance($enchantment, 7));
					return[$item,3,"§4天王星の縦の環のシャベル"];
			}
		}else{
			switch (mt_rand(1,4)) {
				case 1:
					$item = Item::get(444, 0, 1);
					$item->setCustomName("§a聖天使の翼");
					$enchantment = Enchantment::getEnchantment(0);
					$item->addEnchantment(new EnchantmentInstance($enchantment, 3));
					return[$item,4,"§d聖天使の翼"];
				case 2:
					$item = Item::get(261, 0, 1);
					$item->setCustomName("§bジムのスナイパーライフルv2");
					$enchantment = Enchantment::getEnchantment(17);
					$item->addEnchantment(new EnchantmentInstance($enchantment, 10));
					return[$item,4,"§bジムのスナイパーライフルv2"];
				/*case 3:
					$item = Item::get(261, 0, 1);
					$item->setCustomName("§aTANBOの第三の目");
					$enchantment = Enchantment::getEnchantment(17);
					$item->addEnchantment(new EnchantmentInstance($enchantment, 10));
					return[$item,4,"§aTANBOの第三の目"];*/
				case 3:
					$item = Item::get(397, 3, 1);
					$item->setCustomName("§aエフェクト防具頭：暗視");
					return[$item,4,"§aエフェクト防具頭：暗視"];
				case 4:
					$item = RepairCream::get();
					return [$item,4,"§a§l修§b復§cク§dリ§eー§5ム§r"];

			}
		}
		return [];
	}
}
