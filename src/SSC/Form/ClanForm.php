<?php


namespace SSC\Form;


use pocketmine\form\Form;
use pocketmine\form\FormValidationException;
use pocketmine\Player;
use SSC\PlayerEvent;

class ClanForm implements Form {


	/**
	 * @var PlayerEvent
	 */
	private $pe;

	/**
	 * ClanForm constructor.
	 * @param PlayerEvent $pe
	 */
	public function __construct(PlayerEvent $pe) {
		$this->pe=$pe;
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
		if(!isset($data[0])){
			$player->sendMessage("[管理AI]§4名前が入力されていません");
			return;
		}
		if ($data[0] === null) {
			return;
		}
		if ($data[0] === "") {
			$player->sendMessage("[管理AI]§4名前が入力されていません");
			return;
		}
		if(mb_strlen($data[0])>6){
			$player->sendMessage("[管理AI]クラン名の文字は６文字以内です");
			return;
		}
		$this->pe->setClan($data[0]);
		$player->sendMessage("[管理AI] クラン{$data[0]}に加入しました！");
		$this->pe->setTagName($this->pe->getTagName());
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
			"type" => "custom_form",
			"title" => "§dクラン",
			"content" => [
							["type"=>"input",
							"text"=>"クラン名を入力してください。"],

			],
		];
	}
}