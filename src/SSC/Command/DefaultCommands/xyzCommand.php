<?php

namespace SSC\Command\DefaultCommands;

use pocketmine\command\CommandSender;
use pocketmine\command\defaults\VanillaCommand;
use pocketmine\command\utils\CommandException;
use pocketmine\Player;

class xyzCommand extends VanillaCommand {

	public function __construct(string $name) {
		parent::__construct($name, "現在いる座標を表示します");
	}

	/**
	 * @param string[] $args
	 *
	 * @return mixed
	 * @throws CommandException
	 */
	public function execute(CommandSender $sender, string $commandLabel, array $args) {
		if ($sender instanceof Player) {
			$x = $sender->getFloorX();
			$y = $sender->getFloorY();
			$z = $sender->getFloorZ();
			$sender->sendMessage("§aX:" . $x . " §bY:" . $y . "§c Z:" . $z);
		}
		return true;
	}
}