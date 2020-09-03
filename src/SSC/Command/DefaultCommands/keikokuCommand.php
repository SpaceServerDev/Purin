<?php


namespace SSC\Command\DefaultCommands;


use bboyyu51\pmdiscord\Sender;
use bboyyu51\pmdiscord\structure\Content;
use pocketmine\command\CommandSender;
use pocketmine\command\defaults\VanillaCommand;
use pocketmine\command\utils\CommandException;
use SSC\main;

class keikokuCommand extends VanillaCommand {

	public function __construct() {
		parent::__construct("keikoku","警告をする","/keikoku");
	}

	/**
	 * @param string[] $args
	 *
	 * @return mixed
	 * @throws CommandException
	 */
	public function execute(CommandSender $sender, string $commandLabel, array $args) {
		if (main::getPlayerData($sender->getName())->getNumberPerm() >= 2) {
			if (isset($args[0]) and isset($args[1])) {
				if ($target = $sender->getServer()->getPlayer($args[0])) {
					$aite = $sender->getServer()->getPlayer($args[0]);
					$aitename = $aite->getName();
					$aiteplayerdata = main::getPlayerData($aitename);
					$aiteplayerdata->addWarn();
					$aite->kick("§4§l警告付与 理由:§r{$args[1]}", false);
					$webhook = Sender::create("https://discordapp.com/api/webhooks/673124550866763806/oCgzjzWDJ6k5AT2H-bh2JPUxqVBCpoqXjBKg-n6qgdx3Hl_QD3c7D9T1PQ13hpozuV3y");
					$content = new Content();
					$content->setText($sender->getName() . "が" . $args[0] . "を" . $args[1] . "で警告付与しました。");
					$webhook->add($content);
					$webhook->setCustomName("Warning");
					Sender::sendAsync($webhook);
					$sender->sendMessage("[管理AI]§a{$aitename}を{$args[1]}でkickし、警告を付与しました");
				} else {
					$sender->sendMessage("[管理AI]§4プレイヤーが存在しません");
				}
			} else {
				return false;
			}
		}
		return true;
	}
}