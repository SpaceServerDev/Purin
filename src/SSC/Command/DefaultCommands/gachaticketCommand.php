<?php


namespace SSC\Command\DefaultCommands;


use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\utils\CommandException;
use pocketmine\event\Listener;
use pocketmine\item\Item;
use pocketmine\Player;
use SSC\Item\GachaTicket;

class gachaticketCommand extends Command {

	public function __construct() {
		parent::__construct("gachaticket","スタッフ専用コマンドです","");
	}

	/**
	 * @param string[] $args
	 *
	 * @return mixed
	 * @throws CommandException
	 */
	public function execute(CommandSender $sender, string $commandLabel, array $args) {
		if($sender instanceof Player){
			if(!$sender->isOp()) return true;
			if(!isset($args[0])) return true;
			if(!is_numeric($args[0])) return true;
			$paper=GachaTicket::get($args[0]);
			if(!$sender->getInventory()->canAddItem($paper)){
				$sender->sendMessage("インベントリに空きがありません。");
				return true;
			}
			$sender->getInventory()->addItem($paper);
		}
		return true;
	}
}