<?php


namespace SSC\Form\LoginForm;


use pocketmine\form\Form;
use pocketmine\form\FormValidationException;
use pocketmine\item\Item;
use pocketmine\Player;
use pocketmine\Server;
use SSC\Item\NavigationStick;
use SSC\main;

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
		if(main::getMain()->existsToken($data[1])){
			main::getMain()->removeToken($data[1]);
			main::getMain()->playerlist->reload();
			main::getMain()->playerlist->set($player->getName(),(string)$player->getUniqueId()->toString());
			main::getMain()->playerlist->save();
			$player->setImmobile(false);
			$player->sendMessage("[管理AI] §a新規登録ありがとうございます！ ルールを良く読み気持ちのよい生活を心がけましょう！");
			Server::getInstance()->broadcastMessage("[管理AI] ".$player->getName()."のログインが完了しました!");
			$item = Item::get(346, 0, 1);
			$item->setCustomName("§a初心者応援！釣り竿！");
			$item->setLore(["/feedでエサ交換！","/jobで漁師に！","/townでお魚の納品！"]);
			$item2=NavigationStick::get();
			if($player->getInventory()->canAddItem($item)){
				$player->getInventory()->addItem($item);
				$player->getInventory()->addItem($item2);
				$player->sendMessage("§a初心者応援！釣り竿プレゼント中！");
				$player->sendMessage("§b/jobで漁師にチェンジして/townでお魚の納品をすると効率的にお金を稼げるよ！");
			}
			main::getMain()->login[$player->getName()] = 0;
			Server::getInstance()->dispatchCommand($player,"rule");
			return;
		}
		$player->kick("サーバーのホームページにディスコードへ\n参加できるリンクがあります！\nhttp://yurisi.space/\nわからないことがあればツイッターの@Dev_yrsまで！",false);
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
			"text" => "§adiscordで取得したワンタイムパスワードを入力してください！",
		);
		$formdata["content"][] = array(
			"type" => "input",
			"text" => "ここに半角数字で入力してください",
		);
		return $formdata;
	}
}