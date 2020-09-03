<?php

namespace SSC\Form\ShopForm;

use onebone\economyapi\EconomyAPI;
use pocketmine\form\Form;
use pocketmine\form\FormValidationException;
use pocketmine\Player;

class FirstShopForm implements Form {

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
		if($data===0) {
			//Todo:new ShopSelectForm
			return;
		}
		if (empty($player->getInventory()->getContents())) {
		$player->sendMessage("[管理AI]インベントリにアイテムがありません");
			return;
		}
		$player->sendForm(new SellSelectForm($player,EconomyAPI::getInstance()->myMoney($player->getName())));
	}

	/**
	 * Specify data which should be serialized to JSON
	 * @link https://php.net/manual/en/jsonserializable.jsonserialize.php
	 * @return mixed data which can be serialized by <b>json_encode</b>,
	 * which is a value of any type other than a resource.
	 * @since 5.4.0
	 */
	public function jsonSerialize() {

		 $buttons[] = [
			 'text' => "ものを買う",
		 ];
		 $buttons[] = [
			 'text' => "ものを売る",
		 ];
		 return [
		 	"type" => "form",
			 "title" => "§d§lITEMSHOP.com",
			 "content" => "物の売買ができます\n\n\n",
			 "buttons" => $buttons
		 ];
	}
}