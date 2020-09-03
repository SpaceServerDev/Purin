<?php


namespace SSC\Form\SecretaryForm;


use pocketmine\form\Form;
use pocketmine\form\FormValidationException;
use pocketmine\Player;
use pocketmine\Server;
use SSC\Form\RankForm;
use SSC\Form\WarpListForm;
use SSC\main;

class SecretaryForm implements Form {

	/**
	 * Handles a form response from a player.
	 *
	 * @param mixed $data
	 *
	 * @throws FormValidationException if the data could not be processed
	 */
	public function handleResponse(Player $player, $data): void {
		if(!is_numeric($data)) return;

		switch($data){
			case 0:
				$player->sendForm(new WarpForm());
			break;
			case 1:
				//Todo
			break;
			case 2:
				$player->sendForm(new ShopForm());
			break;
			case 3:
				Server::getInstance()->dispatchCommand($player,"rank");
			break;
			case 4:
				Server::getInstance()->dispatchCommand($player,"gacha");
			break;
			case 5:
				Server::getInstance()->dispatchCommand($player,"hungry");
			break;
			case 6:
				Server::getInstance()->dispatchCommand($player,"loginbonus");
			break;
			case 7:
				$player->sendForm(new RepairForm());
			break;
			case 8:
				$player->sendForm(new MathForm());
			break;
			case 9:
				Server::getInstance()->dispatchCommand($player,"info");
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
		return [
			"type" => "form",
			"title" => "§a秘書 好来める",
			"content" => "秘書です。私にできることなら何でもします！",
			"buttons" => [
				[
					'text' => "ワープする",//0
				],
				[
					'text' => "土地保護をする",//1
				],
				[
					'text' => "銀行、経済関連",//2
				],
				[
					'text' => "宇宙船を強化する",//3
				],
				[
					'text' => "ショップ",//4
				],
				[
					'text' => "ランキング",//5
				],
				[
					'text'=>"ガチャを引く"//6
				],
				[
					'text'=>"空腹にする"//7
				],
				[
					'text' => "ログインボーナス",//8
				],
				[
					'text'=> "物品の修復"//9
				],
				[
					'text' => "数学系",//10
				],
				[
					'text' => "最新情報を見る",//11
				],
				[
					'text' => "自治体一覧",//12
				],
				[
					'text' => "プロフィール",//13
				],
				[
					'text'=>"交換所"//14
				],
				[
					'text' => "設定",//15
				],
			],
		];
	}
}

class WarpForm implements Form{

	/**
	 * Handles a form response from a player.
	 *
	 * @param mixed $data
	 *
	 * @throws FormValidationException if the data could not be processed
	 */
	public function handleResponse(Player $player, $data): void {
		if(!is_numeric($data)) return;

		switch ($data){
			case 0:
				Server::getInstance()->dispatchCommand($player,"warp");
			return;
			case 1:
				Server::getInstance()->dispatchCommand($player,"mw");
			return;
		}
		$player->sendForm(new SecretaryForm());

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
			"title" => "§a秘書 好来める",
			"content" => "ワープリストです！",
			"buttons" => [
				[
					'text' => "ワープリストを表示",//0
				],
				[
					'text' => "マイワープを表示",//1
				],
				[
					'text' => "戻る",//2
				],
			],
		];
	}
}

class ShopForm implements Form{

	/**
	 * Handles a form response from a player.
	 *
	 * @param mixed $data
	 *
	 * @throws FormValidationException if the data could not be processed
	 */
	public function handleResponse(Player $player, $data): void {
		if(!is_numeric($data)) return;

		switch ($data){
			case 0:
				Server::getInstance()->dispatchCommand($player,"shop");
			return;
			case 1:
				Server::getInstance()->dispatchCommand($player,"trade");
			return;
		}
		$player->sendForm(new SecretaryForm());

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
			"title" => "§a秘書 好来める",
			"content" => "ショップリストです！",
			"buttons" => [
				[
					'text' => "ショップを見る",//0
				],
				[
					'text' =>"フリーマーケットをひらく",//1
				],
				[
					'text' => "戻る",//2
				],
			],
		];
	}
}

class RepairForm implements Form{

	/**
	 * Handles a form response from a player.
	 *
	 * @param mixed $data
	 *
	 * @throws FormValidationException if the data could not be processed
	 */
	public function handleResponse(Player $player, $data): void {
		if(!is_numeric($data)) return;

		switch ($data){
			case 0:
				Server::getInstance()->dispatchCommand($player,"repair");
			return;
		}
		$player->sendForm(new SecretaryForm());

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
			"title" => "§a秘書 好来める",
			"content" => "修復機能です！1ダメージ40円で修復できます",
			"buttons" => [
				[
					'text' => "修復します！",//0
				],
				[
					'text' => "戻る",//1
				],
			],
		];
	}
}

class MathForm implements Form{

	/**
	 * Handles a form response from a player.
	 *
	 * @param mixed $data
	 *
	 * @throws FormValidationException if the data could not be processed
	 */
	public function handleResponse(Player $player, $data): void {
		if(!is_numeric($data)) return;

		switch ($data){
			case 0:
				$player->sendForm(new CalcForm());
			return;
			case 1:
				$player->sendForm(new RandForm());
			return;

		}
		$player->sendForm(new SecretaryForm());

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
			"title" => "§a秘書 好来める",
			"content" => "数学系の機能です！",
			"buttons" => [
				[
					'text' => "計算機をつかう！",//0
				],
								[
					'text' => "乱数をつかう！",//1
				],
				[
					'text' => "戻る",//2
				],
			],
		];
	}
}

class CalcForm implements Form{

	/**
	 * Handles a form response from a player.
	 *
	 * @param mixed $data
	 *
	 * @throws FormValidationException if the data could not be processed
	 */
	public function handleResponse(Player $player, $data): void {
		if(!is_numeric($data[1]) and !is_numeric($data[2])) return;
		switch ($data[3]) {
			case 0:
				$calc = "+";
				break;
			case 1:
				$calc = "-";
				break;
			case 2:
				$calc = "*";
				break;
			case 3:
				$calc = "/";
				break;
			default:
				$player->sendForm(new self);
				return;
		}
			Server::getInstance()->dispatchCommand($player,"calc $data[1] $data[2] $calc");
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
			"type" => "custom_form",
			"title" => "§a秘書 好来める",
			"content" => [
				["type" => "label",
				"text" => "計算機です！",],
				["type" => "input",
				"text" => "数字1",],
				["type" => "input",
				"text" => "数字2",],
				["type"=>"dropdown",
				"text"=>"符号",
				"options"=>["+","-","×","÷"]],
			],
		];
	}
}

class RandForm implements Form{

	/**
	 * Handles a form response from a player.
	 *
	 * @param mixed $data
	 *
	 * @throws FormValidationException if the data could not be processed
	 */
	public function handleResponse(Player $player, $data): void {
		if(!is_numeric($data[1]) and !is_numeric($data[2])) return;
		$private="";
		if($data[3]){
			if(isset($data[4])) {
				$private = $data[4];
			}
		}

			Server::getInstance()->dispatchCommand($player,"rand $data[1] $data[2] $private");
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
			"type" => "custom_form",
			"title" => "§a秘書 好来める",
			"content" => [
				["type" => "label",
				"text" => "乱数計算機です！",],
				["type" => "input",
				"text" => "最小値 マイナスも可能",],
				["type" => "input",
				"text" => "最大値",],
				["type" => "toggle",
				"text" => "[オプション]プライベートで送信",],
				["type" => "input",
				"text" => "[オプション]プレイヤー名(自分も可能)",],
			],
		];
	}
}