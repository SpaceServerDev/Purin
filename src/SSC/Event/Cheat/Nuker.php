<?php

namespace SSC\Event\Cheat;

use bboyyu51\pmdiscord\Sender;
use bboyyu51\pmdiscord\structure\Content;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\Server;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\Listener;
use SSC\main;

class Nuker implements Listener {

	private $tick;

	/**
	 * @priority LOWEST
	 */
	public function onBreak(BlockBreakEvent $event) {
		//var_dump(Server::getInstance()->getTicksPerSecond());
		if (!$event->getPlayer()->isOp()) {
			if (Server::getInstance()->getTicksPerSecond() > 1.0) {
				if (!isset($this->tick["{$event->getPlayer()->getName()}_1"])) {
					$this->tick["{$event->getPlayer()->getName()}_1"] = Server::getInstance()->getTick();
					if (isset($this->tick["{$event->getPlayer()->getName()}_2"])) {
						/*echo "1";
						var_dump($this->tick["{$event->getPlayer()->getName()}_1"]);
						echo "2";
						var_dump($this->tick["{$event->getPlayer()->getName()}_2"]);*/
						if ($this->CheckCheat($event)) {
							$event->setCancelled();
							main::getMain()::getPlayerData($event->getPlayer()->getName())->addNukeCount();
							if (main::getMain()::getPlayerData($event->getPlayer()->getName())->getNukeCount() > 8) {
								$cid = main::getMain()->playerlist->get($event->getPlayer()->getName());
								$event->getPlayer()->kick("nukeの使用でBANされました。\n自動ですので誤作動でしたら恐れ入りますがdiscordかlobiにて解除申請を\n行ってください。");
								main::getMain()->addCBan($cid, "Nukeの使用");
								$event->getPlayer()->setBanned(true);
								Server::getInstance()->broadcastMessage("[管理AI] §4HackCamが" . $event->getPlayer()->getName() . "をNukeの使用で接続禁止処理しました");
								$webhook = Sender::create("https://discordapp.com/api/webhooks/673124550866763806/oCgzjzWDJ6k5AT2H-bh2JPUxqVBCpoqXjBKg-n6qgdx3Hl_QD3c7D9T1PQ13hpozuV3y");
								$content = new Content();
								$content->setText("HackCamが" . $event->getPlayer()->getName() . "をNukeの使用でBAN処理しました。");
								$webhook->add($content);
								$webhook->setCustomName("BAN");
								Sender::sendAsync($webhook);
								return true;
							}
						}
					}
					unset($this->tick["{$event->getPlayer()->getName()}_2"]);
				} else {


					if (!isset($this->tick["{$event->getPlayer()->getName()}_2"])) {
						$this->tick["{$event->getPlayer()->getName()}_2"] = Server::getInstance()->getTick();
						if (isset($this->tick["{$event->getPlayer()->getName()}_1"])) {
							/*echo "1";
							var_dump($this->tick["{$event->getPlayer()->getName()}_1"]);
							echo "2";
							var_dump($this->tick["{$event->getPlayer()->getName()}_2"]);*/
							if ($this->CheckCheat($event)) {
								$event->setCancelled();
								main::getMain()::getPlayerData($event->getPlayer()->getName())->addNukeCount();
								if(main::getMain()::getPlayerData($event->getPlayer()->getName())->getNukeCount() > 8) {
									$cid = main::getMain()->playerlist->get($event->getPlayer()->getName());
									$event->getPlayer()->kick("nukeの使用でBANされました。\n誤作動でしたら恐れ入りますがdiscordかlobiにて解除申請を\n行ってください。");
									main::getMain()->addCBan($cid, "Nukeの使用");
									$event->getPlayer()->setBanned(true);
									Server::getInstance()->broadcastMessage("[管理AI] §4HackCamが" . $event->getPlayer()->getName() . "をNukeの使用で接続禁止処理しました");
									$webhook = Sender::create("https://discordapp.com/api/webhooks/673124550866763806/oCgzjzWDJ6k5AT2H-bh2JPUxqVBCpoqXjBKg-n6qgdx3Hl_QD3c7D9T1PQ13hpozuV3y");
									$content = new Content();
									$content->setText("HackCamが" . $event->getPlayer()->getName() . "をNukeの使用でBAN処理しました。");
									$webhook->add($content);
									$webhook->setCustomName("BAN");
									Sender::sendAsync($webhook);
									return true;
								}
							}
						}
					}
					unset($this->tick["{$event->getPlayer()->getName()}_1"]);
				}
			}
		}
		return true;
	}

	private function CheckCheat(BlockBreakEvent $event) :bool{
		$max[$event->getPlayer()->getName()] = max([$this->tick["{$event->getPlayer()->getName()}_1"], $this->tick["{$event->getPlayer()->getName()}_2"]]);
		$min[$event->getPlayer()->getName()] = min([$this->tick["{$event->getPlayer()->getName()}_1"], $this->tick["{$event->getPlayer()->getName()}_2"]]);

		if ($max[$event->getPlayer()->getName()] - $min[$event->getPlayer()->getName()] < 2) {
			if (!$event->getPlayer()->hasEffect(3)) {
				if (!$event->getPlayer()->getInventory()->getItemInHand()->hasEnchantment(15)) {
					if($this->checkBlock($event->getBlock()->getId())) {
						return true;
					}
				}
			}

		}
		return false;
	}

	private function checkBlock(int $id):bool{
		switch ($id){
			case 1:
			case 172:
				return true;
			default:
				return false;
		}
	}

}