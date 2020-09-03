<?php


namespace SSC\Form;


use pocketmine\form\Form;
use pocketmine\form\FormValidationException;
use pocketmine\Player;
use SSC\PlayerEvent;

class FishFeedForm implements Form {

	/**
	 * @var Player
	 */
	private $player;

	/**
	 * FishFeedForm constructor.
	 * @param Player $player
	 */
	public function __construct(PlayerEvent $pe) {
		$this->player=$pe->getPlayer();
	}

	/**
	 * @param Player $player
	 * @param mixed $data
	 */

	public function handleResponse(Player $player, $data): void {
		if(!is_numeric($data)){
			return;
		}
		$tag=$player->namedtag;
		$tag->setInt("FishFeed",$data+1);
		//ダイヤ,兎の足(ミミズ),パン,金,
		switch ($data){
			case 0:
				$feed="ダイヤ";//1
			break;
			case 1:
				$feed="金";//2
			break;
			case 2:
				$feed="鉄";//3
			break;
			case 3:
				$feed="ミミズ";//4
			break;
			case 4:
				$feed="パン";//5
			break;
			case 5:
				$feed="怪しい薬";//6
			break;
			case 6:
				$feed="なし";
				$tag->setInt("FishFeed",0);
			break;
			default:
				$feed="ふぃぐりんぐてーぶる";
			break;
		}
		$player->sendMessage("[釣りAI] §a餌を§b{$feed}§aに変更しました");
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
			'type'=>"form",
			'title'=>"§d釣り餌",
			'content'=>"釣り餌を選べます！",
			'buttons'=>[
				['text'=>"ダイヤ"],
				['text'=>"金"],
				['text'=>"鉄"],
				['text'=>"ミミズ"],
				['text'=>"パン"],
				['text'=>"怪しい薬"],
			],

		];
	}
}