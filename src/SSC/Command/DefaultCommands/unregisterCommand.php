<?php


namespace SSC\Command\DefaultCommands;


use pocketmine\command\CommandSender;
use pocketmine\command\defaults\VanillaCommand;
use pocketmine\command\utils\CommandException;
use pocketmine\Player;
use pocketmine\Server;
use SSC\main;

class unregisterCommand extends VanillaCommand {


	public function __construct() {
		parent::__construct("unregister","","/unregister");
	}

	/**
	 * @param string[] $args
	 *
	 * @return mixed
	 * @throws CommandException
	 */
	public function execute(CommandSender $sender, string $commandLabel, array $args) {
		if($sender->isOp()) {
			if (!isset($args[0])) {
				return false;
			}
			$aitename = $args[0];
			$player = Server::getInstance()->getPlayer($aitename);
			if (main::getMain()->isCBan(main::getMain()->playerlist->exists($aitename))) {
				$sender->sendMessage("BAN者のプレイヤーデータリセットはできません");
				return true;
			}
			if (main::getMain()->playerlist->exists($aitename)) {
				if ($player instanceof Player) {
					$player->kick("[管理AI]§cログインしなおしてください", false);
				}
				main::getMain()->password->remove($aitename);
				main::getMain()->password->save();
				main::getMain()->playerlist->reload();
				main::getMain()->playerlist->remove($aitename);
				main::getMain()->playerlist->save();
				$sender->sendMessage("[管理AI]" . $aitename . "§fのパスワードをリセットしました");
			} else {
				$sender->sendMessage("[管理AI]§a" . $aitename . "§fは存在しません");
			}
		}else{
			main::getMain()->password->remove($sender->getName());
				main::getMain()->password->save();
				main::getMain()->playerlist->reload();
				main::getMain()->playerlist->remove($sender->getName());
				main::getMain()->playerlist->save();
				$sender->kick("[管理AI]§cパスワードをリセットしました。ログインしなおしてください", false);
		}
		return true;
	}
}