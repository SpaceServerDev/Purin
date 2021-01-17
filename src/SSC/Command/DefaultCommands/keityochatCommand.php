<?php

namespace SSC\Command\DefaultCommands;

use pocketmine\command\CommandSender;
use pocketmine\command\defaults\VanillaCommand;
use pocketmine\command\utils\CommandException;
use pocketmine\Player;
use pocketmine\Server;
use SSC\main;

class keityochatCommand extends VanillaCommand {

	public function __construct() {
		parent::__construct("keityochat","警長以上のみ見れる重要事項を送信","/keityouchat [message]");
	}

	/**
	 * @param string[] $args
	 *
	 * @return mixed
	 * @throws CommandException
	 */
	public function execute(CommandSender $sender, string $commandLabel, array $args) {
		if (!$sender instanceof Player) return false;
		$permission = main::getPlayerData($sender->getName())->getNumberPerm();
		if ($permission<1) return false;
		if (!isset($args[0])) return false;
		$name = $sender->getName();
		$chat = implode(" ", $args);
		foreach (Server::getInstance()->getOnlinePlayers() as $player){
			$permission = main::getPlayerData($player->getName())->getNumberPerm();
			if ($permission>=2) $player->sendMessage("<§a{$name}§r>→<§e警長以上全員§r> {$chat}");
		}
		return true;
	}
}