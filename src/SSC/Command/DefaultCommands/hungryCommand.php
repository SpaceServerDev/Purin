<?php


namespace SSC\Command\DefaultCommands;


use pocketmine\command\CommandSender;
use pocketmine\command\defaults\VanillaCommand;
use pocketmine\command\utils\CommandException;

class hungryCommand extends VanillaCommand {

	public function __construct() {
		parent::__construct("hungry","お腹をすかせます","/hungry");
	}

	/**
	 * @param string[] $args
	 *
	 * @return mixed
	 * @throws CommandException
	 */
	public function execute(CommandSender $sender, string $commandLabel, array $args) {
		$sender->setFood(0);
		$sender->sendMessage("[管理AI]腸の中身を取り出しました。");
		return true;
	}
}