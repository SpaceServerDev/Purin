<?php


namespace SSC\Form;


use pocketmine\form\Form;
use pocketmine\form\FormValidationException;
use pocketmine\item\Item;
use pocketmine\Player;
use SSC\main;
use SSC\PlayerEvent;

class LoginBonusForm implements Form {

	/**
	 * @var Player
	 */
	private $player;

	public function __construct(PlayerEvent $player) {
		$this->player=$player->getPlayer();
	}

	/**
	 * Handles a form response from a player.
	 *
	 * @param mixed $data
	 *
	 * @throws FormValidationException if the data could not be processed
	 */
	public function handleResponse(Player $player, $data): void {
		$playerdata=main::getPlayerData($player->getName());
		if ($data == 0) {
			return;
		} else {
			switch ($playerdata->getLoginBonus()) {
				case 1:
					$itm = "修復クリーム";
					$item = Item::get(378, 0, 1);
					$item->setCustomName("§d修復クリーム");
					break;
				case 2:
					main::getMain()->economyAPI->addMoney($player->getName(), 2500);
					$player->sendMessage("[まま]2500￥渡したよ！ガチャでも引いてね！");
					$playerdata->setBonus(true);
					$player->sendForm(new RepeatLoginbonusForm($playerdata));
					return;
				case 3:
					$itm = "原木5個";
					$item = Item::get(17, 0, 5);
					break;
				case 4:
					$itm = "名札";
					$item = Item::get(421, 0, 1);
					break;
				case 5:
					$player->sendMessage("[まま]赤いチケット渡したよ！");
					$playerdata->addTicket("RED");
					$playerdata->setBonus(true);
					$player->sendForm(new RepeatLoginbonusForm($playerdata));
					return;
				case 6:
					$itm = "あたま";
					$item = Item::get(397, 3, 1);
					break;
				case 7:
					$itm = "怪しい薬";
					$item = Item::get(367, 0, 1);
					$item->setCustomName("§d怪しい薬");
					break;
				case 8:
					$itm = "石炭5個";
					$item = Item::get(263, 0, 5);
					break;
				case 9:
					$item = Item::get(265, 0, 2);
					$itm = "鉄インゴット2個";
					break;
			}
			$player->getInventory()->addItem($item);
			$player->sendMessage("[まま]{$itm}を渡したよ!。§a明日もログインしてね!");
			$playerdata->setBonus(true);
			$player->sendForm(new RepeatLoginbonusForm($playerdata));
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
		$playerdata = main::getPlayerData($this->player->getName());
		$buttons[] = [
			'text' => "受け取らない",
		];
		switch ($playerdata->getLoginBonus()) {
			case 1:
				$itm = "修復クリーム";
				break;
			case 2:
				$itm = "お金2500￥";
				break;
			case 3:
				$itm = "原木5個";
				break;
			case 4:
				$itm = "名札";
				break;
			case 5:
				$itm = "赤いチケット";
				break;
			case 6:
				$itm = "あたま";
				break;
			case 7:
				$itm = "怪しい薬";
				break;
			case 8:
				$itm = "石炭5個";
				break;
			case 9:
				$itm = "鉄インゴット2個";
				break;
		}
		$item = Item::get(263, 0, 64);
		$content = "アイテム枠がいっぱいです。\nログインボーナスを受け取るには\nアイテムを整理してください。\n/loginbonusでこのページに戻れます";
		if ($this->player->getInventory()->canAddItem($item) == true) {
			$buttons[] = [
				'text' => "受け取る",
			];
			$content = "§a今日のボーナス→{$itm}\n\n";
		}
		return[
			'type'=>'form',
			'title'=>'§d§lログインボーナス',
			'content'=>$content,
			'buttons'=>$buttons
		];
	}
}