<?php


namespace SSC\Form;

use pocketmine\form\FormValidationException;
use SSC\PlayerEvent;
use pocketmine\form\Form;
use pocketmine\Player;
use pocketmine\item\Item;

class RepeatLoginbonusForm implements Form{

	/**
	 * @var PlayerEvent
	 */
	private $pd;

	/**
	 * RepeatLoginbonusForm constructor.
	 * @param PlayerEvent $pd
	 */
	public function __construct(PlayerEvent $pd) {
		$this->pd=$pd;
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
		if($data===null or !is_numeric($data)){
			return;
		}
		if($data===0){
			return;
		}else if($data===1){
			$amount=$this->pd->getVar("REPEAT")/7;
			$am=$amount*2;
			$paper=Item::get(339,0,$am);
			if($this->pd->getRepeatBonus()){
				return;
			}
			if(!$player->getInventory()->canAddItem($paper)){
				$player->sendMessage("インベントリに空きがありません。");
				return;
			}
			$paper->setCustomName("§aガチャチケット");
			$nbt=$paper->getNamedTag();
			$nbt->setInt("EventGacha",1);
			$paper->setNamedTag($nbt);
			$player->getInventory()->addItem($paper);
			$this->pd->setRepeatBonus();
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
		$buttons[]=['text'=>"次へすすむ"];
		$content="現在".$this->pd->getVar("REPEAT")."日連続ログインです。\n7日連続ログインごとに ガチャ券2枚×週数 枚プレゼントします！\n\n\n";
		if($this->pd->getVar("REPEAT")%7===0) {
			$buttons[]=['text' => "受け取る"];
			$amount=$this->pd->getVar("REPEAT")/7;
			$am=$amount*2;
			$content=$amount."週間連続ログインありがとうございます！\nガチャチケット".$am."枚プレゼントします！\n\n\n\n";
		}
		return [
			"type" => "form",
			"title" => "§d連続ログインボーナス",
			"content" => $content,
			"buttons" => $buttons,
		];
	}
}