<?php


namespace SSC\Command\DefaultCommands;


use pocketmine\command\CommandSender;
use pocketmine\command\defaults\VanillaCommand;
use pocketmine\command\utils\CommandException;
use SSC\main;

class kakinCommand extends VanillaCommand {

	public function __construct() {
		parent::__construct("kakin","","/kakin [set|remove] [name] ([amount])");
	}

	/**
	 * @param string[] $args
	 *
	 * @return mixed
	 * @throws CommandException
	 */
	public function execute(CommandSender $sender, string $commandLabel, array $args) {
		if ($sender->isOp()) {
			if (!isset($args[0])) {
				$sender->sendMessage(self::getUsage());
				return false;
			}
			if (!isset($args[1])) {
				$sender->sendMessage(self::getUsage());
				return false;
			}
			if ($args[0] === "set") {
				if (!isset($args[2]) or !is_numeric($args[2])) {
					$sender->sendMessage(self::getUsage());
					return false;
				}
				$amount = 0;
				if (main::getMain()->kakin->exists($args[1])) {
					$amount = main::getMain()->kakin->get($args[1]);
				}
				main::getMain()->kakin->set($args[1], $args[2] + $amount);
				main::getMain()->kakin->save();
				main::getMain()->dkakin->set($args[1], time());
				main::getMain()->dkakin->save();
				$sender->sendMessage("[管理AI]§a{$args[1]}さんを課金者に追加しました！");
				return true;
			} elseif ($args[0] === "remove") {
				if (main::getMain()->kakin->exists($args[1])) {
					main::getMain()->kakin->remove($args[1]);
					main::getMain()->kakin->save();
					$sender->sendMessage("[管理AI]§a{$args[1]}さんを課金者から削除しました！");
				}
				return true;
			}
			$sender->sendMessage(self::getUsage());
			return false;
		}
	}
}