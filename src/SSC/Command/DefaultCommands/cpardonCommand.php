<?php


namespace SSC\Command\DefaultCommands;


use pocketmine\command\CommandSender;
use pocketmine\command\defaults\VanillaCommand;
use pocketmine\command\utils\CommandException;
use pocketmine\Server;
use SSC\main;


class cpardonCommand extends VanillaCommand {

	public function __construct() {
		parent::__construct("cpardon","ban解除","/cpardon [name]");
	}

	/**
	 * @param string[] $args
	 *
	 * @return mixed
	 * @throws CommandException
	 */
	public function execute(CommandSender $sender, string $commandLabel, array $args) {
		if (!isset($args[0])) return false;
		if ($sender->getName() === "CONSOLE" || $sender->isOp()) {
			$name = $args[0];
			main::getMain()->playerlist->reload();
			if (main::getMain()->playerlist->exists($name)) {
				$cid = main::getMain()->playerlist->get($name);
				if (main::getMain()->isCBan($cid)) {
					main::getMain()->removeCBan($cid);
					Server::getInstance()->broadcastMessage("[管理AI] §4" . $sender->getName() . "が" . $args[0] . "の接続禁止解除処理を実行しました");
					main::getMain()->getServer()->getNameBans()->remove($name);
				} else {
					$sender->sendMessage("[管理AI]{$args[0]}はClientBanされていません");
				}

			} else {
				$sender->sendMessage("[管理AI]{$args[0]}のデータがありません");
			}
		}
		return true;
	}
}