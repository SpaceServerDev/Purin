<?php


namespace SSC\Command\DefaultCommands;


use bboyyu51\pmdiscord\Sender;
use bboyyu51\pmdiscord\structure\Content;
use pocketmine\command\CommandSender;
use pocketmine\command\defaults\VanillaCommand;
use pocketmine\command\utils\CommandException;
use pocketmine\Player;
use pocketmine\Server;
use SSC\main;

class cbanCommand extends VanillaCommand {

	public function __construct() {
		parent::__construct("cban","banする。","/cban ");
	}

	/**
	 * @param Player $sender
	 * @param string $commandLabel
	 * @param string[] $args
	 *
	 * @return mixed
	 */
	public function execute(CommandSender $sender, string $commandLabel, array $args) {
		if (main::getPlayerData($sender->getName())->getNumberPerm() >= 2) {
			if (!isset($args[0])) return false;
			if (!isset($args[1])) {
				$sender->sendMessage("/cban [name] [reason]");
				return false;
			}
			$name = $args[0];
			$player = Server::getInstance()->getPlayer($name);
			main::getMain()->playerlist->reload();
			if (main::getMain()->playerlist->exists($name)) {
				if ($player instanceof Player) $player->kick("[警備AI]§4あなたは接続禁止処理されました。", false);
				$cid = main::getMain()->playerlist->get($name);
				main::getMain()->addCBan($cid, $reason = (isset($args[1])) ? $args[1] : "Ban");
				Server::getInstance()->getNameBans()->addBan($name, "", null, $sender->getName());
				Server::getInstance()->broadcastMessage("[管理AI] §4" . $sender->getName() . "が" . $args[0] . "を接続禁止処理しました");
				$webhook = Sender::create("https://discordapp.com/api/webhooks/673124550866763806/oCgzjzWDJ6k5AT2H-bh2JPUxqVBCpoqXjBKg-n6qgdx3Hl_QD3c7D9T1PQ13hpozuV3y");
				$content = new Content();
				$content->setText($sender->getName() . "が" . $args[0] . "を" . $args[1] . "でBAN処理しました。");
				$webhook->add($content);
				$webhook->setCustomName("BAN");
				Sender::sendAsync($webhook);
			} else {
				$sender->sendMessage("[管理AI]{$args[0]}は存在しません");
			}
			return true;
		}
		return true;
	}
}