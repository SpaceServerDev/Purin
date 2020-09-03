<?php


namespace SSC\Command\DefaultCommands;


use pocketmine\command\CommandSender;
use pocketmine\command\defaults\VanillaCommand;
use pocketmine\command\utils\CommandException;
use pocketmine\item\Item;
use pocketmine\Player;

class saddleCommand extends VanillaCommand {

	public function __construct() {
		parent::__construct("saddle","サドルをゲット！","/saddle");
	}

	/**
	 * @param string[] $args
	 *
	 * @return mixed
	 * @throws CommandException
	 */
	public function execute(CommandSender $sender, string $commandLabel, array $args) {
		if($sender instanceof Player){
			$saddle=Item::get(329,0,1);
			if($sender->getInventory()->canAddItem($saddle)){
				$sender->getInventory()->addItem($saddle);
				$sender->sendMessage("[管理AI]サドルを追加しました！");
			}
		}
		return true;
	}
}