<?php


namespace SSC\Gacha\Normal;


use pocketmine\item\Item;
use SSC\Gacha\Gacha;

class RNormalGacha implements Gacha {

	public function turn(): array {
		switch (mt_rand(1,8)) {
			case 1:
				$item = Item::get(364, 0, 64);
				return [$item,2,"ステーキ"];
			case 2:
				$item = Item::get(400, 0, 64);
				return [$item,2,"パンプキンパイ"];
			case 3:
				$item = Item::get(412, 0, 64);
				return [$item,2,"焼きうさぎ"];
			case 4:
				$item = Item::get(322, 1, 15);
				return [$item,2,"エンチャント金りんご"];
			case 5:
				$item = Item::get(388, 0, 18);
				return [$item,2,"エメラルド"];
			case 6:
				$item = Item::get(396, 0, 12);
				return [$item,2,"金の人参"];
			case 7:
				$item = Item::get(357, 0, 64);
				return [$item,2,"クッキー"];
			case 8:
				$item = Item::get(414, 0, 32);
				$item->setCustomName("ミミズ");
				return [$item,2,"ミミズ"];
		}
		return [];
	}
}