<?php


namespace SSC\Command\DefaultCommands;


use pocketmine\command\CommandSender;
use pocketmine\command\defaults\VanillaCommand;
use pocketmine\Player;
use pocketmine\Server;
use SSC\main;

class tppCommand extends VanillaCommand {


	public function __construct() {
		parent::__construct("tpp", "テレポート申請を送ります", "/tpp [player](名前は省略可能)");
	}

	/**
	 * @param CommandSender $sender
	 * @param string $commandLabel
	 * @param string[] $args
	 *
	 * @return mixed
	 */
	public function execute(CommandSender $sender, string $commandLabel, array $args) {
		if (!$sender instanceof Player) return true;
		if (!isset($args[0])) {
			$sender->sendMessage("[管理AI] /tpp プレイヤー名");
			return true;
		}

		$server = Server::getInstance();

		if ($server->getPlayer($args[0]) === null) {
			$sender->sendMessage("[転送用AI] §aそのプレイヤーはオフラインです");
			return true;
		}

		$target = main::getPlayerData($server->getPlayer($args[0])->getName());
		$targetname = $target->getName();
		$name = $sender->getName();
		$playerdata=main::getPlayerData($name);


		if ($targetname === $name) {
			$sender->sendMessage("[転送用AI]§a自分にリクエストを送信することはできません。");
			return true;
		}

		if(!$target->getSentTppRequest()===""){
			$sender->sendMessage("[転送用AI] §a相手に他のリクエストがあるようなのでできません！");
			return true;
		}

		if(!$playerdata->getSendTppRequest()===""){
			$sender->sendMessage("[転送用AI] §aあなたはすでに「".$playerdata->getSendTppRequest()."」にリクエストを送っています");
			$sender->sendMessage("[転送用AI] §aキャンセルする場合は/tpcancelでキャンセルすることができます。");
			return true;
		}

		if ($target->getPlayer()->getLevel()->getFolderName() === "sun") {
			if (!$playerdata->isSun()) {
				$sender->sendMessage("[転送用AI]§a相手は太陽にいますが、あなたは太陽に行くチケットを未所持のため送信できませんでした。");
				return true;
			}
		}

		if ($target->getPlayer()->getLevel()->getFolderName() === "pluto") {
			if (!$playerdata->isExpertLevel()) {
				$sender->sendMessage("[転送用AI]§a相手は冥王星にいますが、あなたは冥王星に行く権限を未所持のため送信できませんでした。");
				return true;
			}
		}

		if ($target->getPlayer()->getLevel()->getFolderName() === "moon") {
			if ($playerdata->getSpaceShipSize() < 20) {
				$sender->sendMessage("[転送用AI]§a相手は月にいますが、あなたは月に行く権限を未所持のため送信できませんでした。");
				return true;
			}
		}

		if ($target->getSentTppRequest()==="") {
			$target->getPlayer()->sendMessage("[転送用AI]§d" . $name . " §bはあなたにテレポートしたいようです.");
			$target->getPlayer()->sendMessage("[転送用AI] /tpagree§dとチャット欄に打てば承認することができます");
			$target->getPlayer()->sendMessage("[転送用AI] /tpdis §dとチャット欄に打てば拒否することができます");
			$sender->sendMessage("[転送用AI] §r§bリクエストを §a" . $targetname . "§bに送信しました");
			$target->setSentTppRequest($name);
			$playerdata->setSendTppRequest($targetname);
		}
		return true;
	}
}