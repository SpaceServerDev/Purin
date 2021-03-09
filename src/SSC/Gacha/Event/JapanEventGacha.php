<?php


namespace SSC\Gacha\Event;

use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\Item;
use SSC\Gacha\Gacha;
use SSC\Item\FallenAngelWings_Elytra;

class JapanEventGacha implements Gacha{


	/**
	 * @var string
	 */
	private $rare;

	public function __construct(string $rare) {
		$this->rare=$rare;
	}

	public function turn() :array {
		$rand = mt_rand(1, 10);
		switch ($rand) {
			case 1:
				$item = Item::get(261, 0, 1);
				$item->setCustomName("§a天之麻迦古弓（あめのまかこゆみ）");
				$enchantment = Enchantment::getEnchantment(13);
				$item->addEnchantment(new EnchantmentInstance($enchantment, 1));
				$enchantment = Enchantment::getEnchantment(9);
				$item->addEnchantment(new EnchantmentInstance($enchantment, 2));
				$enchantment = Enchantment::getEnchantment(17);
				$item->addEnchantment(new EnchantmentInstance($enchantment, 10));
				$enchantment = Enchantment::getEnchantment(19);
				$item->addEnchantment(new EnchantmentInstance($enchantment, 3));
				return [$item,3,"§a天之麻迦古弓"];
			case 2:
				$item = Item::get(276, 0, 1);
				$item->setCustomName("§a草薙剣（くさなぎのつるぎ）");
				$enchantment = Enchantment::getEnchantment(12);
				$item->addEnchantment(new EnchantmentInstance($enchantment, 3));
				$enchantment = Enchantment::getEnchantment(9);
				$item->addEnchantment(new EnchantmentInstance($enchantment, 2));
				$enchantment = Enchantment::getEnchantment(17);
				$item->addEnchantment(new EnchantmentInstance($enchantment, 10));
				return [$item,3,"§d草薙剣"];
			case 3:
				$item = Item::get(310, 0, 1);
				$item->setCustomName("§c避来矢 (ひらいし)(兜)");
				$enchantment = Enchantment::getEnchantment(0);
				$item->addEnchantment(new EnchantmentInstance($enchantment, 3));
				$enchantment = Enchantment::getEnchantment(2);
				$item->addEnchantment(new EnchantmentInstance($enchantment, 2));
				$enchantment = Enchantment::getEnchantment(17);
				$item->addEnchantment(new EnchantmentInstance($enchantment, 10));
				return [$item,3,"§c避来矢(兜)"];
			case 4:
				$item = Item::get(311, 0, 1);
				$item->setCustomName("§c避来矢 (ひらいし)(胴)");
				$enchantment = Enchantment::getEnchantment(0);
				$item->addEnchantment(new EnchantmentInstance($enchantment, 3));
				$enchantment = Enchantment::getEnchantment(2);
				$item->addEnchantment(new EnchantmentInstance($enchantment, 2));
				$enchantment = Enchantment::getEnchantment(17);
				$item->addEnchantment(new EnchantmentInstance($enchantment, 10));
				$enchantment = Enchantment::getEnchantment(5);
				$item->addEnchantment(new EnchantmentInstance($enchantment, 1));
				return [$item,3,"§a避来矢(胴)"];
			case 5:
				$item = Item::get(312, 0, 1);
				$item->setCustomName("§c避来矢 (ひらいし)(袴)");
				$enchantment = Enchantment::getEnchantment(0);
				$item->addEnchantment(new EnchantmentInstance($enchantment, 3));
				$enchantment = Enchantment::getEnchantment(2);
				$item->addEnchantment(new EnchantmentInstance($enchantment, 2));
				$enchantment = Enchantment::getEnchantment(17);
				$item->addEnchantment(new EnchantmentInstance($enchantment, 10));
				return [$item,3,"§c避来矢(袴)"];
			case 6:
				$item = Item::get(313, 0, 1);
				$item->setCustomName("§c避来矢 (ひらいし)(足)");
				$enchantment = Enchantment::getEnchantment(0);
				$item->addEnchantment(new EnchantmentInstance($enchantment, 3));
				$enchantment = Enchantment::getEnchantment(2);
				$item->addEnchantment(new EnchantmentInstance($enchantment, 2));
				$enchantment = Enchantment::getEnchantment(17);
				$item->addEnchantment(new EnchantmentInstance($enchantment, 10));
				return [$item,3,"§c避来矢(足)"];
			case 7:
				$item = Item::get(278, 0, 1);
				$item->setCustomName("§e朱鷺の紋章のある鶴嘴");
				$enchantment = Enchantment::getEnchantment(15);
				$item->addEnchantment(new EnchantmentInstance($enchantment, 3));
				$enchantment = Enchantment::getEnchantment(17);
				$item->addEnchantment(new EnchantmentInstance($enchantment, 10));
				return [$item,3,"§e朱鷺の紋章のある鶴嘴"];
			case 8:
				$item = Item::get(277, 0, 1);
				$item->setCustomName("§eaktouのイケてる☆シャベル");
				$enchantment = Enchantment::getEnchantment(15);
				$item->addEnchantment(new EnchantmentInstance($enchantment, 3));
				$enchantment = Enchantment::getEnchantment(17);
				$item->addEnchantment(new EnchantmentInstance($enchantment, 10));
				return [$item,3,"§aaktouのイケてる☆シャベル"];
			case 9:
				$item = Item::get(275, 0, 1);
				$item->setCustomName("§a天狗の鉞(てんぐのまさかり)");
				$enchantment = Enchantment::getEnchantment(15);
				$item->addEnchantment(new EnchantmentInstance($enchantment, 5));
				$enchantment = Enchantment::getEnchantment(17);
				$item->addEnchantment(new EnchantmentInstance($enchantment, 15));
				return [$item,3,"§d天狗の鉞(てんぐのまさかり)"];
			case 10:
				return [FallenAngelWings_Elytra::get(),3,"§a堕天使の翼"];
		}
		return [];
	}
}