<?php


namespace SSC\Form\LoginForm;


use pocketmine\form\Form;
use pocketmine\form\FormValidationException;
use pocketmine\item\Item;
use pocketmine\Player;
use pocketmine\Server;
use SSC\main;

class SetPasswordForm implements Form {

	/**
	 * @var string
	 */
	private $lavel;

	public function __construct($lavel = "§aここに自分が考えたパスワードを入力してください。\nipアドレスが変わった際などのサーバー参加の際に必要になります。\n\nパスワードはハッシュ化されてデーターベースで保存されるため、誰も閲覧することができませんので安心してください。\n\n§4§l間違えがないかよく確認しましょう\n\n"){
		$this->lavel=$lavel;
	}

	/**
	 * Handles a form response from a player.
	 *
	 * @param mixed $data
	 *
	 * @throws FormValidationException if the data could not be processed
	 */
	public function handleResponse(Player $player, $data): void {
		if($data===null){
			$player->sendForm(new self("§aここに自分が考えたパスワードを入力してください。\n\n\nipアドレスが変わった際などのサーバー参加の際に必要になります。\n\nパスワードはハッシュ化されてデーターベースで保存されるため、誰も閲覧することができませんので安心してください。\n\n§4§l間違えがないかよく確認しましょう\n\nパスワードは半角英数字で設定してください"));
			return;
		}
		if($data[1]===null){
			$player->sendForm(new self("§aここに自分が考えたパスワードを入力してください。\n\n\nipアドレスが変わった際などのサーバー参加の際に必要になります。\n\nパスワードはハッシュ化されてデーターベースで保存されるため、誰も閲覧することができませんので安心してください。\n\n§4§l間違えがないかよく確認しましょう\n\nパスワードは半角英数字で設定してください"));
			return;
		}
		if($data[1]==""){
			$player->sendForm(new self("§aここに自分が考えたパスワードを入力してください。\n\n\nipアドレスが変わった際などのサーバー参加の際に必要になります。\n\nパスワードはハッシュ化されてデーターベースで保存されるため、誰も閲覧することができませんので安心してください。\n\n§4§l間違えがないかよく確認しましょう\n\nパスワードは半角英数字で設定してください"));
			return;
		}
		if (preg_match(/** @lang text */ "/[^a-zA-Z0-9]/", $data[1])) {
			new self("cパスワードは半角英数字で設定してください");
			return;
		}
		$name=strtolower($player->getName());
		$hasha = MD5($data[1]);
		$hashb = hash('sha256', 'SpaceServer' . $hasha . $name);
		main::getMain()->password->set($player->getName(),$hashb);
		main::getMain()->password->save();
		main::getMain()->playerlist->reload();
		main::getMain()->playerlist->set($player->getName(),(string)$player->getUniqueId()->toString());
		main::getMain()->playerlist->save();
		$player->setImmobile(false);
		$player->sendMessage("[管理AI] パスワードのセットが完了しました。 [\"§a{$data[1]}§f\"] です！");
		$player->sendMessage("[管理AI] §aスクリーンショットしてパスワードを保存してください！");
		Server::getInstance()->broadcastMessage("[管理AI] ".$name."のログインが完了しました!");
		$item = Item::get(346, 0, 1);
		$item->setCustomName("§a初心者応援！釣り竿！");
		$item->setLore(["/feedでエサ交換！","/jobで漁師に！","/townでお魚の納品！"]);
		if($player->getInventory()->canAddItem($item)){
			$player->getInventory()->addItem($item);
			$player->sendMessage("§a初心者応援！釣り竿プレゼント中！");
			$player->sendMessage("§b/jobで漁師にチェンジして/townでお魚の納品をすると効率的にお金を稼げるよ！");
		}
		Server::getInstance()->dispatchCommand($player,"rule");
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
			"text" => $this->lavel,
		);
		$formdata["content"][] = array(
			"type" => "input",
			"text" => "ここに半角英数字で入力してください",
		);
		return $formdata;
	}
}