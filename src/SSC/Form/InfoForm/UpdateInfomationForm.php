<?php


namespace SSC\Form\InfoForm;


use pocketmine\form\Form;
use pocketmine\form\FormValidationException;
use pocketmine\Player;
use SSC\Form\InformationForm;
use SSC\main;

class UpdateInfomationForm implements Form {

	/**
	 * Handles a form response from a player.
	 *
	 * @param mixed $data
	 *
	 * @throws FormValidationException if the data could not be processed
	 */
	public function handleResponse(Player $player, $data): void {
		if(!is_bool($data)){
			return;
		}
		if($data) {
			$player->sendForm(new InformationForm());
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
		return [
			'type'=>'modal',
			'title'=>"§a§lSPACESERVER INFO",
			'content'=> "アップデート情報\n2020/03/xx更新\n\n§l§aコーディングの見直しをしました\nトラピストで入る経験値やお金が通常のワールドと一緒になりました\nマイワープがより便利になりました。\n/mwでマイワープのフォームを開けます\n/mwコマンドのプレイヤー名を省略することができるようになりました\n(ただしオンラインのプレイヤーに限る)\n\n§l§a物をドロップしないようにする機能を追加！\n§rレベル稼ぎのときや整地を永遠としたい人のために\nサーバー負荷をへらすためにアイテムをドロップしない\nようにする設定を開放しました。\nコマンドは/dropです。",
			'button1'=>"もっと見る",
			'button2'=>"おわる",
		];
	}
}