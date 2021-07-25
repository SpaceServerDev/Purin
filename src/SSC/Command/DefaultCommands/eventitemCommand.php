<?php


namespace SSC\Command\DefaultCommands;


use pocketmine\command\CommandSender;
use pocketmine\command\defaults\VanillaCommand;
use pocketmine\command\utils\CommandException;
use pocketmine\item\Item;
use pocketmine\Player;

class eventitemCommand extends VanillaCommand {

	public function __construct() {
		parent::__construct("eventitem","イベント限定アイテムを64個生成します(OP限定)","/eventitem イベント名");
	}

	/**
	 * @param Player $sender
	 * @param string $commandLabel
	 * @param string[] $args
	 *
	 * @return mixed
	 */
	public function execute(CommandSender $sender, string $commandLabel, array $args) {
		if(!$sender->isOp()) return true;
		if(!isset($args[0])) return true;
		$item=Item::get(340,0,64);
		$item->setCustomName($args[0]." 参加賞！");
		$item->setLore(["参加ありがとうございました！"]);
		if($sender->getInventory()->canAddItem($item)) $sender->getInventory()->addItem($item);
		return true;
	}
}