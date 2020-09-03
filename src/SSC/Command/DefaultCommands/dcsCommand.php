<?php


namespace SSC\Command\DefaultCommands;



use pocketmine\command\CommandSender;
use pocketmine\command\defaults\VanillaCommand;
use pocketmine\command\utils\CommandException;
use pocketmine\Server;

class dcsCommand extends VanillaCommand {

	public function __construct() {
		parent::__construct("dcs","","");
	}

	/**
	 * @param string[] $args
	 *
	 * @return mixed
	 * @throws CommandException
	 */
	public function execute(CommandSender $sender, string $commandLabel, array $args) {
		if ($sender->isOp()) {
			if (!isset($args[0]) or !isset($args[1])) {
				return false;
			}
			Server::getInstance()->broadcastMessage("§a[Discord]§b" . $args[0] . "§l§f>> §r" . $args[1]);
			return true;
		}
		return true;
	}
}