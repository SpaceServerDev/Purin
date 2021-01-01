<?php


namespace SSC\Command\DefaultCommands;


use pocketmine\command\CommandSender;
use pocketmine\command\defaults\VanillaCommand;
use pocketmine\command\utils\CommandException;
use pocketmine\Player;
use SSC\Item\NavigationStick;

class naviCommand extends VanillaCommand {

	public function __construct() {
		parent::__construct("navi", "ナビゲーターアイテムを入手", "/navi");
	}

	/**
	 * @param CommandSender $sender
	 * @param string $commandLabel
	 * @param string[] $args
	 *
	 * @return mixed
	 */
	public function execute(CommandSender $sender, string $commandLabel, array $args) {
		$item=NavigationStick::get();
		if($sender instanceof Player){
			if($sender->getInventory()->canAddItem($item)){
				$sender->getInventory()->addItem($item);
				$sender->sendMessage("[管理AI]インベントリに転送いたしました");
			}
		}
		return true;
	}
}