<?php


namespace SSC\Form;


use pocketmine\form\Form;
use pocketmine\Player;
use pocketmine\Server;
use SSC\Form\InfoForm\marsInfomation;
use SSC\Form\InfoForm\UpdateInfomationForm;
use SSC\main;
use SSC\PlayerEvent;

class InformationForm implements Form {


	/**
	 * Handles a form response from a player.
	 *
	 * @param Player $player
	 * @param mixed $data
	 *
	 */
	public function handleResponse(Player $player, $data): void {
		if(!is_numeric($data)){
			return;
		}
		/** @var  $playerdata  PlayerEvent*/
		$playerdata=main::getPlayerData($player->getName());
		switch ($data) {
			case 0:
				Server::getInstance()->dispatchCommand($player, "loginbonus");
				return;
			case 1:
				$player->sendForm(new marsInfomation());
				return;
			case 2:
				$player->sendForm(new ApologyForm(main::getMain(), $playerdata));
				return;
			case 3:
				$player->sendForm(new DailyForm($playerdata));
				return;
			case 4:
				$player->sendForm(new UpdateInfomationForm());
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
		$buttons = [
			['text' => "ログインボーナスへ",
				'image' => [
					"type" => "path",
					'data' => "textures/items/magma_cream.png"
				],
			],
			['text' => "火星開放！",
				'image' => [
					"type" => "path",
					'data' => "textures/ui/World.png"
				],
			],
			['text' => "お詫びBOX",
				'image' => [
					"type" => "path",
					'data' => "textures/items/cookie.png"
				],
			],
			['text' => "今日のデイリー",
				'image' => [
					"type" => "path",
					'data' => "textures/items/map_filled.png"
				],
			],
			['text' => "最新のアップデート",
				'image' => [
					"type" => "path",
					'data' => "textures/items/sign.png"
				],
			],
			['text' => "§4infoを終了する",],
		];
		return [
			'type'=>'form',
			'title'=>"§a§lSPACESERVER INFO",
			'content'=>"§aようこそ宇宙サーバーへ！\n§a現在のイベントです。詳しく見たい広告をタッチしてください",
			'buttons'=>$buttons
		];
	}
}