<?php


namespace SSC\Gacha\Normal;


use pocketmine\item\Item;
use SSC\Gacha\Gacha;

class URNormalGacha implements Gacha {

	public function turn(): array {
		switch (mt_rand(1, 7)) {
			case 1:
				$item = Item::get(264, 0, 16);
				return [$item,3,"ダイヤモンド"];
			case 2:
				$item = Item::get(266, 0, 32);
				return [$item,3,"金"];
			case 3:
				$item = Item::get(265, 0, 64);
				return [$item,3,"鉄"];
			case 4:
				$item = Item::get(263, 0, 64);
				return [$item,3,"石炭"];
			case 5:
				$item = Item::get(91, 0, 64);
				return [$item,3,"ジャック・オ・ランタン"];
			case 6:
				$item = Item::get(130, 0, 1);
				return [$item,3,"エンダーチェスト"];
			case 7:
				$item = Item::get(152, 0, 25);
				return [$item,3,"レッドストーンブロック"];
		}
		return[];
	}
}