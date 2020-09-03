<?php

namespace SSC\Form\InfoForm;

use pocketmine\form\Form;
use pocketmine\form\FormValidationException;
use pocketmine\Player;
use SSC\Form\InformationForm;
use SSC\main;

class marsInfomation implements Form {

	/**
	 * Handles a form response from a player.
	 *
	 * @param mixed $data
	 *
	 * @throws FormValidationException if the data could not be processed
	 */
	public function handleResponse(Player $player, $data): void {
		if(!is_bool($data)){
			return;
		}
		if($data){
			$player->sendForm(new InformationForm());
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
			'type'=>'modal',
			'title'=>'§a§lSPACESERVER INFO',
			'content'=>"新惑星情報\n火星が開放されました。\nテラコッタがたくさん手に入る惑星です。\n鉱石の配置が変わっています。",
			'button1'=>"もっと見る",
			'button2'=>"おわる"
		];
	}
}