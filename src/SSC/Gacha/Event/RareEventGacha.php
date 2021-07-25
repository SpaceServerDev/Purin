<?php


namespace SSC\Gacha\Event;

use pocketmine\item\Item;
use SSC\Gacha\Gacha;

class RareEventGacha implements Gacha {

	public function turn(): array {
		switch (mt_rand(1,13)) {
			case 1:
				$item = Item::get(364, 0, 64);
				return [$item,2,"§aステーキ"];
			case 2:
				$item = Item::get(400, 0, 64);
				return [$item,2,"§aパンプキンパイ"];
			case 3:
				$item = Item::get(412, 0, 64);
				return [$item,2,"§a焼きうさぎ"];
			case 4:
				$item = Item::get(322, 1, 15);
				return [$item,2,"§aエンチャント金りんご"];
			case 5:
				$item = Item::get(388, 0, 16);
				return [$item,2,"§aエメラルド"];
			case 6:
				$item = Item::get(396, 0, 12);
				return [$item,2,"§a金の人参"];
			case 7:
				$item = Item::get(357, 0, 64);
				return [$item,2,"§aクッキー"];
			case 8:
				$item = Item::get(282, 0, 1);
				$item->setCustomName("§aとんこつラーメン");
				$rand=mt_rand(1,100000);
				$item->getNamedTag()->setInt("ramen",$rand);
				return [$item,2,"§aとんこつラーメン"];
			case 9:
				$item = Item::get(413, 0, 1);
				$item->setCustomName("§a醤油ラーメン");
				$rand=mt_rand(1,100000);
				$item->getNamedTag()->setInt("ramen",$rand);
				return [$item,2,"§a醤油ラーメン"];
			case 10:
				$item = Item::get(459, 0, 1);
				$item->setCustomName("§a担々麺");
				$rand=mt_rand(1,100000);
				$item->getNamedTag()->setInt("ramen",$rand);
				return [$item,2,"§a担々麺"];
			case 11:
				$item = Item::get(373, 0, 1);
				$item->setCustomName("§a§lMONSTER ENERGY");
				return [$item,2,"§aモンスターエナジー"];
			case 12:
				$item = Item::get(346, 0, 1);
				return [$item,2,"§a釣り竿"];
			case 13:
				$item = Item::get(373, 37, 1);
				$item->setCustomName("§1§lBeaujolais Nouveau");
				return [$item,2,"§aボジョレーヌーボー"];
		}
		return [];
	}
}