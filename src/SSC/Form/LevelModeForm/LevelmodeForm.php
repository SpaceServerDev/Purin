<?php


namespace SSC\Form\LevelModeForm;


use pocketmine\form\Form;
use pocketmine\form\FormValidationException;
use pocketmine\Player;
use pocketmine\Server;
use SSC\main;

class LevelmodeForm implements Form {

	/**
	 * @var string
	 */
	private $name;

	public function __construct(string $name) {
		$this->name=$name;
	}

	/**
	 * Handles a form response from a player.
	 *
	 * @param mixed $data
	 *
	 * @throws FormValidationException if the data could not be processed
	 */
	public function handleResponse(Player $player, $data): void {
		if(!is_numeric($data)) return;
		if($data===0){
			Server::getInstance()->dispatchCommand($player,"advancemode");
			return;
		}
		Server::getInstance()->dispatchCommand($player,"expertmode");
		return;

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
			"type" => "form",
			"title" => "§aレベルモード変更",
			"content" => main::getPlayerData($this->name),
			"buttons" => [
				[
					'text' => "アドバンスモードに変更",
				],
				[
					'text' => "エキスパートモードに変更",
				],
			],
		];
	}
}