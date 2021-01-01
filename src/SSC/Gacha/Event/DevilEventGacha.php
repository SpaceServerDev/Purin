<?php


namespace SSC\Gacha\Event;

use SSC\Gacha\Gacha;
use SSC\Item\DevilBoots;
use SSC\Item\DevilBow;
use SSC\Item\DevilChestPlate;
use SSC\Item\DevilHelmet;
use SSC\Item\DevilLeggings;
use SSC\Item\DevilPickAxe;
use SSC\Item\DevilShovel;
use SSC\Item\DevilSword;

class DevilEventGacha implements Gacha {



	public function turn(): array {
		$rand=mt_rand(1,8);
		switch ($rand){
			case 1:
				return [DevilSword::get(),5,"§4悪魔の魔剣 -レーヴァテイン-"];
			break;
			case 2:
				return [DevilBow::get(),5,"§4悪魔の弓 -サキュバスの長弓-"];
			break;
			case 3:
				return [DevilHelmet::get(),5,"§4悪魔の兜 -堕天使ルシファーの兜-"];
			break;
			case 4:
				return [DevilChestPlate::get(),5,"§4悪魔の衣 -サタンの衣-"];
			break;
			case 5:
				return [DevilLeggings::get(),5,"§4悪魔のタイツ -ベリアルのタイツ-"];
			break;
			case 6:
				return [DevilBoots::get(),5,"§4悪魔の靴 -ベルゼブブの靴-"];
			break;
			case 7:
				return [DevilPickAxe::get(),5,"§4悪魔のツルハシ -鬼の棍棒-"];
			break;
			case 8:
				return [DevilShovel::get(),5,"§4悪魔のスコップ -イフリートの魔術-"];
			break;
		}
		return [];
	}
}