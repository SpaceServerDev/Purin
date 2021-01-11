<?php


namespace SSC\Command\DefaultCommands;


use pocketmine\command\CommandSender;
use pocketmine\command\defaults\VanillaCommand;
use pocketmine\command\utils\CommandException;
use pocketmine\Player;
use pocketmine\Server;
use SSC\main;

class tpdisCommand extends VanillaCommand {

	public function __construct() {
		parent::__construct("tpdis","テレポートの要求を拒否します。","/tpdis");
	}

	/**
	 * @param string[] $args
	 *
	 * @return mixed
	 * @throws CommandException
	 */
	public function execute(CommandSender $sender, string $commandLabel, array $args) {
		if (!$sender instanceof Player) return true;
		$name = $sender->getName();
		$playerdata = main::getPlayerData($name);

		if ($playerdata->getSentTppRequest() === "") {
			$sender->sendMessage("[転送用AI] §cリクエストはありません。");
			return true;
		}

		$target = Server::getInstance()->getPlayer($playerdata->getSentTppRequest());

		if ($target === null) {
			$sender->sendMessage("[転送用AI] §4相手プレイヤーが存在しないのでテレポートを拒否しました。");
			$playerdata->resetSentTppRequest();
			return true;
		}

		$target->sendMessage("[転送用AI] §4" . $sender->getName() . " はあなたの要求を拒否しました");
		$sender->sendMessage("[転送用AI] §4テレポートの要求を拒否しました");
		$playerdata->resetSentTppRequest();
		main::getPlayerData($target->getName())->resetSendTppRequest();
		return true;
	}
}