<?php

namespace SSC\Form;

use pocketmine\form\Form;
use pocketmine\form\FormValidationException;
use pocketmine\Player;

class OptionForm implements Form{

	/**
	 * Handles a form response from a player.
	 *
	 * @param mixed $data
	 *
	 * @throws FormValidationException if the data could not be processed
	 */
	public function handleResponse(Player $player, $data): void {
		if(!is_numeric($data)) return;

		switch($data) {
			case 0:
				$player->sendForm(new JumpOptionForm());
				break;
			case 1:
				$player->sendForm(new WarpOptionForm());
				break;
			case 2:
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
			"type" => "form",
			"title" => "§aオプション",
			"content" => "項目を指定してください。",
			"buttons" => [
				[
					'text' => "ジャンプしたときのエフェクトを変える",//0
				],
				[
					'text' => "ワープ時のエフェクト",//1
				],
				[
					'text' => "やめる",//2
				],

			],
		];
	}
}


class JumpOptionForm implements Form {

	/**
	 * Handles a form response from a player.
	 *
	 * @param mixed $data
	 *
	 * @throws FormValidationException if the data could not be processed
	 */
	public function handleResponse(Player $player, $data): void {
		// TODO: Implement handleResponse() method.
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
			"title" => "§aジャンプエフェクトオプション",
			"content" => "項目を指定してください。",
			"buttons" => [
				[
					'text' => "やめる",//0
				],
				[
					'text' => "なし",//1
				],
				[
					'text' => "てすと",//2
				],

			],
		];
	}
}

class WarpOptionForm implements Form {

	/**
	 * Handles a form response from a player.
	 *
	 * @param mixed $data
	 *
	 * @throws FormValidationException if the data could not be processed
	 */
	public function handleResponse(Player $player, $data): void {
		// TODO: Implement handleResponse() method.
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
			"title" => "§aワープエフェクトオプション",
			"content" => "項目を指定してください。",
			"buttons" => [
				[
					'text' => "やめる",//0
				],
				[
					'text' => "なし",//1
				],
				[
					'text' => "てすと",//2
				],

			],
		];
	}
}

