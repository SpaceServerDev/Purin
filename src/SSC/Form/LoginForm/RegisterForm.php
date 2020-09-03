<?php


namespace SSC\Form\LoginForm;


use pocketmine\form\Form;
use pocketmine\form\FormValidationException;
use pocketmine\Player;

class RegisterForm implements Form {

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
		switch ($data){
			case 0:
				$player->sendForm(new CheckPasswordForm());
			return;
			case 1:
				$player->kick("サーバーのホームページを読んできてください！\nルールのページにパスワードが書いてあります！\nhttp://yurisi.space/",false);
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
		$buttons[] = [
			'text' => "はい",
		];
		$buttons[] = [
			'text' => "いいえ",
		];
		return [
			"type" => "form",
			"title" => "§a§lSPACESERVER LOGINSYSTEM",
			"content" => "§aようこそ宇宙サーバーへ！\n§aこのサーバーのルール\nhttps://yurisi.space/rule.html\nはお読みになられましたでしょうか。\n\n",
			"buttons" => $buttons
		];
	}
}