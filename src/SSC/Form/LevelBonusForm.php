<?php


namespace SSC\Form;


use onebone\economyapi\EconomyAPI;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\Item;
use pocketmine\nbt\tag\IntTag;
use SSC\main;
use SSC\PlayerEvent;
use pocketmine\form\Form;
use pocketmine\form\FormValidationException;
use pocketmine\Player;

class LevelBonusForm implements Form {

	/**
	 * @var PlayerEvent
	 */
	private $pe;

	/**
	 * @var main
	 */
	private $main;

	public function __construct(PlayerEvent $pe,main $main) {
		$this->main=$main;
		$this->pe=$pe;
	}

	/**
	 * Handles a form response from a player.
	 *
	 * @param mixed $data
	 *
	 * @throws FormValidationException if the data could not be processed
	 */
	public function handleResponse(Player $player, $data): void {
		$name=$player->getName();
		if(!is_numeric($data)){
			return;
		}
		switch($data) {
			case 0:
				if(self::islevel(10,$this->pe)) {
					if (isset($this->main->levelbonus->getAll()["10"][$name])) {
						$player->sendMessage("[管理AI]すでにもらっているようです");
						return;
					}
					EconomyAPI::getInstance()->addMoney($name, 3000);
					$player->sendMessage("[管理AI]3000円ボーナスを追加しました！");
					$this->receiveBonus(10, $player);
				}else{
					$player->sendMessage("[管理AI]レベルが足りません");
				}
				break;
			case 1:
				if(self::islevel(50,$this->pe)) {
					if (isset($this->main->levelbonus->getAll()["50"][$name])) {
						$player->sendMessage("[管理AI]すでにもらっているようです");
						return;
					}
					$this->main->economyAPI->addMoney($name, 10000);
					$player->sendMessage("[管理AI]10000円ボーナスを追加しました！");
					$this->receiveBonus(50, $player);
				}else{
					$player->sendMessage("[管理AI]レベルが足りません");
				}
				break;
			case 2:
				if(self::islevel(80,$this->pe)) {
					if (isset($this->main->levelbonus->getAll()["80"][$name])) {
						$player->sendMessage("[管理AI]すでにもらっているようです");
						return;
					}
					$item = Item::get(278, 0, 1);
					if ($player->getInventory()->canAddItem($item) == true) {
						$enchantment = Enchantment::getEnchantment(15);
						$item->addEnchantment(new EnchantmentInstance($enchantment, 2));
						$player->sendMessage("[管理AI]アイテムのボーナスを追加しました！");
						$player->getInventory()->addItem($item);
						$this->receiveBonus(80, $player);
					} else {
						$player->sendMessage("[管理AI]アイテムが入り切りません");
					}
				}else{
					$player->sendMessage("[管理AI]レベルが足りません");
				}
				break;
			case 3:
				return;
			case 4:
				if(self::islevel(150,$this->pe)) {
					if (isset($this->main->levelbonus->getAll()["150"][$name])) {
						$player->sendMessage("[管理AI]すでにもらっているようです");
						return;
					}
					$this->main->economyAPI->addMoney($name, 10000);
					$player->sendMessage("[管理AI]10000円ボーナスを追加しました！");
					$this->receiveBonus(150, $player);
				}else{
					$player->sendMessage("[管理AI]レベルが足りません");
				}
				break;
			case 5:
				if(self::islevel(200,$this->pe)) {
					if (isset($this->main->levelbonus->getAll()["200"][$name])) {
						$player->sendMessage("[管理AI]すでにもらっているようです");
						return;
					}
					$item = Item::get(218, 4, 1);
					if ($player->getInventory()->canAddItem($item) == true) {
						$player->sendMessage("[管理AI]アイテムのボーナスを追加しました！");
						$player->getInventory()->addItem($item);
						$this->receiveBonus(200, $player);
					} else {
						$player->sendMessage("[管理AI]アイテムが入り切りません");
					}
				}else{
					$player->sendMessage("[管理AI]レベルが足りません");
				}
				break;
			case 6:
				if(self::islevel(250,$this->pe)) {
					if (isset($this->main->levelbonus->getAll()["250"][$name])) {
						$player->sendMessage("[管理AI]すでにもらっているようです");
						return;
					}
					$this->main->economyAPI->addMoney($name, 100000);
					$player->sendMessage("[管理AI]100000円ボーナスを追加しました！");
					$this->receiveBonus(250, $player);
				}else{
					$player->sendMessage("[管理AI]レベルが足りません");
				}
				break;
			case 7:
				if(self::islevel(300,$this->pe)) {
					if (isset($this->main->levelbonus->getAll()["300"][$name])) {
						$player->sendMessage("[管理AI]すでにもらっているようです");
						return;
					}
					$item = Item::get(218, 3, 1);
					if ($player->getInventory()->canAddItem($item) == true) {
						$player->sendMessage("[管理AI]アイテムのボーナスを追加しました！");
						$player->getInventory()->addItem($item);
						$this->receiveBonus(300, $player);
					} else {
						$player->sendMessage("[管理AI]アイテムが入り切りません");
					}
				}else{
					$player->sendMessage("[管理AI]レベルが足りません");
				}
				break;
			case 8:
				if(self::islevel(350,$this->pe)) {
					$item = Item::get(298, 0, 1);
					if (isset($this->main->levelbonus->getAll()["350"][$name])) {
						$player->sendMessage("[管理AI]すでにもらっているようです");
						return;
					}
					if ($player->getInventory()->canAddItem($item) == true) {
						$colorcode = 0x8a2be2;
						$item->setNamedTagEntry(new IntTag("customColor", $colorcode));
						$item->setCustomName("§aエフェクト防具頭：暗視");
						$enchantment = Enchantment::getEnchantment(17);
						$item->addEnchantment(new EnchantmentInstance($enchantment, 20));
						$player->getInventory()->addItem($item);
						$player->sendMessage("[管理AI]アイテムのボーナスを追加しました！");
						$this->receiveBonus(350, $player);
					} else {
						$player->sendMessage("[管理AI]アイテムが入り切りません");
					}
				}else{
					$player->sendMessage("[管理AI]レベルが足りません");
				}
				break;
			case 9:
				if(self::islevel(400,$this->pe)) {
					if (isset($this->main->levelbonus->getAll()["400"][$name])) {
						$player->sendMessage("[管理AI]すでにもらっているようです");
						return;
					}
					$item = Item::get(218, 5, 1);
					if ($player->getInventory()->canAddItem($item) == true) {
						$this->main->economyAPI->addMoney($name, 300000);
						$player->sendMessage("[管理AI]アイテムのボーナスを追加しました！");
						$player->getInventory()->addItem($item);
						$this->receiveBonus(400, $player);
					} else {
						$player->sendMessage("[管理AI]アイテムが入り切りません");
					}
				}else{
					$player->sendMessage("[管理AI]レベルが足りません");
				}
				break;
			case 10:
				if(self::islevel(450,$this->pe)) {
					$item = Item::get(278, 0, 1);
					if (isset($this->main->levelbonus->getAll()["450"][$name])) {
						$player->sendMessage("[管理AI]すでにもらっているようです");
						return;
					}
					if ($player->getInventory()->canAddItem($item) == true) {
						$enchantment = Enchantment::getEnchantment(15);
						$item->addEnchantment(new EnchantmentInstance($enchantment, 4));
						$enchantment = Enchantment::getEnchantment(17);
						$item->addEnchantment(new EnchantmentInstance($enchantment, 10));
						$enchantment = Enchantment::getEnchantment(18);
						$item->addEnchantment(new EnchantmentInstance($enchantment, 1));
						$player->getInventory()->addItem($item);
						$player->sendMessage("[管理AI]アイテムのボーナスを追加しました！");
						$this->receiveBonus(450, $player);
					} else {
						$player->sendMessage("[管理AI]アイテムが入り切りません");
					}
				}else{
					$player->sendMessage("[管理AI]レベルが足りません");
				}
				break;
			case 11:
				if(self::islevel(500,$this->pe)) {
					if (isset($this->main->levelbonus->getAll()["500"][$name])) {
						$player->sendMessage("[管理AI]すでにもらっているようです");
						return;
					}
					$item = Item::get(299, 0, 1);
					if ($player->getInventory()->canAddItem($item)) {
						$item->setCustomName("§a伝説の宝具 所有者:" . $name);
						$enchantment = Enchantment::getEnchantment(17);
						$item->addEnchantment(new EnchantmentInstance($enchantment, 20));
						$colorcode = 0xfff8dc;
						$item->setNamedTagEntry(new IntTag("customColor", $colorcode));
						$player->getInventory()->addItem($item);
						$player->sendMessage("[管理AI]アイテムのボーナスを追加しました！");
						$this->receiveBonus(500, $player);
					} else {
						$player->sendMessage("[管理AI]§4空きがなくて渡せませんでした...");
					}
				}else{
					$player->sendMessage("[管理AI]レベルが足りません");
				}
			break;

		}
	}

	/**
	 * Specify data which should be serialized to JSON
	 * @link https://php.net/manual/en/jsonserializable.jsonserialize.php
	 * @return mixed data which can be serialized by <b>json_encode</b>,
	 * which is a value of any type other than a resource.
	 * @since 5.4.0
	 */
	public function jsonSerialize() {
		$buttons[] = [
			'text' => "Lv.10 3000￥",
		];//0
		$buttons[] = [
			'text' => "Lv.50 10000￥",
		];//1
		$buttons[] = [
			'text' => "Lv.80 効率2ダイヤピッケル",
		];//2
		$buttons[] = [
			'text' => "Lv.100 かみんぐすーん",
		];//3
		$buttons[] = [
			'text' => "Lv.150 10000￥",
		];//4
		$buttons[] = [
			'text' => "Lv.200 シュルカーボックス(限定色)",
		];//5
		$buttons[] = [
			'text' => "Lv.250 100000￥",
		];//6
		$buttons[] = [
			'text' => "Lv.300 シュルカーボックス(限定色)",
		];//7
		$buttons[] = [
			'text' => "Lv.350 暗視エフェクト防具！(限定色)",
		];//8
		$buttons[] = [
			'text' => "Lv.400 300000￥ シュルカーボックス",
		];//9
		$buttons[] = [
			'text' => "Lv.450 幸運効率4ダイヤピッケル",
		];//10
		$buttons[] = [
			'text' => "Lv.500 採掘速度強化エフェクト防具",
		];//11
		return [
			"type" => "form",
			"title" => "§d§lLevelReturn",
			"content" => "§aレベルの報酬を受け取れます！\n\n",
			"buttons" => $buttons
		];
	}


	private function receiveBonus(int $bonuslevel,Player $player){
			$config=$this->main->levelbonus->getAll();
			$config[$bonuslevel]+=[$player->getName()=>true];

			$this->main->levelbonus->setAll($config);
			$this->main->levelbonus->save();
	}

	private static function islevel(int $level, PlayerEvent $pe){
		$ad=0;
		if($pe->isAdvanceLevel()) $ad+=500;
		if($pe->isExpertLevel()) $ad+=1500;
		return ($pe->getLevel()+$ad>=$level);
	}
}