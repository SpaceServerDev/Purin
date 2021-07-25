<?php


namespace SSC\Data;


class EXShop {

	private $shop=[];

	private $shop_players=[];

	public function init(){
		$this->shop=[mt_rand(1,12),mt_rand(13,25),mt_rand(26,38)];
	}

	public function getItemNames(){
		$first="";
		$second="";
		$third="";
		//貴重アイテム
		switch ($this->shop[0]){
			case 1:
				$first="修復クリーム　1個";
				break;
			case 2:
				 $first="修復クリーム　2個";
				 break;
			case 3:
				 $first="修復クリーム　3個";
				 break;
			case 4:
				 $first="ガチャチケット5枚";
				 break;
			case 5:
				$first="ガチャチケット10枚";
				break;
			case 6:
				$first="ガチャチケット20枚";
				break;
			case 7:
				$first="名札";
				break;
			case 8:
				$first="花火";
				break;
			case 9:
				$first="エメラルド鉱石　3個";
				break;
			case 10:
				$first="エンダーチェスト";
				break;
			case 11:
				$first="堕天使の翼";
				break;
			case 12:
				$first="紅い黒曜石";
				break;
		}
		//日常アイテム
		switch ($this->shop[1]){}
		//ツール
		switch ($this->shop[2]){}
		return [$first,$second,$third];
	}

	public function getUseShopPlayer(int $id, string $name){
		return isset($this->shop_players[$id][$name]);
	}

	public function changeUseShopPlayer(int $id,string $name){
		$this->shop_players[$id][$name]=true;
	}


}