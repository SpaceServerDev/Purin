<?php

namespace SSC\Command\DefaultCommands;

use pocketmine\command\CommandSender;
use pocketmine\command\defaults\VanillaCommand;
use pocketmine\command\utils\CommandException;
use pocketmine\Player;
use pocketmine\Server;
use SSC\main;

class policechatCommand extends VanillaCommand {

	public function __construct() {
		parent::__construct("policechat","警察以上のみ見れる重要事項を送信","/policechat [message]");
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
		if ($permission===0) return false;
		if (!isset($args[0])) return false;
		$name = $sender->getName();
		$chat = implode(" ", $args);
		foreach (Server::getInstance()->getOnlinePlayers() as $player){
			$permission = main::getPlayerData($player->getName())->getNumberPerm();
			if ($permission>=1) $player->sendMessage("<§a{$name}§r>→<§e警察以上全員§r> {$chat}");
		}
		return true;
	}
}