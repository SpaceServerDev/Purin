<?php


namespace SSC\Form;

use pocketmine\form\FormValidationException;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\Item;
use SSC\PlayerEvent;
use pocketmine\form\Form;
use pocketmine\Player;

class DailyChangeForm implements Form{

	/**
	 * @var PlayerEvent
	 */
	private $pe;

	/**
	 * @var String
	 */
	private $content;

	public function __construct(PlayerEvent $pe ,String $content="") {
		$this->pe=$pe;
		$this->content=$content;
	}

	/**
	 * Handles a form response from a player.
	 *
	 * @param Player $player
	 * @param mixed $data
	 *
	 * @throws FormValidationException if the data could not be processed
	 */
	public function handleResponse(Player $player, $data): void {
		if(!is_numeric($data)){
			return;
		}
		switch ($data){
			case 0:
				if($this->pe->getVar("DAIRY")<12){
					$player->sendMessage("チケットが足りません。");
					return;
				}
				if(!$player->getInventory()->canAddItem(Item::get(1,0,1))){
					$player->sendMessage("インベントリに空きがありません");
					return;
				}
				$item = Item::get(378, 0, 1);
				$item->setCustomName("§d修復クリーム");
				$player->getInventory()->addItem($item);
				$this->pe->removeVar("DAIRY",12);
			break;

			case 1:
				if($this->pe->getVar("DAIRY")<3){
					$player->sendMessage("チケットが足りません。");
					return;
				}
				if(!$player->getInventory()->canAddItem(Item::get(1,0,1))){
					$player->sendMessage("インベントリに空きがありません");
					return;
				}
				$item=Item::get(129,0,1);
				$player->getInventory()->addItem($item);
				$this->pe->removeVar("DAIRY",3);
			break;

			case 2:
				if($this->pe->getVar("DAIRY")<100){
					$player->sendMessage("チケットが足りません。");
					return;
				}
				if(!$player->getInventory()->canAddItem(Item::get(1,0,1))) {
					$player->sendMessage("インベントリに空きがありません");
					return;
				}
				$item = Item::get(278, 0, 1);
				$item->setCustomName("§d恒星の輝きを放つピッケルv2");
				$item->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(15), 4));
				$item->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(17), 10));
				$player->getInventory()->addItem($item);
				$this->pe->removeVar("DAIRY",100);
			break;

			case 3:
				if($this->pe->getVar("DAIRY")<20){
					$player->sendMessage("チケットが足りません。");
					return;
				}
				if(!$player->getInventory()->canAddItem(Item::get(1,0,1))){
					$player->sendMessage("インベントリに空きがありません");
					return;
				}
				$item=Item::get(218,7,1);
				$player->getInventory()->addItem($item);
				$this->pe->removeVar("DAIRY",20);
			break;

			case 4:
				if($this->pe->getVar("DAIRY")<3){
					$player->sendMessage("チケットが足りません。");
					return;
				}
				if(!$player->getInventory()->canAddItem(Item::get(1,0,3))){
					$player->sendMessage("インベントリに空きがありません");
					return;
				}
				$paper=Item::get(339,0,3);
				$paper->setCustomName("§aガチャチケット");
				$nbt=$paper->getNamedTag();
				$nbt->setInt("EventGacha",1);
				$paper->setNamedTag($nbt);
				$player->getInventory()->addItem($paper);
				$this->pe->removeVar("DAIRY",3);
			break;

			case 5:
				if($this->pe->getVar("DAIRY")<2){
					$player->sendMessage("チケットが足りません。");
					return;
				}
				$this->pe->removeVar("DAIRY",2);
				$this->pe->addVar("RED",1);
			break;
		}
		$player->sendMessage("[管理AI]交換が完了しました。");
	}

	/**
	 * Specify data which should be serialized to JSON
	 * @link https://php.net/manual/en/jsonserializable.jsonserialize.php
	 * @return mixed data which can be serialized by <b>json_encode</b>,
	 * which is a value of any type other than a resource.
	 * @since 5.4.0
	 */
	public function jsonSerialize() {
		$ticket=$this->pe->getVar("DAIRY");
		return [
			"type" => "form",
			"title" => "§aデイリーチケット交換所",
			"content" => $this->content."デイリーチケット交換所です。\n現在のチケット所有枚数".$ticket."枚",
			"buttons" => [
				[
					'text' => "修復クリーム\n(デイリーチケット:12枚)",
				],
				[
					'text' => "エメラルド原石\n(デイリーチケット3枚)",
				],
				[
					'text' => "§d恒星の輝きを放つピッケルv2§r\n(デイリーチケット100枚)",
				],
				[
					'text'=> "シュルカーボックス(灰色)\n(デイリーチケット20枚)"
				],
				[
					'text'=>"ガチャチケット３枚\n(デイリーチケット3枚)"
				],
				[
					'text'=>"赤いチケット\n(デイリーチケット2枚)"
				],
			],
		];
	}
}