<?php


namespace SSC\Event\player;


use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\ModalFormRequestPacket;
use pocketmine\network\mcpe\protocol\PlaySoundPacket;
use pocketmine\Server;
use SSC\Data\FishConfig;
use SSC\Form\LoginForm\RegisterForm;
use SSC\Form\LoginForm\ReloginForm;
use SSC\main;
use SSC\PlayerEvent;
use SSC\Task\Sendtask;
use xenialdan\apibossbar\BossBar;

class JoinEvent implements Listener {

	public function onJoin(PlayerJoinEvent $event) {
		$player = $event->getPlayer();
		$player->sendTitle("§eWelcome to SpaceServer");
		$name = $player->getName();
		/**@var PLayerEvent $playerdata */
		$playerdata = main::getPlayerData($name);
		$playerdata->setAll();
		$playerdata->setTime();

		main::getMain()->sendDiscord("JOIN", "[入室>>]" . $name);

		main::getMain()->playerlog[$name] = 0;
		if (main::getMain()->loginbonus->exists($name)) {
			if (main::getMain()->loginbonus->get($name) !== date("Y/m/d")) {
				$playerdata->changeDay();
				main::getMain()->loginbonus->set($name, date("Y/m/d"));
				main::getMain()->loginbonus->save();
			}
		} else {
			$playerdata->changeLoginBonus();
			$playerdata->addLoginDay();
			$playerdata->changeDairy();
			$playerdata->setBonus(false);
			main::getMain()->loginbonus->set($name, date("Y/m/d"));
			main::getMain()->loginbonus->save();
		}

		$playerdata->setMove(new Vector3($player->getFloorX(), 0, $player->getFloorZ()));
		$playerdata->registerFish(new FishConfig(main::getMain()->getDataFolder()."Fish"."/{$name}.yml"));
		if ($player->getGamemode() == 0) {
			if ($player->getLevel()->getFolderName() == "space") {
				$player->setAllowFlight(true);
				$player->setFlying(true);
			} else {
				$player->setAllowFlight(false);
				$player->setFlying(false);
			}
		}

		/*
		   * 称号などの変更
		   */
		if ($playerdata->isNormalLevel()) {
			$lt = floor($playerdata->getLevel() * 1.1);
			$leveltable = (30 * $lt) + $playerdata->getTotalExp();
		} else if($playerdata->isAdvanceLevel()){
			$lt = floor($playerdata->getLevel() * 1.3);
			$leveltable = (120 * $lt) + $playerdata->getTotalExp();
		}else if($playerdata->isExpertLevel()){
			$lt = floor($playerdata->getLevel() * 1.5);
			$leveltable = (250 * $lt) + $playerdata->getTotalExp();
		}
		if ($playerdata->getExp() == 0) {
			$keiken = 0;
		} else {
			$a = $playerdata->getExp() - $playerdata->getTotalExp();
			$b = $leveltable - $playerdata->getTotalExp();
			$keiken = $a / $b;
		}
		$bar = (new BossBar())->setTitle("経験値 {$playerdata->getExp()} / {$leveltable}")->setSubTitle("名前:{$name} レベル:{$playerdata->getLevel()}")->setPercentage($keiken)->addPlayer($player);
		$playerdata->registerBossbar($bar);

		$playerdata->setDisplayName($playerdata->getDisplayName());
		$playerdata->setTagName($playerdata->getTagName());

		$nbt = $player->namedtag;
		$nbt->setInt("FishFeed", 0);


		main::getMain()->tpplayer[$name] = null;

		/*
		   * 入室メッセージの管理
		   */
		if (!main::getMain()->playerlist->exists($name)) {
			$event->setJoinMessage("§a[入室] §a§l 初in §r§bの§c" . $name . "§b様が§a§lオンライン§r§bになりました");
		} else {
			if ($name === "yurisi") {
				$event->setJoinMessage("§a[入室] §bオーナーの§c" . $name . "§bが§a§lオンライン§r§bになりました");
			} else if ($name === "jimsan114") {
				$event->setJoinMessage("§a[入室] §aサブオーナーの§c" . $name . "§a様が§a§lオンライン§r§bになりました");
			} else if ($name === "yomogicute") {
				$event->setJoinMessage("§a[入室] §bいけめんの§c" . $name . "§b様が§a§lオンライン§r§bになりました");
			} else if ($name === "Ka3y9") {
				$event->setJoinMessage("§a[入室] §dどすえスタッフの§c" . $name . "§d様が§a§lオンライン§r§bになりました");
			} else if ($name === "sndykmr0102") {
				$event->setJoinMessage("§a[入室] §dさかみちスタッフの§c" . $name . "§d様が§a§lオンライン§r§bになりました");
			} else if ($name === "liliyama0132") {
				$event->setJoinMessage("§a[入室] §dかわいいスタッフの§c" . $name . "§d様が§a§lオンライン§r§bになりました");
			} else if ($name === "AkToU07") {
				$event->setJoinMessage("§a[入室] §dけんちくスタッフの§c" . $name . "§d様が§a§lオンライン§r§bになりました");
			} else if ($name === "Tanbo1223") {
				$event->setJoinMessage("§a[入室] §dふしぎなスタッフの§c" . $name . "§d様が§a§lオンライン§r§bになりました");
			} else if ($name === "tamnia") {
				$event->setJoinMessage("§a[入室] §b天才ら民のたむにあ§eが§a§lオンライン§r§eになりました");
			} else if ($name === "ryuu219") {
				$event->setJoinMessage("§a[入室] §bryuu219が採掘しに来ました！！");
			} else if ($name === "ApateticFoil114") {
				$event->setJoinMessage("§a[入室] §eゆりしーの財布！§r§aApateticFoil114が入室しました！！");
			} else if ($name === "Liar Rowen") {
				$event->setJoinMessage("§a[入室] §b誰が来た？§c奴が来た？§a§lLiar Rowen§r§aが来た！");
			} else if ($name === "Iron7hunter") {
				$event->setJoinMessage("§a[入室] §bIron7hunter「ぽえ」");
			} else if ($name === "yus10124") {
				$event->setJoinMessage("§a[入室] §aハロー！§dyus10124§aがやってきた！");
			} else if ($name === "cheshaneko410") {
				$event->setJoinMessage("§a[入室] §bにゃんはろー§d§lチェシャ猫§r§bがきーたよ(๑>ㅅ<๑)ฅ");
			} else if ($name === "VillagerMeyason") {
				$event->setJoinMessage("§a[入室] §5じゃがいも§e大王 §d§lめやそん §r§5がいらっしゃった！");
			} else {
				$event->setJoinMessage("§a[入室] §c" . $name . "§e様が§a§lオンライン§r§eになりました");
			}

			if (main::getMain()->blacklist->exists($player->getName()) == true) {
				$player->setImmobile(true);
			}
		}
		$task = new SendTask(main::getMain(), $player);
		main::getMain()->getScheduler()->scheduleRepeatingTask($task, 5);
		main::getMain()->id[$player->getName()] = $task->getTaskId();

		$pk = new PlaySoundPacket;
		$pk->soundName = "beacon.power";//"server.welcome";
		$pk->x = $player->x;
		$pk->y = $player->y;
		$pk->z = $player->z;
		$pk->volume = 0.5;
		$pk->pitch = 1;
		$player->sendDataPacket($pk);
		$pk = new PlaySoundPacket;
		$pk->soundName = "music.welcome";
		$pk->x = $player->x;
		$pk->y = $player->y;
		$pk->z = $player->z;
		$pk->volume = 0.5;
		$pk->pitch = 1;
		$player->sendDataPacket($pk);

		main::getMain()->playerlist->reload();
		$name = $player->getName();
		$ip = $player->getAddress();
		if (!main::getMain()->playerlist->exists($name)) {
			$player->setImmobile(true);
			$player->sendForm(new RegisterForm());
		} else {
			Server::getInstance()->dispatchCommand($player, "info");

		}

		if($playerdata->getPerm()!="OP" and $playerdata->getPerm()!="オーナー"){
			main::getMain()->registerRanking($playerdata);
		}
		return true;

	}
}