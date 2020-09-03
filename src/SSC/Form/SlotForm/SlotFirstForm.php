<?php


namespace SSC\Form\SlotForm;


use onebone\economyapi\EconomyAPI;
use pocketmine\form\Form;
use pocketmine\form\FormValidationException;
use pocketmine\Player;

class SlotFirstForm implements Form {

	private $money;

	public function __construct($money) {
		$this->money=$money;
	}

	/**
	 * Handles a form response from a player.
	 *
	 * @param mixed $data
	 *
	 * @throws FormValidationException if the data could not be processed
	 */
	public function handleResponse(Player $player, $data): void {
		if(!is_numeric($data)){
			return;
		}
		if(EconomyAPI::getInstance()->myMoney($player->getName())<500){
			$player->sendMessage("お金が足りません");
			return;
		}
		$player->sendForm(new RaffleSlotForm());
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
			'text' => "スロットを開始する！",
		];
		return  [
			"type"    => "form",
			"title"   => "§d§lSlot",
			"content" => "スロットを引けます!\n縦横斜めそれぞれ1500￥ずつ手に入ります！\n1回500￥！\n§e現在のお金 : {$this->money}￥\n\n\n",
			"buttons" => $buttons
		];
	}
}