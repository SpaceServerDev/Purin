<?php


namespace SSC\Gacha\Event;


use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\Item;
use pocketmine\nbt\tag\IntTag;
use SSC\Gacha\Gacha;

class ComradeGacha implements Gacha {
	/**
	 * @var string
	 */
	private $rare;

	public function __construct(string $rare) {
		$this->rare = $rare;
	}

	public function turn(): array {
		if ($this->rare === "UR") {
			$rand = mt_rand(1, 13);
			switch ($rand) {
				case 1:
					$item = Item::get(261, 0, 1);
					$item->setCustomName("§aナマケモノの相手もなまけちゃう弓");
					$enchantment = Enchantment::getEnchantment(17);
					$item->addEnchantment(new EnchantmentInstance($enchantment, 5));
					$item->getNamedTag()->setString("Namakemono","true");
					return [$item,3,"§aナマケモノの相手もなまけちゃう弓"];
				case 2:
					$item = Item::get(276, 0, 1);
					$item->setCustomName("§bサイサナの魔剣（くさいさなのつるぎ）");
					$item->setLore(["なんか臭いこれ"]);
					$enchantment = Enchantment::getEnchantment(17);
					$item->addEnchantment(new EnchantmentInstance($enchantment, 5));
					$item->getNamedTag()->setString("Saisana","true");
					return [$item,3,"サイサナの魔剣"];
				case 3:
					$item = Item::get(310, 0, 1);
					$item->setCustomName("§cたくまっちのあたま");
					$item->setLore(["リンスとヘアオイルがいい匂い♪"]);
					$enchantment = Enchantment::getEnchantment(0);
					$item->addEnchantment(new EnchantmentInstance($enchantment, 5));
					$enchantment = Enchantment::getEnchantment(17);
					$item->addEnchantment(new EnchantmentInstance($enchantment, 7));
					return [$item,3,"§cたくまっちのあたま"];
				case 4:
					$item = Item::get(311, 0, 1);
					$item->setCustomName("§cたくまっちのほんたいうえ");
					$enchantment = Enchantment::getEnchantment(0);
					$item->addEnchantment(new EnchantmentInstance($enchantment, 3));
					$enchantment = Enchantment::getEnchantment(17);
					$item->addEnchantment(new EnchantmentInstance($enchantment, 7));
					return [$item,3,"§aたくまっちの本体(上)"];
				case 5:
					$item = Item::get(312, 0, 1);
					$item->setCustomName("§cたくまっちのほんたいした");
					$enchantment = Enchantment::getEnchantment(0);
					$item->addEnchantment(new EnchantmentInstance($enchantment, 3));
					$enchantment = Enchantment::getEnchantment(17);
					$item->addEnchantment(new EnchantmentInstance($enchantment, 7));
					return [$item,3,"§cたくまっちの本体(下)"];
				case 6:
					$item = Item::get(313, 0, 1);
					$item->setCustomName("§cたくまっちの足(臭い)");
					$item->setLore(["吐き気を催すにおい(リアル)(汚い)"]);
					$enchantment = Enchantment::getEnchantment(0);
					$item->addEnchantment(new EnchantmentInstance($enchantment, 1));
					$enchantment = Enchantment::getEnchantment(17);
					$item->addEnchantment(new EnchantmentInstance($enchantment, 7));
					return [$item,3,"§cたくまっちの足"];
				case 7:
					$item = Item::get(278, 0, 1);
					$item->setCustomName("§aReef§ePickaxe\n§a所有者 : 当ててくれたあなた");
					$enchantment = Enchantment::getEnchantment(15);
					$item->addEnchantment(new EnchantmentInstance($enchantment, 1));
					$enchantment = Enchantment::getEnchantment(17);
					$item->addEnchantment(new EnchantmentInstance($enchantment, 25));
					return [$item,3,"§aReef§ePickaxe"];
				case 8:
					$item = Item::get(261, 0, 1);
					$item->setCustomName("§aビーボのワンちゃん追い打ち弓");
					$enchantment = Enchantment::getEnchantment(20);
					$item->addEnchantment(new EnchantmentInstance($enchantment, 1));
					$enchantment = Enchantment::getEnchantment(17);
					$item->addEnchantment(new EnchantmentInstance($enchantment, 5));
					$item->getNamedTag()->setString("bbo","true");
					return [$item,3,"§aビーボのワンちゃん追い打ち弓"];
				case 9:
					$item = Item::get(261, 0, 1);
					$item->setCustomName("§dゆりしーの愛を伝える弓");
					$enchantment = Enchantment::getEnchantment(19);
					$item->addEnchantment(new EnchantmentInstance($enchantment, 2));
					$enchantment = Enchantment::getEnchantment(17);
					$item->addEnchantment(new EnchantmentInstance($enchantment, 15));
					$item->getNamedTag()->setString("Yurisi_Love","true");
					return [$item,3,"§dゆりしーの愛を伝える弓"];
				case 10:
					$item = Item::get(300, 0, 1);
					$colorcode = 0x00ffdd;
					$item->setNamedTagEntry(new IntTag("customColor", $colorcode));
					$item->setCustomName("§aエフェクト防具腰：火炎耐性");
					$enchantment = Enchantment::getEnchantment(0);
					$item->addEnchantment(new EnchantmentInstance($enchantment, 3));
					$enchantment = Enchantment::getEnchantment(17);
					$item->addEnchantment(new EnchantmentInstance($enchantment, 7));
					return [$item,3,"§aエフェクト防具腰：火炎耐性"];
				case 11:
					$item = Item::get(346, 0, 1);
					$item->setCustomName("§d良さげな釣り竿");
					$enchantment = Enchantment::getEnchantment(17);
					$item->addEnchantment(new EnchantmentInstance($enchantment, 3));
					$item->getNamedTag()->setInt("RareFishingHook",1);
					return [$item,3,"§d良さげな釣り竿"];
				case 12:
					$item = Item::get(395, 0, 1);
					return [$item,3,"§d空っぽの地図"];
				case 13:
					$item = Item::get(395, 2, 1);
					return [$item,3,"§dまっさらな地図"];
			}
		} else {
			$rand = mt_rand(1, 5);
			switch ($rand) {
				case 1:
					$item = Item::get(310, 0, 1);
					$item->setCustomName("§aエフェクト防具頭：暗視");
					$enchantment = Enchantment::getEnchantment(0);
					$item->addEnchantment(new EnchantmentInstance($enchantment, 5));
					$enchantment = Enchantment::getEnchantment(17);
					$item->addEnchantment(new EnchantmentInstance($enchantment, 4));
					return [$item,4,"§aエフェクト防具頭：暗視"];
				case 2:
					$item = Item::get(312, 0, 1);
					$item->setCustomName("§aエフェクト防具腰：火炎耐性");
					$enchantment = Enchantment::getEnchantment(0);
					$item->addEnchantment(new EnchantmentInstance($enchantment, 3));
					$enchantment = Enchantment::getEnchantment(17);
					$item->addEnchantment(new EnchantmentInstance($enchantment, 7));
					return [$item,4,"§aエフェクト防具腰：火炎耐性"];
				case 3:
					$item = Item::get(313, 0, 1);
					$item->setCustomName("§aエフェクト防具足：移動速度上昇");
					$enchantment = Enchantment::getEnchantment(0);
					$item->addEnchantment(new EnchantmentInstance($enchantment, 1));
					$enchantment = Enchantment::getEnchantment(17);
					$item->addEnchantment(new EnchantmentInstance($enchantment, 7));
					return [$item,4,"§aエフェクト防具足：移動速度上昇"];

				case 4:
					$item = Item::get(346, 0, 1);
					$item->setCustomName("§8銀の釣り竿");
					$enchantment = Enchantment::getEnchantment(17);
					$item->addEnchantment(new EnchantmentInstance($enchantment, 3));
					$item->getNamedTag()->setInt("RareFishingHook",2);
					return [$item,4,"§8銀の釣り竿"];

				case 5:
					$item = Item::get(346, 0, 1);
					$item->setCustomName("§e金の釣り竿");
					$enchantment = Enchantment::getEnchantment(17);
					$item->addEnchantment(new EnchantmentInstance($enchantment, 4));
					$item->getNamedTag()->setInt("RareFishingHook",3);
					return [$item,4,"§e金の釣り竿"];
			}
		}
		return [];
	}
}