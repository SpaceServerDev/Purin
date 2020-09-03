<?php


namespace SSC\Event\player;


use onebone\economyapi\EconomyAPI;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerLoginEvent;
use pocketmine\utils\Config;
use SSC\Data\PlayerConfigManager;
use SSC\main;
use SSC\PlayerEvent;

class LoginEvent implements Listener {

	public function onLogin(PlayerLoginEvent $event) {
		$name = $event->getPlayer()->getName();
		$playerdata = new PlayerEvent(main::getMain(), $event->getPlayer());
		main::getMain()->playerdata[$name] = $playerdata;
		main::getMain()->itiji[$name] = null;
		main::getMain()->login[$name] = 0;

		/*初inの際の情報を代入*/
		$fi = main::getMain()->getDataFolder() . "Player";
		if (!(file_exists($fi))) @mkdir($fi, 0755, true);
		$file = "$fi/$name.yml";
		$playerconfig = new Config($file, Config::YAML);

		$fi = main::getMain()->getDataFolder() . "Mywarp";
		if (!(file_exists($fi))) @mkdir($fi, 0755, true);
		$file = "$fi/$name.yml";
		new Config($file, Config::YAML);

		$fi = main::getMain()->getDataFolder() . "Inventory";
		if (!(file_exists($fi))) @mkdir($fi, 0755, true);
		$file = "$fi/$name.yml";
		new Config($file, Config::YAML);


		if (!($playerconfig->exists("3"))) {
			if (!($playerconfig->exists($name))) {
				$ip = $event->getPlayer()->getAddress();
				$dt = date("Y/m/d G:i:s");
				$gg = $event->getPlayer()->getLocale();
				$xuid = $event->getPlayer()->getXuid();
				$playerconfig->set($name, array(
					"NAME" => $name,
					"IP" => $ip,
					"KENGEN" => "鯖民",
					"HITOKOTO" => "よろしくおねがいします",
					"MONEY" => "1000",
					"LASTLOGIN" => $dt,
					"KEIKOKU" => "0",
					"SYOUGOU" => $name,
					"ATAMA" => $name,
					"JOB" => "なし",
					"GENGO" => $gg,
					"XUID" => $xuid,
					"KILL" => 0,
					"DEATH" => 0,
					"RED" => 0,
					"BLUE" => 0,
					"GREEN" => 0,
					"ORANGE" => 0,
					"YELLOW" => 0,
					"WHITE" => 0,
					"PURPLE" => 0,
					"BROWN" => 0,
					"BLACK" => 0,
					"GOLD" => 0,
					"SILVER" => 0,
					"PINK" => 0,
					"LEGEND" => 0,
					"SPECIAL" => 0,
					"BEAUTIFUL" => 0,
					"ZERO" => 0,
					"EVENT" => 0,
					"Mars" => false,
					"Mercury" => false,
					"Neptune" => false,
					"Uranus" => false,
					"Venus" => false,
					"Sun" => false,
					"KOUSEKI" => 0,
					"MOKUTEKI" => 30,
					"EXP" => 0,
					"LEVEL" => 1,
					"EXPLEVEL" => 0,
				));
				$playerconfig->save();
				main::getMain()->update->set($name);
				main::getMain()->update->save();
				main::getMain()->itiji[$name] = $name;
			} else {
				if (!main::getMain()->update->exists($name)) {
					$uf = main::getMain()->getDataFolder() . "Player" . "/{$name}.yml";
					$user = new Config($uf, Config::YAML);
					$dt = date("Y/m/d G:i:s");
					$money = EconomyAPI::getInstance()->myMoney($name);
					$kikk = $user->get($name)["KEIKOKU"];
					$perm = $user->get($name)["KENGEN"];
					$hitokoto = $user->get($name)["HITOKOTO"];
					$ip = $event->getPlayer()->getAddress();
					$syougou = $user->get($name)["SYOUGOU"];
					$atama = $user->get($name)["ATAMA"];
					$gg = $user->get($name)["GENGO"];
					$xuid = $user->get($name)["XUID"];

					$user->set($name, array(
						"NAME" => $name,
						"IP" => $ip,
						"KENGEN" => $perm,
						"HITOKOTO" => $hitokoto,
						"MONEY" => $money,
						"LASTLOGIN" => $dt,
						"KEIKOKU" => $kikk,
						"SYOUGOU" => $syougou,
						"ATAMA" => $atama,
						"GENGO" => $gg,
						"XUID" => $xuid,
						"JOB" => "なし",
						"KILL" => 0,
						"DEATH" => 0,
						"RED" => 0,
						"BLUE" => 0,
						"GREEN" => 0,
						"ORANGE" => 0,
						"YELLOW" => 0,
						"WHITE" => 0,
						"PURPLE" => 0,
						"BROWN" => 0,
						"BLACK" => 0,
						"GOLD" => 0,
						"SILVER" => 0,
						"PINK" => 0,
						"LEGEND" => 0,
						"SPECIAL" => 0,
						"BEAUTIFUL" => 0,
						"ZERO" => 0,
						"EVENT" => 0,
						"Mars" => false,
						"Mercury" => false,
						"Neptune" => false,
						"Uranus" => false,
						"Venus" => false,
						"Sun" => false,
						"KOUSEKI" => 0,
						"MOKUTEKI" => 30,
						"EXP" => 0,
						"LEVEL" => 1,
						"EXPLEVEL" => 0,

					));
					$user->save();
					main::getMain()->update->set($name);
					main::getMain()->update->save();

				}
			}
		}
		if ($playerdata->configVersion() !== PlayerConfigManager::UPDATE_VERSION) {
			$playerdata->updateConfig();
		}
	}
}