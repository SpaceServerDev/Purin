<?php


namespace SSC\Form;


use onebone\economyapi\EconomyAPI;
use pocketmine\form\Form;
use pocketmine\form\FormValidationException;
use pocketmine\Player;
use pocketmine\Server;

class GachaListForm implements Form {

	/**
	 * @var Player
	 */
	private $player;

	public function __construct(Player $player) {
		$this->player=$player;
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

		switch ($data){
			case 0:
				Server::getInstance()->dispatchCommand($player,"gac normal");
			break;
			case 1:
				Server::getInstance()->dispatchCommand($player,"gac event");
			break;
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
		$buttons=[
			['text' => "ノーマルガチャ",],
			['text' => "星ガチャ",]
		];
		$mymoney=EconomyAPI::getInstance()->myMoney($this->player->getName());
		return [
			'type'=>'form',
			'title'=>'§d§lGACHA STATION',
			'content'=>"§e現在の所持金 → $mymoney ￥\n§aノーマル(1500円)星イベント(2500円)\n§a現在星イベントが開催されてます。\n§aピックアップ武器は§dlobi,discord§aを御覧ください",
			'buttons'=>$buttons
		];
	}
}