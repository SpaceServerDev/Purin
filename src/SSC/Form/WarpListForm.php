<?php


namespace SSC\Form;


use pocketmine\form\Form;
use pocketmine\form\FormValidationException;
use pocketmine\Player;
use SSC\main;

class WarpListForm implements Form {

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
		switch ($data) {
			case 0:
				$cmd = "spawn";
				break;
			case 1:
				$cmd = "space 1";
				break;
			case 2:
				$cmd = "earth";
				break;
			case 3:
				$cmd = "flat";
				break;
			case 4:
				$cmd = "respawn";
				break;
			case 5:
				$cmd = "pvp 1";
				break;
			case 6:
				$cmd = "rule";
				break;
			case 7:
				$cmd = "taucetuse";
				break;
			case 8:
				$cmd = "taucetusf";
				break;
			case 9:
				$cmd = "neptune";
				break;
			case 10:
				$cmd = "mars";
				break;
			case 11:
				$cmd = "sun";
				break;
			case 12:
				$cmd = "blackhole";
				break;
			case 13:
				$cmd = "trappist-1e";
				break;
		}
		main::getMain()->getServer()->dispatchCommand($player, $cmd);
	}

	/**
	 * Specify data which should be serialized to JSON
	 * @link https://php.net/manual/en/jsonserializable.jsonserialize.php
	 * @return mixed data which can be serialized by <b>json_encode</b>,
	 * which is a value of any type other than a resource.
	 * @since 5.4.0
	 */
	public function jsonSerialize() {
		$buttons = [
			['text' => "ロビー",],
			['text' => "宇宙",],
			['text' => "地球",],
			['text' => "人工惑星",],
			['text' => "リスポーン",],
			['text' => "pvp",],
			['text' => "ルール場へ",],
			['text' => "くじら座τ星e",],
			['text' => "くじら座τ星f",],
			['text' => "海王星",],
			['text' => "火星",],
			['text' => "太陽",],
			['text' => "ブラックホール",],
			['text' => "トラピスト1星e",],
		];
		return [
			'type'=>'form',
			'title'=>"ワープ",
			'content'=>"§a選択したところにワープできます\n§a行きたいところを選択してください。",
			'buttons'=>$buttons,
		];
	}
}