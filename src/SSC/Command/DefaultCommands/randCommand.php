<?php


namespace SSC\Command\DefaultCommands;


use pocketmine\command\CommandSender;
use pocketmine\command\defaults\VanillaCommand;
use pocketmine\command\utils\CommandException;
use pocketmine\Player;
use SSC\main;

class randCommand extends VanillaCommand {

	public function __construct() {
		parent::__construct("rand","乱数を出す。","/rand [min] [max] ([PlayerName])");
	}

	/**
	 * @param string[] $args
	 *
	 * @return mixed
	 * @throws CommandException
	 */
	public function execute(CommandSender $sender, string $commandLabel, array $args) {
		if($sender instanceof Player) {
			if (!isset($args[0])) {
				return false;
			}
			if (!isset($args[1])) {
				return false;
			}
			if (is_numeric($args[0]) === false) {
				return false;
			}
			if (is_numeric($args[1]) === false) {
				return false;
			}
			if ($args[0] > $args[1] || $args[0] === $args[1]) {
				return false;
			}
			$rnd = mt_rand($args[0], $args[1]);
			if (isset($args[2])) {
				if ($sender->getServer()->getPlayer($args[2])) {
					$aiteplayer = $sender->getServer()->getPlayer($args[2]);
					$aitename = $aiteplayer->getName();
					if ($aitename === $sender->getName()) {
						$sender->sendMessage("[管理AI] §a {$args[0]}～{$args[1]} " . $rnd . "を当選させました。");
						return true;
					}
					$sender->sendMessage("[管理AI] §a" . $sender->getName() . "が{$args[0]}～{$args[1]} " . $rnd . "を当選させました。");
					$aiteplayer->sendMessage("[管理AI] §a" . $sender->getName() . "が{$args[0]}～{$args[1]} " . $rnd . "を当選させました。");
					return true;
				}
			} else {
				main::getMain()->getServer()->broadcastMessage("[管理AI] §a" . $sender->getName() . "が{$args[0]}～{$args[1]} " . $rnd . "を当選させました。");
			}
		}
		return true;
	}
}