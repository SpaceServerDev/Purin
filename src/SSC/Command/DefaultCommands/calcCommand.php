<?php


namespace SSC\Command\DefaultCommands;


use pocketmine\command\CommandSender;
use pocketmine\command\defaults\VanillaCommand;
use pocketmine\command\utils\CommandException;
use pocketmine\Player;

class calcCommand extends VanillaCommand {

	public function __construct() {
		parent::__construct("calc","計算する","/calc [value] [value] [+|-|*|/]");
	}

	/**
	 * @param string[] $args
	 *
	 * @return mixed
	 * @throws CommandException
	 */
	public function execute(CommandSender $sender, string $commandLabel, array $args) {
		if ($sender instanceof Player) {
			if (!isset($args[0]) or !isset($args[1]) or !isset($args[2])) {
				return false;
			}
			if (!is_numeric($args[0])or!is_numeric($args[1])) {
				return false;
			}
			switch ($args[2]){
				case "+":
					$k = $args[0] + $args[1];
					$sender->sendMessage("[管理AI]" . $args[0] . "+" . $args[1] . "は" . $k);
					return true;
				case "-":
					$k = $args[0] - $args[1];
					$sender->sendMessage("[管理AI]" . $args[0] . "-" . $args[1] . "は" . $k);
					return true;

				case "/":
					if ($args[1] === "0") {
						$sender->sendMessage("[管理AI] ErrorCode:1 0を割ることはできません");
						return false;
					}
					$k = $args[0] / $args[1];
					$sender->sendMessage("[管理AI]" . $args[0] . "÷" . $args[1] . "は" . $k);
					return true;
				case "*":
					$k = $args[0] * $args[1];
					$sender->sendMessage("[管理AI]" . $args[0] . "×" . $args[1] . "は" . $k);
					return true;
				default:
				 return false;
			}
		}
		return true;
	}
}