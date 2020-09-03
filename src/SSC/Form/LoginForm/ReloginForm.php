<?php


namespace SSC\Form\LoginForm;


use pocketmine\form\Form;
use pocketmine\form\FormValidationException;
use pocketmine\Player;
use SSC\Form\InformationForm;
use SSC\main;

class ReloginForm implements Form {

	/**
	 * @var string
	 */
	private $label;

	public function __construct($str="§aあなたのIPアドレスが変更されたようです。\nセキュリティのため、パスワードを入力してログインしてください\n\n") {
		$this->label=$str;
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
			$player->sendForm(new self());
			return;
		}
		if($data[1]===null){
			$player->sendForm(new self());
			return;
		}
		$name=strtolower($player->getName());
		$hasha = MD5($data[1]);
		$hashb = hash('sha256', 'SpaceServer' . $hasha . $name);
		if(main::getMain()->password->get($name)==$hashb){
			$player->sendMessage("ログインが完了しました！");
			$player->sendForm(new InformationForm());
			main::getMain()->login[$name]=0;
			main::getMain()->playerlist->reload();
			main::getMain()->playerlist->set($player->getName(),(string)$player->getUniqueId()->toString());
			main::getMain()->playerlist->save();
			main::getPlayerData($player->getName())->setIP();
			main::getPlayerData($player->getName())->save();
			$player->setImmobile(false);
			return;
		}
		$player->sendForm(new self("パスワードが間違っています\n\n"));
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
			"text" => $this->label,
		);
		$formdata["content"][] = array(
			"type" => "input",
			"text" => "ここに半角英数字で入力してください",
		);
		return $formdata;
	}
}