<?php


namespace SSC\Gacha\Event;


use SSC\Gacha\Gacha;
use SSC\Item\Item_AK47;
use SSC\Item\Item_AWM;
use SSC\Item\Item_RPG7;
use SSC\Item\Item_UZI;

class GunGacha implements Gacha {



	public function turn(): array {
		$rand=mt_rand(1,4);
		switch ($rand){
			case 1:
				return [Item_AK47::get(),5,"§4アサルトライフル"];
			break;
			case 2:
				return [Item_AWM::get(),5,"§4スナイパーライフル"];
			break;
			case 3:
				return [Item_RPG7::get(),5,"§4ロケットランチャー"];
			break;
			case 4:
				return [Item_UZI::get(),5,"§4サブマシンガン"];
			break;

		}
		return [];
	}
}