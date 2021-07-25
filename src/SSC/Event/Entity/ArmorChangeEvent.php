<?php


namespace SSC\Event\Entity;


use pocketmine\entity\Effect;
use pocketmine\entity\EffectInstance;
use pocketmine\event\entity\EntityArmorChangeEvent;
use pocketmine\event\Listener;

class ArmorChangeEvent implements Listener {

	public function onArmorChange(EntityArmorChangeEvent $event) {
		$player = $event->getEntity();
		$name = $player->getName();
		$item = $event->getNewItem();
		$iname = $item->getCustomName();


		if (mb_strstr($iname, '具', true) == "§a伝説の宝") {
			if (strstr($iname, ":") == ":" . $name) {
				$player->addEffect(new EffectInstance(Effect::getEffect(3), 2147483647, 1, false));
			} else {
				$player->sendMessage("§aプレイヤー名が適合されません。");
			}
		} else if ($iname === "§aエフェクト防具頭：暗視") {
			$player->addEffect(new EffectInstance(Effect::getEffect(16), 2147483647, 1, false));
		} else if ($iname === "§aエフェクト防具足：移動速度上昇" || $iname=="§bヘルメスの靴") {
			if($iname=="§bヘルメスの靴"){
				if($player->getLevel()->getFolderName()==="pvp"){
					$player->addEffect(new EffectInstance(Effect::getEffect(1), 2147483647, 1, false));
				}
			}
			$player->addEffect(new EffectInstance(Effect::getEffect(1), 2147483647, 1, false));
		}else if($iname==="§aエフェクト防具胴：火炎耐性"or$iname==="§aエフェクト防具腰：火炎耐性"){
			$player->addEffect(new EffectInstance(Effect::getEffect(12), 2147483647, 1, false));
		}else if($iname==="§aエフェクト防具腰：跳躍力上昇"){
			$player->addEffect(new EffectInstance(Effect::getEffect(8), 2147483647, 1, false));
		}
	}

}