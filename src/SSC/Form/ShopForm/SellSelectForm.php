<?php

namespace SSC\Form\ShopForm;

use pocketmine\form\Form;
use pocketmine\form\FormValidationException;
use pocketmine\item\Item;
use pocketmine\Player;

class SellSelectForm implements Form {

	/**
	 * @var mixed
	 */
	private $money;

	/**
	 * @var Player
	 */
	private $player;

	private $data;

	/**
	 * SellSelectForm constructor.
	 * @param $money
	 */
	public function __construct(Player $player,$money) {
		$this->player=$player;
		$this->money=$money;
	}

	/**
	 * Handles a form response from a player.
	 *
	 * @param mixed $data
	 *
	 * @throws FormValidationException if the data could not be processed
	 */
	public function handleResponse(Player $player, $data): void {
		// TODO: Implement handleResponse() method.
	}

	/**
	 * Specify data which should be serialized to JSON
	 * @link https://php.net/manual/en/jsonserializable.jsonserialize.php
	 * @return mixed data which can be serialized by <b>json_encode</b>,
	 * which is a value of any type other than a resource.
	 * @since 5.4.0
	 */
	public function jsonSerialize() {
		$register = array();
		foreach ($this->player->getInventory()->getContents() as $inv) {
			$in = $inv->getName();
			if (!in_array($in, $register, true)) {
				$register[] = $in;
				$buttons[] = [
					"text" => $in,
				];
				$this->data[] += $inv;
			}
		}
		return ["type" => "form",
				"title" => "§d§lITEMSHOP.com",
				"content" => "売りたいアイテムを選択してください\n§a現在の所持金 → §e{$this->money}￥\n\n",
				"buttons" => $buttons
			   ];
	}
}