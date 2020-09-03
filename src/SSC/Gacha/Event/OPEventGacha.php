<?php


namespace SSC\Gacha\Event;

use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\Item;
use SSC\PlayerEvent;
use SSC\Gacha\Gacha;

class OPEventGacha implements Gacha{

	/**
	 * @var string
	 */
	private $rare;

	public function __construct(string $rare) {
		$this->rare=$rare;
	}

	public function turn() :array {
		$rand=mt_rand(1,9);
		switch ($rand) {
			case 1:
				$item = Item::get(261, 0, 1);
				$item->setCustomName("§aじむのスナイパーライフル");
				$enchantment = Enchantment::getEnchantment(13);
				$item->addEnchantment(new EnchantmentInstance($enchantment, 1));
				$enchantment = Enchantment::getEnchantment(9);
				$item->addEnchantment(new EnchantmentInstance($enchantment, 2));
				$enchantment = Enchantment::getEnchantment(17);
				$item->addEnchantment(new EnchantmentInstance($enchantment, 10));
				$enchantment = Enchantment::getEnchantment(19);
				$item->addEnchantment(new EnchantmentInstance($enchantment, 2));
				$enchantment = Enchantment::getEnchantment(21);
				$item->addEnchantment(new EnchantmentInstance($enchantment, 1));
				return [$item,3,"§aじむのスナイパーライフル"];
			case 2:
				$item = Item::get(276, 0, 1);
				$item->setCustomName("§d聖剣§asndykmrのペンライト");
				$enchantment = Enchantment::getEnchantment(12);
				$item->addEnchantment(new EnchantmentInstance($enchantment, 1));
				$enchantment = Enchantment::getEnchantment(9);
				$item->addEnchantment(new EnchantmentInstance($enchantment, 2));
				$enchantment = Enchantment::getEnchantment(17);
				$item->addEnchantment(new EnchantmentInstance($enchantment, 10));
				$enchantment = Enchantment::getEnchantment(19);
				$item->addEnchantment(new EnchantmentInstance($enchantment, 2));
				return [$item,3,"§d聖剣 sndykmrのペンライト"];
			case 3:
				$item = Item::get(310, 0, 1);
				$item->setCustomName("§ccreepeがくれなかったマーライオンの兜");
				$enchantment = Enchantment::getEnchantment(0);
				$item->addEnchantment(new EnchantmentInstance($enchantment, 1));
				$enchantment = Enchantment::getEnchantment(2);
				$item->addEnchantment(new EnchantmentInstance($enchantment, 2));
				$enchantment = Enchantment::getEnchantment(17);
				$item->addEnchantment(new EnchantmentInstance($enchantment, 10));
				$enchantment = Enchantment::getEnchantment(1);
				$item->addEnchantment(new EnchantmentInstance($enchantment, 2));
				return [$item,3,"§ccreepeがくれなかったマーライオンの兜"];
			case 4:
				$item = Item::get(311, 0, 1);
				$item->setCustomName("§ccreepeがくれなかったマーライオンの体");
				$enchantment = Enchantment::getEnchantment(0);
				$item->addEnchantment(new EnchantmentInstance($enchantment, 1));
				$enchantment = Enchantment::getEnchantment(2);
				$item->addEnchantment(new EnchantmentInstance($enchantment, 2));
				$enchantment = Enchantment::getEnchantment(17);
				$item->addEnchantment(new EnchantmentInstance($enchantment, 10));
				$enchantment = Enchantment::getEnchantment(1);
				$item->addEnchantment(new EnchantmentInstance($enchantment, 2));
				return [$item,3,"§ccreepeがくれなかったマーライオンの体"];
			case 5:
				$item = Item::get(312, 0, 1);
				$item->setCustomName("§c§ccreepeがくれなかったマーライオンの足");
				$enchantment = Enchantment::getEnchantment(0);
				$item->addEnchantment(new EnchantmentInstance($enchantment, 1));
				$enchantment = Enchantment::getEnchantment(2);
				$item->addEnchantment(new EnchantmentInstance($enchantment, 2));
				$enchantment = Enchantment::getEnchantment(17);
				$item->addEnchantment(new EnchantmentInstance($enchantment, 10));
				$enchantment = Enchantment::getEnchantment(1);
				$item->addEnchantment(new EnchantmentInstance($enchantment, 2));
				return [$item,3,"§ccreepeがくれなかったマーライオンの足"];
			case 6:
				$item = Item::get(313, 0, 1);
				$item->setCustomName("§c§ccreepeがくれなかったマーライオンの足首");
				$enchantment = Enchantment::getEnchantment(0);
				$item->addEnchantment(new EnchantmentInstance($enchantment, 1));
				$enchantment = Enchantment::getEnchantment(2);
				$item->addEnchantment(new EnchantmentInstance($enchantment, 2));
				$enchantment = Enchantment::getEnchantment(17);
				$item->addEnchantment(new EnchantmentInstance($enchantment, 10));
				$enchantment = Enchantment::getEnchantment(1);
				$item->addEnchantment(new EnchantmentInstance($enchantment, 2));
				return [$item,3,"§ccreepeがくれなかったマーライオンの足首"];
			case 7:
				$item = Item::get(278, 0, 1);
				$item->setCustomName("§eKaminariの雷が出そうなくらい早く掘れるボロピッケル");
				$enchantment = Enchantment::getEnchantment(15);
				$item->addEnchantment(new EnchantmentInstance($enchantment, 5));
				$enchantment = Enchantment::getEnchantment(16);
				$item->addEnchantment(new EnchantmentInstance($enchantment, 3));
				$enchantment = Enchantment::getEnchantment(17);
				$item->addEnchantment(new EnchantmentInstance($enchantment, 10));
				return [$item,3,"§eKaminariの雷が出そうなくらい早く掘れるボロピッケル"];
			case 8:
				$item = Item::get(277, 0, 1);
				$item->setCustomName("§aよもぎの穴掘りぐらい早く掘れる肉球");
				$enchantment = Enchantment::getEnchantment(15);
				$item->addEnchantment(new EnchantmentInstance($enchantment, 5));
				$enchantment = Enchantment::getEnchantment(17);
				$item->addEnchantment(new EnchantmentInstance($enchantment, 10));
				return [$item,3,"§aよもぎの穴掘りぐらい早く掘れる肉球"];
			case 9:
				$item = Item::get(279, 0, 1);
				$item->setCustomName("§dりりの亡き猫ルナとの思い出の爪");
				$enchantment = Enchantment::getEnchantment(15);
				$item->addEnchantment(new EnchantmentInstance($enchantment, 5));
				$enchantment = Enchantment::getEnchantment(17);
				$item->addEnchantment(new EnchantmentInstance($enchantment, 10));
				return [$item,3,"§dりりの亡き猫ルナとの思い出の爪"];
		}
		return [];
	}
}