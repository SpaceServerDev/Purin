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
		$cmd=["spawn","space 1","earth","town","flat","pvp 1","rule","taucetuse","taucetusf","neptune","mars","sun","moon","pluto","blackhole","trappist-1e","respawn"];
		main::getMain()->getServer()->dispatchCommand($player, $cmd[$data]);
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
			['text' => "街",],
			['text' => "人工惑星",],
			['text' => "pvp",],
			['text' => "ルール場へ",],
			['text' => "くじら座τ星e",],
			['text' => "くじら座τ星f",],
			['text' => "海王星",],
			['text' => "火星",],
			['text' => "太陽",],
			['text' => "月",],
			['text' => "冥王星",],
			['text' => "ブラックホール",],
			['text' => "トラピスト1星e",]
			,['text' => "リスポーン",],
		];
		return [
			'type'=>'form',
			'title'=>"ワープ",
			'content'=>"§a選択したところにワープできます\n§a行きたいところを選択してください。",
			'buttons'=>$buttons,
		];
	}
}