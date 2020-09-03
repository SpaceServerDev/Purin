<?php


namespace SSC\Command\DefaultCommands;


use pocketmine\command\CommandSender;
use pocketmine\command\defaults\VanillaCommand;
use pocketmine\command\utils\CommandException;

class hidCommand extends VanillaCommand {

	public function __construct() {
		parent::__construct("/hid","手に持ってるアイテムIDを確認します","hid");
	}

	/**
	 * @param string[] $args
	 *
	 * @return mixed
	 * @throws CommandException
	 */
	public function execute(CommandSender $sender, string $commandLabel, array $args) {
		$item=$sender->getInventory()->getItemInHand();
		$id=$item->getId();
		$damage=$item->getDamage();
		$sender->sendMessage("[管理AI] §l§a{$id} : {$damage}");
		return true;
	}
}