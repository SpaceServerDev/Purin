<?php


namespace SSC\Command\DefaultCommands;



use pocketmine\command\CommandSender;
use pocketmine\command\defaults\VanillaCommand;
use pocketmine\command\utils\CommandException;
use pocketmine\Player;
use pocketmine\Server;
use SSC\main;

class tokenCommand extends VanillaCommand {

	public function __construct() {
		parent::__construct("token","","");
	}

	/**
	 * @param string[] $args
	 *
	 * @return mixed
	 * @throws CommandException
	 */
	public function execute(CommandSender $sender, string $commandLabel, array $args) {
		if ($sender->isOp()) {
			if($sender instanceof Player){
				$timestamp=time();
				$result = strrev($timestamp % pow(10, 4));
				$sender->sendMessage("トークンを取得しました。{$result}厳重な管理をお願いします。");
				main::getMain()->addToken($result);
			}else{
				if(!isset($args[0])) return false;
				main::getMain()->addToken((string)$args[0]);
			}
		}
		return true;
	}
}