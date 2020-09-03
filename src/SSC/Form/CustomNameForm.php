<?php


namespace SSC\Form;


use pocketmine\form\Form;
use pocketmine\form\FormValidationException;
use pocketmine\item\Item;
use pocketmine\Player;

class CustomNameForm implements Form {

	/**
	 * @var Item
	 */
	private $item;

	/**
	 * CustomNameForm constructor.
	 * @param Item $item
	 */
	public function __construct(Item $item) {
		$this->item=$item;
	}

	/**
	 * Handles a form response from a player.
	 *
	 * @param mixed $data
	 *
	 * @throws FormValidationException if the data could not be processed
	 */
	public function handleResponse(Player $player, $data): void {
		if ($data[1] === null) {
			return;
		}
		if ($data[1] === "") {
			$player->sendMessage("[工務AI]§4名前が入力されていません");
			return;
		}
		$item = $this->item;
		$player->getInventory()->removeItem($item);
		if ($player->isOp()) {
			$item->setCustomName($data[1]);
		} else {
			$check = array("§1", "§2", "§3", "§4", "§5", "§6", "§7", "§8", "§9", "§a", "§b", "§c", "§d", "§e", "§f", "§k", "§l", "§m", "§n", "§o", "§r");
			$string = str_replace($check, "", $data[1]);
			$item->setCustomName("§f" . $string);
		}
		$player->getInventory()->addItem($item);
		$item = Item::get(421, 0, 1);
		$player->getInventory()->removeItem($item);
		$player->sendMessage("[工務AI]変更しました。");
		return;
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
			'type'=>'custom_form',
			'title'=>'かすたむねえむ',
			'content'=>[["type" => "label",
						"text" => "今持っているアイテムの名前を変えます。\n§c§o一部特殊アイテムは名前を変えると機能しなくなります\n色変えは使えません",],
						["type" => "input",
						"text" => "名前",]],
		];
	}
}