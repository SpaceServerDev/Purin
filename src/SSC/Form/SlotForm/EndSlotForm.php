<?php


namespace SSC\Form\SlotForm;


use onebone\economyapi\EconomyAPI;
use pocketmine\form\Form;
use pocketmine\form\FormValidationException;
use pocketmine\Player;

class EndSlotForm implements Form {

	/**
	 * @var string
	 */
	private $message;

	/**
	 * @var int
	 */
	private $amount;

	public function __construct(string $message,int $amount) {
		$this->message=$message;
		$this->amount=$amount;
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
		if($data==0){
			$player->sendForm(new SlotFirstForm(EconomyAPI::getInstance()->myMoney($player->getName())));
			return;
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
		return [
			'type'=>'form',
			'title'=>'slot',
			'content'=>$this->message,"\n\n",
			'buttons'=>[[
				'text'=>"もう一度引く！"
			],
			[
				'text'=>'終わる！'
			]]
		];
	}
}