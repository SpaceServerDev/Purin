<?php

namespace SSC\Command\DefaultCommands;

use pocketmine\command\CommandSender;
use pocketmine\command\defaults\VanillaCommand;
use pocketmine\command\utils\CommandException;
use pocketmine\Player;
use pocketmine\Server;

class opchatCommand extends VanillaCommand {

	public function __construct() {
		parent::__construct("opchat","Staffのみ見れる重要事項を送信","/opchat [message]");
	}

	/**
	 * @param string[] $args
	 *
	 * @return mixed
	 * @throws CommandException
	 */
	public function execute(CommandSender $sender, string $commandLabel, array $args) {
		if (!$sender instanceof Player) return false;
		if (!$sender->isOp()) return false;
		if (!isset($args[0])) return false;
		$name = $sender->getName();
		$chat = implode(" ", $args);
		foreach (Server::getInstance()->getOnlinePlayers() as $player) if ($player->isOp()) $player->sendMessage("<§a{$name}§r>→<§eop全員§r> {$chat}");
		return true;
	}
}