<?php


namespace SSC\Command\DefaultCommands;


use pocketmine\command\CommandSender;
use pocketmine\command\defaults\VanillaCommand;
use pocketmine\command\utils\CommandException;
use pocketmine\level\Position;
use pocketmine\Server;

class townCommand extends VanillaCommand {

	public function __construct() {
		parent::__construct("town","街にワープ","/town");
	}

	/**
	 * @param string[] $args
	 *
	 * @return mixed
	 * @throws CommandException
	 */
	public function execute(CommandSender $sender, string $commandLabel, array $args) {
		$sender->teleport(new Position(214,4,274,Server::getInstance()->getLevelByName("world")));
		if ($sender->getGamemode() == 0) {
			$sender->getPlayer()->setAllowFlight(false);
			$sender->setFlying(false);
		}
		return true;
	}
}