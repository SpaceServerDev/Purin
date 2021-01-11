<?php

namespace SSC\Task;

use pocketmine\command\ConsoleCommandSender;
use pocketmine\level\Level;
use pocketmine\level\particle\FloatingTextParticle;
use pocketmine\scheduler\Task;
use pocketmine\entity\Entity;
use pocketmine\entity\object\ItemEntity;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\PlaySoundPacket;
use pocketmine\network\mcpe\protocol\AddActorPacket;
use pocketmine\Server;
use SSC\Data\RankConfig;
use SSC\main;

class RebootTask extends Task{


	/**
	 * @var bool
	 */
	private $first = false;

	/**
	 * @var FloatingTextParticle
	 */
	private $part;

	/**
	 * @var int
	 */
	private $rank=0;
	/**
	 * @var array
	 */
	private $particles;


	public function onRun($tick) {
		main::getMain()->CountDownServerReloadTick();
		main::getMain()->seconds--;

		if (main::getMain()->getServerReloadTick() === 0) {
			Server::getInstance()->dispatchCommand(new ConsoleCommandSender(),"stop");
			return;
		} else if (main::getMain()->getServerReloadTick() === 10) {
			Server::getInstance()->broadcastTip("[管理AI]§c§l10秒後に再起動します");
		} else if (main::getMain()->getServerReloadTick() === 30) {
			Server::getInstance()->broadcastTip("[管理AI]§c§l30秒後に再起動します");
		} else if (main::getMain()->getServerReloadTick() === 60) {
			Server::getInstance()->broadcastTip("[管理AI]§c§l1分後に再起動します");
		} else if (main::getMain()->getServerReloadTick() === 5) {
			Server::getInstance()->broadcastTip("[管理AI]§c§l5秒後に再起動します");
		} else if (main::getMain()->getServerReloadTick() === 4) {
			Server::getInstance()->broadcastTip("[管理AI]§c§l4秒後に再起動します");
		} else if (main::getMain()->getServerReloadTick() === 3) {
			Server::getInstance()->broadcastTip("[管理AI]§c§l3秒後に再起動します");
		} else if (main::getMain()->getServerReloadTick() === 2) {
			Server::getInstance()->broadcastTip("[管理AI]§c§l2秒後に再起動します");
		} else if (main::getMain()->getServerReloadTick() === 1) {
			Server::getInstance()->broadcastTip("[管理AI]§c§l1秒後に再起動します");
		}

		if (main::getMain()->seconds === 0) {
			$amount = 0;
			foreach (Server::getInstance()->getLevels() as $level) {
				foreach ($level->getEntities() as $entity) {
					if ($entity instanceof ItemEntity) {
						++$amount;
						$entity->flagForDespawn();
					}
				}
			}
			Server::getInstance()->broadcastTip("[管理AI]§c§lすべてのアイテム({$amount}個)を掃除いたしました。");


			switch (mt_rand(1, 18)) {
				case 1:
					Server::getInstance()->broadcastMessage("§a§lアナウンス>> コマンドがわからなくなったら/cmdを見てみよう！");
					break;
				case 2:
					Server::getInstance()->broadcastMessage("§a§lアナウンス>> /warpで快適なワープを実現できます");
					break;
				case 3:
					Server::getInstance()->broadcastMessage("§a§lアナウンス>> /syo で称号が変えられます！");
					break;
				case 4:
					Server::getInstance()->broadcastMessage("§a§lアナウンス>> エンチャント武器がほしいときは/gachaで！");
					break;
				case 5:
					Server::getInstance()->broadcastMessage("§a§lアナウンス>> /randで乱数を生成できるよ！");
					break;
				case 6:
					Server::getInstance()->broadcastMessage("§a§lアナウンス>> 土地保護は/ruleを見てみよう");
					break;
				case 7:
					Server::getInstance()->broadcastMessage("§a§lアナウンス>> 水流と溶岩流は禁止！見つけたら埋めてください");
					break;
				case 8:
					Server::getInstance()->broadcastMessage("§a§lアナウンス>> 要望があれば@Dev_yrsまで！");
					break;
				case 9:
					Server::getInstance()->broadcastMessage("§a§lアナウンス>> discordの登録してますか？お得情報満載なので登録してください！");
					break;
				case 10:
					Server::getInstance()->broadcastMessage("§a§lアナウンス>> 疲れたらしっかり休もう！元気なあなたを待ってます！");
					break;
				case 11:
					Server::getInstance()->broadcastMessage("§a§lアナウンス>> レベル報酬は/levelbonusで確認できます！");
					break;
				case 12:
					Server::getInstance()->broadcastMessage("§a§lアナウンス>> 設置・破壊ログはすべて記録されています。荒らしを行った場合即座に特定・BANできるように努めております");
					break;
				case 13:
					Server::getInstance()->broadcastMessage("§a§lアナウンス>> 雑掘は荒らしとみなしBANいたします。");
					break;
				case 14:
					Server::getInstance()->broadcastMessage("§a§lアナウンス>> レベルが501になれば/advancemodeでレベル上限が開放されます！");
					break;
				case 15:
					Server::getInstance()->broadcastMessage("§a§lアナウンス>> xtpで現実的じゃない数字に飛ばないように注意！");
					break;
				case 16:
					Server::getInstance()->broadcastMessage("§a§lアナウンス>> お金は/jobで貯めれます！");
					break;
				case 17:
					Server::getInstance()->broadcastMessage("§a§lアナウンス>> シルクタッチ付きの道具は経験値、お金は入りません");
					break;
				case 18:
					Server::getInstance()->broadcastMessage("§a§lアナウンス>> 釣った魚は/townで納品しよう");
					break;
			}
			$this->Thunder();
			main::getMain()->seconds = 599;
		} else if (main::getMain()->seconds === 10) {
			Server::getInstance()->broadcastTip("[管理AI]§c§l10秒後に落ちてるアイテムを掃除します。");
			Server::getInstance()->broadcastMessage("[管理AI] プレイヤーデータ保存中...");
			foreach (Server::getInstance()->getOnlinePlayers() as $p) {
				if (main::getMain()->getPlayerData($p->getName()) === false) {
					continue;
				}
				$playerdata = main::getMain()->getPlayerData($p->getName());
				if ($playerdata->getLoad()) {
					$playerdata->save(main::getMain()->economyAPI->myMoney($p->getName()));
				}
				$p->save();
			}
			Server::getInstance()->broadcastMessage("[管理AI] 保存が完了しました");
		} else if (main::getMain()->seconds === 3) {
			Server::getInstance()->broadcastTip("[管理AI]§c§l3秒後に落ちてるアイテムを掃除します。");
		} else if (main::getMain()->seconds === 2) {
			Server::getInstance()->broadcastTip("[管理AI]§c§l2秒後に落ちてるアイテムを掃除します。");
			foreach (Server::getInstance()->getOnlinePlayers() as $player){
				main::getPlayerData($player->getName())->resetNukeCount();
			}
		} else if (main::getMain()->seconds === 1) {
			Server::getInstance()->broadcastTip("[管理AI]§c§l1秒後に落ちてるアイテムを掃除します。");
		} else if (main::getMain()->seconds === 100) {
			$this->Thunder();
			Server::getInstance()->broadcastMessage("[管理AI] ワールドデータ保存中...");
			foreach (Server::getInstance()->getLevels() as $level) {
				$level->save(true);
			}
			foreach (Server::getInstance()->getOnlinePlayers() as $player){
				main::getPlayerData($player->getName())->resetNukeCount();
			}
			Server::getInstance()->broadcastMessage("[管理AI] 保存が完了しました");
		} else if (main::getMain()->seconds === 150) {
			Server::getInstance()->broadcastMessage("[管理AI] ランキング更新中...");
			foreach (Server::getInstance()->getOnlinePlayers() as $p) {
				if (main::getMain()->getPlayerData($p->getName()) === false) {
					continue;
				}
				$playerdata = main::getMain()->getPlayerData($p->getName());
				if ($playerdata->getLoad()) {
					$playerdata->save(main::getMain()->economyAPI->myMoney($p->getName()));
				}
				$p->save();
			}
			Server::getInstance()->broadcastMessage("[管理AI] 更新が完了しました");
			$this->Thunder();
		}else if(main::getMain()->seconds%5===0){
			foreach (Server::getInstance()->getOnlinePlayers() as $player){
				main::getPlayerData($player->getName())->resetNukeCount();
			}
		}else if(main::getMain()->seconds%100===0){
			foreach (Server::getInstance()->getOnlinePlayers() as $player){
				main::getPlayerData($player->getName())->getBossbar()->removePlayer($player);
			}
		}


		if(main::getMain()->seconds%10===0){
			$rname=[];
			switch ($this->rank){
				case 0:
					$rname=["login","ログインした日数","日"];
				break;
				case 1:
					$rname=["repeat","連続ログイン日数","日"];
				break;
				case 2:
					$rname=["break","ブロック破壊個数","個"];
				break;
				case 3:
					$rname=["peace","プロック設置","個"];
				break;
				case 4:
					$rname=["trappist","トラピスト整地数","個"];
				break;
				case 5:
					$rname=["flower","花を植えた数","輪"];
				break;
				case 6:
					$rname=["wood","木を切った数","個"];
				break;
				case 7:
					$rname=["gacha","ガチャの回数","回"];
				break;
				case 8:
					$rname=["shopping","買い物回数","回"];
				break;
				case 9:
					$rname=["slot","スロット回数","回"];
				break;
				case 10:
					$rname=["kill","キル数","回"];
				break;
				case 11:
					$rname=["killst","最大キルストリーク","回"];
				break;
				case 12:
					$rname=["fishing","釣りした回数","回"];
				break;
				case 13:
					$rname=["money","所持金ランキング","￥"];
				break;
				case 14:
					$rname=["emerald","エメラルド","個"];
				break;
				case 15:
					$rname=["coal","石炭","個"];
				break;
				case 16:
					$rname=["lapis","ラピスラズリ","個"];
				break;
				case 17:
					$rname=["iron","鉄","個"];
				break;
				case 18:
					$rname=["redstone","赤石","個"];
				break;
				case 19:
					$rname=["gold","金","個"];
				break;
				case 20:
					$rname=["diamond","ダイヤモンド","個"];
				break;
				case 21:
					$rname=["spaceshipsize","宇宙船サイズ",""];
				break;
			}
			self::Text($rname[0],$rname[1],$rname[2]);
			if($this->rank===21){
				$this->rank=0;
			}else{
				$this->rank++;
			}
		}
	}

	private function Text(string $rname,string $rdata,string $unit="個") {
		if(!$this->first){
			$this->registerText();
			$this->addTextParticles();
		}
		/**@var $part \pocketmine\level\particle\FloatingTextParticle*/
		$count=0;
		foreach ($this->particles as $part){
			$title=$part->getTitle();
			$text=$part->getText();
			$part->setTitle("");
			$part->setText("");
			if($count<4){
				Server::getInstance()->getLevelByName("world")->addParticle($part);
			}else{
				Server::getInstance()->getLevelByName("space")->addParticle($part);
			}
			$part->setTitle($title);
			$part->setText($text);
			if($count<4){
				Server::getInstance()->getLevelByName("world")->addParticle($part);
			}else{
				Server::getInstance()->getLevelByName("space")->addParticle($part);
			}
			$count++;
		}
		$content = "";
		$rank = 1;
		$cls = new RankConfig(main::getMain()->getDataFolder() . "Rank/{$rname}.yml");
		$cls->reload();
		if (empty($cls->getTopRank(5))) {
			$content = "NODATA";
		}
		foreach ($cls->getTopRank(5) as $name => $data) {
			$content = $content . "{$rank}位 {$name}:{$data}{$unit}\n";
			$rank++;
		}
		if($this->part!=null) {
			$this->part->setTitle("§aRanking: {$rdata}");
			$this->part->setText($content);
			$level = Server::getInstance()->getLevelByName("space");
			$level->addParticle($this->part);
		}
	}

	public function registerText(){
		$level = Server::getInstance()->getLevelByName("space");
		$pos = new Vector3(223, 101, 380);
		$particle = new FloatingTextParticle($pos, "セッティング中", "§aRanking: セッティング中");
		$level->addParticle($particle);
		$this->part = $particle;
		$this->first=true;
	}

	private function Thunder(){
		$pk = new AddActorPacket();
		$pk->type = 93;
		$pk->entityRuntimeId = Entity::$entityCount++;
		$pk->position = new Vector3(259, 111 , 256);
		Server::getInstance()->broadcastPacket(Server::getInstance()->getLevelByName("mars")->getPlayers(), $pk);
		$pk2 = new PlaySoundPacket;
		$pk2->soundName = "random.explode";
		$pk2->x = 259;
		$pk2->y = 111;
		$pk2->z = 256;
		$pk2->volume = 0.5;
		$pk2->pitch = 1;
		Server::getInstance()->broadcastPacket(Server::getInstance()->getLevelByName("mars")->getPlayers(), $pk2);
	}

	private function addFloatTextParticle(Level $level,Vector3 $pos,string $text,string $title){
		$particle = new FloatingTextParticle($pos, $text, $title);
		$level->addParticle($particle);
		$this->particles[]=$particle;
	}

	private function addTextParticles(){
		$world=Server::getInstance()->getLevelByName("world");
		$space=Server::getInstance()->getLevelByName("space");
		$this->addFloatTextParticle($world,new Vector3(9995.2,6.0,10039.4),"","大まかなサーバーの流れ！");
		$this->addFloatTextParticle($world,new Vector3(9992.8,6.0,10039.5),"§bオープンワールド生活サーバーですので\n/warpで地球に向かい、木を取りましょう","§a1,最初の流れ");
		$this->addFloatTextParticle($world,new Vector3(9989.3,7.0,10039.6),"§b/jobで仕事に付くとお金を稼げます。\n有効に活用しましょう！","§a2、jobでお金を貯めよう");
		$this->addFloatTextParticle($world,new Vector3(9985.0,7.0,10039.6),"§a自由な生活サーバーで\nルールが複雑かもしれませんがきっとだいじょうぶ！","§a3,あとは存分に楽しもう！");
		$this->addFloatTextParticle($space,new Vector3(224.6,100.2,391.2),"浮遊できるワールドです！\n§a浮けなくなったら/spawnをしてくださいね！","宇宙サーバーへようこそ！");
		$this->addFloatTextParticle($space,new Vector3(226.7,103.2,379.8),"", "§aアスレチック！");
		$this->addFloatTextParticle($space,new Vector3(208.7,100.7,391.1),"§d↓宇宙へ飛び立てます。","§aハッチ");
		$this->addFloatTextParticle($space,new Vector3(203.9,94.4,385.3),"","§c太陽系はこちらです。");
		$this->addFloatTextParticle($space,new Vector3(191.6,101.0,391.0),"","§bこちらからPVPエリアへ向かえます!");
		$this->addFloatTextParticle($space,new Vector3(223.3,107.3,390.7),"","牢屋");
		$this->addFloatTextParticle($space,new Vector3(211.4,94.0,391.0),"§bロケットに乗り換えて\n他の惑星に行きます","§aショートカット");
		$this->addFloatTextParticle($space, new Vector3(190.1,118.7,438.7),"座りながらゆったりどうぞ","釣り堀！");
	}
}
