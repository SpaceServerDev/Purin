<?php


namespace SSC\Event\Cheat;


use bboyyu51\pmdiscord\Sender;
use bboyyu51\pmdiscord\structure\Content;
use pocketmine\block\Block;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\Listener;
use pocketmine\Server;
use SSC\main;

class XRay implements Listener {

	/**
	 * @var int
	 */
	private $targetamount=50;

	/**
	 * @var $timer int
	 */
	private $timer;

	/**
	 * @var $amount int
	 */
	private $amount;


	/**
	 * @priority LOWEST
	 */

	public function onBreak(BlockBreakEvent $event) {
		if (!$event->getPlayer()->isOp()) {
			if (!$this->isStartTimer($event->getPlayer()->getName())) {
				$this->StartTimer($event->getPlayer()->getName());
			}
			if ($this->isEndTimer($event->getPlayer()->getName())) {
				if ($this->isTarget($event->getPlayer()->getName())) {
					main::getMain()::getPlayerData($event->getPlayer()->getName())->addXrayCount();
					if (main::getMain()::getPlayerData($event->getPlayer()->getName())->getXrayCount() > 3) {
						$event->setCancelled();
						$this->reset($event->getPlayer()->getName());
						$cid = main::getMain()->playerlist->get($event->getPlayer()->getName());
						main::getMain()->addCBan($cid, "XRAYの使用");
						$event->getPlayer()->setBanned(true);
						Server::getInstance()->broadcastMessage("[管理AI] §4HackCamが" . $event->getPlayer()->getName() . "をXRAYの使用で接続禁止処理しました");
						$webhook = Sender::create("https://discordapp.com/api/webhooks/673124550866763806/oCgzjzWDJ6k5AT2H-bh2JPUxqVBCpoqXjBKg-n6qgdx3Hl_QD3c7D9T1PQ13hpozuV3y");
						$content = new Content();
						$content->setText("HackCamが" . $event->getPlayer()->getName() . "をXRAYの使用でBAN処理しました。");
						$webhook->add($content);
						$webhook->setCustomName("BAN");
						Sender::sendAsync($webhook);
						return true;
					}
				}
				$this->reset($event->getPlayer()->getName());
			}

			if ($this->isOre($event->getBlock())) {
				//var_dump(main::getMain()->isLog($event->getBlock()->getFloorX(), $event->getBlock()->getFloorY(), $event->getBlock()->getFloorZ(), $event->getBlock()->getLevel()->getName()));
				if (!main::getMain()->isLog($event->getBlock()->getFloorX(), $event->getBlock()->getFloorY(), $event->getBlock()->getFloorZ(), $event->getBlock()->getLevel()->getName())) {
					$this->addOre($event->getPlayer()->getName());
				}
			}
			//var_dump($this->amount[$event->getPlayer()->getName()]);
			//var_dump($this->timer[$event->getPlayer()->getName()]);
			return true;
		}
	}

	private function isOre(Block $block): bool {
		if ($block->getId() == Block::COAL_ORE or
			$block->getId() == Block::DIAMOND_ORE or
			$block->getId() == Block::EMERALD_ORE or
			$block->getId() == Block::GOLD_ORE or
			$block->getId() == Block::IRON_ORE or
			$block->getId() == Block::REDSTONE_ORE or
			$block->getId() == Block::LAPIS_ORE) {
			return true;
		}
		return false;
	}


	private function isAmount(string $player):bool{
		return isset($this->amount[$player]);
	}

	private function isStartTimer(string $player):bool{
		return !empty($this->timer[$player]);
	}

	private function StartTimer(string $player){
		$this->timer[$player] = time();
		$this->amount[$player]=0;
	}

	private function isEndTimer(string $player):bool{
		$target=$this->timer[$player] + 60;
		return ($target<=time());
	}

	private function addOre(string $player){
		$this->amount[$player]++;
	}

	private function isTarget(string $player):bool{
		return ($this->amount[$player]>=$this->targetamount+$this->isHaste($player)+$this->isEfficiency($player));
	}

	private function reset(string $player){
		$this->amount[$player]=0;
		$this->timer[$player]=0;
	}

	private function isHaste(string $player):int{
		if(Server::getInstance()->getPlayer($player)->hasEffect(3)){
			return 25;
		}
		return 0;
	}

	private function isEfficiency(string $player):int{
		if(Server::getInstance()->getPlayer($player)->getInventory()->getItemInHand()->hasEnchantment(15)){
			return 25;
		}
		return 0;
	}
}