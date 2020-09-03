<?php


namespace SSC\Command\DefaultCommands;


use pocketmine\command\CommandSender;
use pocketmine\command\defaults\VanillaCommand;
use pocketmine\command\utils\CommandException;
use pocketmine\Player;
use pocketmine\Server;
use SSC\main;

class spCommand extends VanillaCommand {

	public function __construct() {
		parent::__construct("sp", "", "/sp");
	}


	/**
	 * @param string[] $args
	 *
	 * @return mixed
	 * @throws CommandException
	 */
	public function execute(CommandSender $sender, string $commandLabel, array $args) {
		if($sender instanceof Player) {
			if (main::getPlayerData($sender->getName())->getNumberPerm() >= 3) {
				if($sender->getGamemode()===0){
					$sender->setGamemode(3);
					$sender->sendMessage("監視モードに変更しました。pvpワールド内、worldワールド内ではこれを使い移動することを禁止します。");
					foreach (Server::getInstance()->getOnlinePlayers() as $player){
						if($player->isOp()){
							$player->sendMessage($sender->getName()."がスペクテイターモードになりました");
						}
					}
				}else{
					$sender->setGamemode(0);
					$sender->setAllowFlight(false);
					$sender->setFlying(false);
					$sender->sendMessage("通常モードに変更しました。");
					foreach (Server::getInstance()->getOnlinePlayers() as $player){
						if($player->isOp()){
							$player->sendMessage($sender->getName()."がサバイバルモードになりました");
						}
					}
				}

			}
		}
		return true;
	}
}