<?php


namespace SSC\Form\LoginForm;


use pocketmine\form\Form;
use pocketmine\form\FormValidationException;
use pocketmine\Player;

class CheckPasswordForm implements Form {

	/**
	 * Handles a form response from a player.
	 *
	 * @param mixed $data
	 *
	 * @throws FormValidationException if the data could not be processed
	 */
	public function handleResponse(Player $player, $data): void {
		if($data===null){
			$player->sendForm(new self);
			return;
		}
		if($data[1]===null){
			$player->sendForm(new self);
			return;
		}
		if($data[1]==="うちゅう"){
			$player->sendForm(new SetPasswordForm());
			return;
		}
		$player->kick("サーバーのホームページを読んできてください！\nルールのページにパスワードが書いてあります！\nhttp://yurisi.space/",false);
	}

	/**
	 * Specify data which should be serialized to JSON
	 * @link https://php.net/manual/en/jsonserializable.jsonserialize.php
	 * @return mixed data which can be serialized by <b>json_encode</b>,
	 * which is a value of any type other than a resource.
	 * @since 5.4.0
	 */
	public function jsonSerialize() {
		$formdata["type"] = "custom_form";
		$formdata["title"] = "§a§lSPACESERVER LOGINSYSTEM";
		$formdata["content"][] = array(
			"type" => "label",
			"text" => "§aホームページのルールのにあるサーバーに参加するためのパスワードを入力してください",
		);
		$formdata["content"][] = array(
			"type" => "input",
			"text" => "ここにひらがなで入力してください",
		);
		return $formdata;
	}
}