<?php


namespace SSC\Event\player;


use bboyyu51\pmdiscord\Sender;
use bboyyu51\pmdiscord\structure\Content;
use onebone\economyapi\EconomyAPI;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\Server;
use SSC\main;

class QuitEvent implements Listener {

	public function onQuit(PlayerQuitEvent $event) {
		$player = $event->getPlayer();
		$name = $player->getName();
		$re = $event->getQuitReason();
		$webhook = Sender::create("https://discordapp.com/api/webhooks/670915313071816704/5CWGaxC5YLlsNHWGygFwobs7DS3T3PPWWvvJN3VIxlF3UkqhkmhJpWomdhBDXiNVnRB0");
		//$webhook = Sender::create("https://discordapp.com/api/webhooks/708707372775768114/viYuI9PbQKKNXKO89-ygqP9xuIMsG_wkO84826XUcBuCsIwlqH0a369SJIGIbqkFB9J5");

		$content = new Content();
		$content->setText("[退室>>]" . $name . " [理由>>]" . $re);
		$webhook->add($content);
		$webhook->setCustomName("QUIT");
		Sender::sendAsync($webhook);

		$pe = main::getPlayerData($player->getName());
		if ($pe->getShitDownNow()) {
			ShitDownEvent::StandUp($player);
		}

		if (isset(main::getMain()->id[$name])) {
			main::getMain()->getScheduler()->cancelTask(main::getMain()->id[$name]);
			unset(main::getMain()->id[$name]);
		}
		if (main::getMain()->playerlist->exists($name)) {
			main::getMain()->playerlist->reload();
			main::getMain()->playerlist->set($player->getName(), (string)$player->getUniqueId()->toString());
			main::getMain()->playerlist->save();
		}
		$money = EconomyAPI::getInstance()->myMoney($name);
		$playerdata = main::getPlayerData($name);
		$playerdata->save($money);
		unset(main::getMain()->playerdata[$name]);
		//Server::getInstance()->getAsyncPool()->submitTask(new SendWeb());


		if ($re === "client disconnect") {
			$event->setQuitMessage("§7[退室] §c" . $name . "§e様が§aサーバー抜け§eで§7§lオフライン§r§eになりました");
		} else if ($re === "Internal server error") {
			$event->setQuitMessage("§7[退室] §c" . $name . "§e様が§aサーバーのエラー§eで§7§lオフライン§r§eになりました");
		} else if ($re === "Kicked by admin.") {
			$event->setQuitMessage("§7[退室] §c" . $name . "§e様が§aopによる強制退出§eで§7§lオフライン§r§eになりました");
		} else if ($re === "timeout") {
			$event->setQuitMessage("§7[退室] §c" . $name . "§e様が§aネットワークの不安定により§a§7§lオフライン§r§eになりました");
		} else if ($re === "サーバーのホームページを読んできてください！\nルールのページにパスワードが書いてあります！\nhttp://yurisi.space/") {
			$event->setQuitMessage("§7[退室] §c" . $name . "§e様が§a新規ルール未読により§a§7§lオフライン§r§eになりました");
		} else {
			$event->setQuitMessage("§7[退室] §c" . $name . "§e様が§a" . $re . "§eで§7§lオフライン§r§eになりました");
		}

		if ($playerdata->getPerm() != "OP" and $playerdata->getPerm() != "オーナー") {
			main::getMain()->registerRanking($playerdata);
		}

		if ($player->getInventory()->getItemInHand()->getNamedTag()->offsetExists("gun")) {
			$gunmanager = main::getMain()->getGunManager();
			$gun = $player->getInventory()->getItemInHand()->getNamedTag()->getString("gun");
			$serial = $player->getInventory()->getItemInHand()->getNamedTag()->getString("serial");
			$gundata = $gunmanager->getGunData($gun, $serial);
			if ($gundata->isShootNow()) {
				main::getMain()->getScheduler()->cancelTask($gundata->getTaskId());
				$gundata->endShoot();
			}
		}
	}
}