<?php


namespace SSC\Command\DefaultCommands;


use pocketmine\command\CommandSender;
use pocketmine\command\defaults\VanillaCommand;
use pocketmine\command\utils\CommandException;
use pocketmine\Player;
use pocketmine\Server;
use SSC\main;

class tpcancelCommand extends VanillaCommand {

	public function __construct() {
		parent::__construct("tpcancel", "送ったtpリクエストをキャンセルします", "/tpcancel");
	}

	/**
	 * @param string[] $args
	 *
	 * @return mixed
	 * @throws CommandException
	 */
	public function execute(CommandSender $sender, string $commandLabel, array $args) {
		if(!$sender instanceof Player) return true;
		$playerdata=main::getPlayerData($sender->getName());
		if($playerdata->getSendTppRequest()===""){
			$sender->sendMessage("[転送用AI] tpリクエストを送信した記録がありません");
			return true;
		}

		$target=Server::getInstance()->getPlayer($playerdata->getSendTppRequest());

		if($target===null){
			$sender->sendMessage("[転送用AI] 相手のプレイヤーが存在しないためキャンセルしました");
			$playerdata->resetSendTppRequest();
			return true;
		}

		$sender->sendMessage("[転送用AI] tpリクエストをキャンセルしました");
		$target->sendMessage("[転送用AI] ".$sender->getName()."から送られていたtpリクエストがキャンセルされました");
		$playerdata->resetSendTppRequest();
		main::getPlayerData($target->getName())->resetSentTppRequest();
		return true;
	}
}