<?php


namespace SSC\Data;


use pocketmine\utils\Config;

class FishConfig extends Config {

	public function __construct(string $file, int $type = Config::YAML, array $default = [], &$correct = null) {
		parent::__construct($file, $type, $default, $correct);
		if (!$this->exists("シーラカンス")) {
			$this->Firstset();
		}
	}

	public function Firstset() {
		$this->setAll(array(
			"シーラカンス" => 0,
			"メガロドン" => 0,
			"リュウグウノツカイ" => 0,
			"オキナ" => 0,
			"クラーケン" => 0,
			"ダイオウイカ" => 0,
			"赤えい" => 0,
			"カメロケラス" => 0,
			"アスピドケロン" => 0,
			"マンボウ" => 0,
			"ミツクリザメ" => 0,
			"ラブカ" => 0,
			"リフィーシードラゴン" => 0,
			"ガラスイカ" => 0,
			"スターゲイザーフィッシュ" => 0,
			"ブロブフィッシュ" => 0,
			"ホウライエソ" => 0,
			"ミズウオ" => 0,
			"オニハダカ" => 0,
			"ミドリフサアンコウ" => 0,
			"ツボダイ" => 0,
			"デメニギス" => 0,
			"ハプロフリューネ・モリス" => 0,
			"フサアンコウ" => 0,
			"コンニャクウオ" => 0,
			"ダンゴウオ" => 0,
			"ヨミノアシロ" => 0,
			"シンカイクサウオ" => 0,
			"マグロ" => 0,
			"ホオジロザメ" => 0,
			"カジキ" => 0,
			"シャチ" => 0,
			"ドリアスピス" => 0,
			"エデスタス" => 0,
			"ヘリコプリオン" => 0,
			"リオプレウロドン" => 0,
			"ニューネッシー" => 0,
			"キャディ" => 0,
			"マダイ" => 0,
			"ノコギリザメ" => 0,
			"ジンベエザメ" => 0,
			"シュモクザメ" => 0,
			"コバンザメ" => 0,
			"サバ" => 0,
			"サケ" => 0,
			"ブリ" => 0,
			"ウツボ" => 0,
			"カツオ" => 0,
			"スズキ" => 0,
			"オニカサゴ" => 0,
			"アカエイ" => 0,
			"クロサバフグ" => 0,
			"サワラ" => 0,
			"タチウオ" => 0,
			"ボラ" => 0,
			"マルアジ" => 0,
			"ヤリイカ" => 0,
			"ウナギ" => 0,
			"ホッケ" => 0,
			"ネッシー" => 0,
			"イッシー" => 0,
			"クッシー" => 0,
			"モッシー" => 0,
			"チュッシー" => 0,
			"アッシー" => 0,
			"キス" => 0,
			"ニシン" => 0,
			"エビ" => 0,
			"ヒラメ" => 0,
			"ナマズ" => 0,
			"ヒラマサ" => 0,
			"エイ" => 0,
			"コチ" => 0,
			"ライギョ" => 0,
			"サヨリ" => 0,
			"コイ" => 0,
			"デメキン" => 0,
			"ワカサギ" => 0,
			"タイ" => 0,
			"フグ" => 0,
			"ブルーギル" => 0,
			"シーバス" => 0,
			"ハゼ" => 0,
			"ナイルパーチ" => 0,
			"ヘラチョウザメ" => 0,
			"オオメジロサメ" => 0,
			"メコンオオナマズ" => 0,
			"デンキウナギ" => 0,
			"アハイア・グランディ" => 0,
			"アマゾンカワイルカ" => 0,
			"アリゲーターガー" => 0,
			"ベタ" => 0,
			"エンドリケリー" => 0,
			"ピラルク" => 0,
			"ドラド" => 0,
			"アロワナ" => 0,
			"ティラピア" => 0,
			"河ふぐ" => 0,
			"ニジマス" => 0,
			"ケツギョ" => 0,
			"カワアナゴ" => 0,
			"ハヤ" => 0,
			"アユ" => 0,
			"サクラマス" => 0,
			"イトウ" => 0,
			"ドジョウ" => 0,
		));
		$this->save();
	}

	public function getPublic(string $tag): bool {
		$data = $this->getAll();
		if (isset($data[$tag])) {
			if ($data[$tag] == 0) {
				return false;
			}
			return true;
		}
		return false;
	}

	public function addFish(string $tag,int $amount){
		$data = $this->getAll();
		if (isset($data[$tag])) {
			$data[$tag] = $data[$tag]+$amount;
			$this->setAll($data);
			$this->save();
		}
	}

	public function getFish(string $tag):int{
		if($this->exists($tag)){
			return $this->get($tag);
		}
		return 0;
	}

	public function getAllFish():int{
		$amount=0;
		foreach($this->getAll() as $fish=>$number){
			if($number!=0){
				$amount+=1;
			}
		}
		return $amount;
	}

}