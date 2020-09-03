<?php


namespace SSC\Form;


use pocketmine\form\Form;
use pocketmine\form\FormValidationException;
use pocketmine\Player;
use SSC\Data\FishSizeConfig;
use SSC\Data\RankConfig;
use SSC\main;
use SSC\PlayerEvent;

class RankForm implements Form{

	/**
	 * @var PlayerEvent
	 */
	private $pe;

	public function __construct(PlayerEvent $pe) {
		$this->pe=$pe;
	}


	/**
	 * Handles a form response from a player.
	 *
	 * @param mixed $data
	 *
	 * @throws FormValidationException if the data could not be processed
	 */
	public function handleResponse(Player $player, $data): void {
		if(!is_numeric($data)) return;
		switch ($data){
			case 0:
				$player->sendForm(new AllRankForm($this->pe,"login","ログイン日数","日"));
			return;
			case 1:
				$player->sendForm(new StayRankForm($this->pe));
			return;
			case 2:
				$player->sendForm(new AllRankForm($this->pe,"repeat","連続ログイン日数","日"));
			return;
			case 3:
				$player->sendForm(new AllRankForm($this->pe,"break","ブロック破壊",));
			return;
			case 4:
				$player->sendForm(new AllRankForm($this->pe,"peace","プロック設置"));
			return;
			case 5:
				$player->sendForm(new AllRankForm($this->pe,"trappist","トラピスト整地数"));
			return;
			case 6:
				$player->sendForm(new AllRankForm($this->pe,"flower","花を植えた数","輪"));
			return;
			case 7:
				$player->sendForm(new AllRankForm($this->pe,"wood","木を切る"));
			return;
			case 8:
				$player->sendForm(new OreRankForm($this->pe));
			return;
			case 9:
				$player->sendForm(new AllRankForm($this->pe,"gacha","ガチャの回数","回"));
			return;
			case 10:
				$player->sendForm(new AllRankForm($this->pe,"shopping","買い物回数","回",));
			return;
			case 11:
				$player->sendForm(new AllRankForm($this->pe,"slot","スロット回数","回"));
			return;
			case 12:
				$player->sendForm(new AllRankForm($this->pe,"kill","キル数","回"));
			return;
			case 13:
				$player->sendForm(new AllRankForm($this->pe,"killst","最大キルストリーク","回"));
			return;
			case 14:
				$player->sendForm(new AllRankForm($this->pe,"fishing","釣りした回数","回"));
			return;
			case 15:
				$player->sendForm(new FishRankForm($this->pe));
			return;
			case 16:
				$player->sendForm(new AllRankForm($this->pe,"money","所持金ランキング","￥"));
			return;
		}
	}

	/**
	 * Specify data which should be serialized to JSON
	 * @link https://php.net/manual/en/jsonserializable.jsonserialize.php
	 * @return mixed data which can be serialized by <b>json_encode</b>,
	 * which is a value of any type other than a resource.
	 * @since 5.4.0
	 */
	public function jsonSerialize() {
		$buttons = [
			['text' => "ログイン日数",],
			['text' => "サーバー滞在時間",],
			['text' => "連続ログイン日数",],
			['text' => "ブロック破壊数",],
			['text' => "ブロック設置数",],
			['text' => "トラピストでの整地数",],
			['text' => "花を植えた数",],
			['text' => "原木破壊数",],
			['text' => "各鉱石破壊数",],
			['text' => "ガチャ引いた回数",],
			['text' => "買い物した回数",],
			['text' => "スロットした回数",],
			['text' => "キルした回数",],
			['text' => "キルストリーク回数",],
			['text' => "釣りをした回数",],
			['text' => "魚の大きさランキング",],
			['text' => "所持金ランキング",],
		];
		return [
			'type'=>'form',
			'title'=>'§e§lTOPRANKING',
			'content'=>"§a各ランキングを確認できます",
			'buttons'=>$buttons,
		];
	}
}

class AllRankForm implements Form {

	/**
	 * @var PlayerEvent
	 */
	private $pe;
	/**
	 * @var string
	 */
	private $rankdata;
	/**
	 * @var string
	 */
	private $unit;
	/**
	 * @var int
	 */
	private $data;
	/**
	 * @var string
	 */
	private $rank;

	public function __construct(PlayerEvent $pe, string $rankdata, string $rank, string $unit = "個", int $data = 0) {
		$this->pe = $pe;
		$this->rank = $rank;
		$this->rankdata = $rankdata;
		$this->unit = $unit;
		$this->data = $data;
	}

	/**
	 * Handles a form response from a player.
	 *
	 * @param mixed $data
	 *
	 * @throws FormValidationException if the data could not be processed
	 */
	public function handleResponse(Player $player, $data): void {
		if (!is_bool($data)) return;
		if ($data) {
			if ($this->data == 1) {
				$player->sendForm(new OreRankForm($this->pe));
				return;
			}
			$player->sendForm(new RankForm($this->pe));
		}
		return;
	}

	/**
	 * Specify data which should be serialized to JSON
	 * @link https://php.net/manual/en/jsonserializable.jsonserialize.php
	 * @return mixed data which can be serialized by <b>json_encode</b>,
	 * which is a value of any type other than a resource.
	 * @since 5.4.0
	 */
	public function jsonSerialize() {
		$content = "";
		$cls = new RankConfig(main::getMain()->getDataFolder() . "Rank/{$this->rankdata}.yml");
		$cls->reload();
		if (empty($cls->getTopRank(100))) {
			$content = "NODATA";
		}
		$rank = 1;
		list($myrank, $vle) = $cls->getPlayerRank($this->pe->getName());
		if ($myrank != -1) {
			if ($myrank != false) {
				$content = $content . "§a自分のランク {$myrank}位 {$this->pe->getName()}:{$vle}{$this->unit}\n\n§e===全体ランキング===§f\n";
			}
		}else{
			$content = $content . "§a自分のランク X位 {$this->pe->getName()}:X{$this->unit}\n\n§e===全体ランキング===§f\n";
		}
		foreach ($cls->getTopRank(100) as $name => $data) {
			$content = $content . "{$rank}位 {$name}:{$data}{$this->unit}\n";
			$rank++;
		}

		return [
			'type' => 'modal',
			'title' => '§e§lTOPRANKING　' . $this->rank,
			'content' => $content,
			'button1' => "ほかを見る",
			'button2' => "おわる"
		];
	}
}

class StayRankForm implements Form{

	/**
	 * @var PlayerEvent
	 */
	private $pe;


	public function __construct(PlayerEvent $pe) {
		$this->pe=$pe;
	}

	/**
	 * Handles a form response from a player.
	 *
	 * @param mixed $data
	 *
	 * @throws FormValidationException if the data could not be processed
	 */
	public function handleResponse(Player $player, $data): void {
		if(!is_bool($data)) return;
		if($data){
			$player->sendForm(new RankForm($this->pe));
		}
	}

	/**
	 * Specify data which should be serialized to JSON
	 * @link https://php.net/manual/en/jsonserializable.jsonserialize.php
	 * @return mixed data which can be serialized by <b>json_encode</b>,
	 * which is a value of any type other than a resource.
	 * @since 5.4.0
	 */
	public function jsonSerialize() {
		$content="";
		$cls=new RankConfig(main::getMain()->getDataFolder()."Rank/stay.yml");
		$cls->reload();
		if(empty($cls->getTopRank(100))){
			$content="NODATA";
		}
		$rank=1;
		list($myrank,$vle)=$cls->getPlayerRank($this->pe->getName());
		if($myrank!=-1) {
			if ($myrank != false) {
				$h = floor($vle / 3600);
				$m = floor(($vle / 60) % 60);
				$content = $content . "§a自分のランク {$myrank}位 {$this->pe->getName()}:{$h}時間{$m}分\n\n§e===全体ランキング===§f\n";
			}
			foreach ($cls->getTopRank(100) as $name => $data) {
				$h = floor($data / 3600);
				$m = floor(($data / 60) % 60);
				$content = $content . "{$rank}位 {$name}:{$h}時間{$m}分\n";
				$rank++;
			}
		}
		return[
			'type'=>'modal',
			'title'=>'§e§lTOPRANKING'."　滞在時間",
			'content'=>$content,
			'button1'=>"ほかを見る",
			'button2'=>"おわる"
		];
	}
}

class OreRankForm implements Form{

	/**
	 * @var PlayerEvent
	 */
	private $pe;

	public function __construct(PlayerEvent $pe) {
		$this->pe=$pe;
	}


	/**
	 * Handles a form response from a player.
	 *
	 * @param mixed $data
	 *
	 * @throws FormValidationException if the data could not be processed
	 */
	public function handleResponse(Player $player, $data): void {
		if(!is_numeric($data)) return;
		switch ($data){
			case 0:
				$player->sendForm(new RankForm($this->pe));
			return;
			case 1:
				$player->sendForm(new AllRankForm($this->pe,"emerald","エメラルド","個",1));
			return;
			case 2:
				$player->sendForm(new AllRankForm($this->pe,"coal","石炭","個",1));
			return;
			case 3:
				$player->sendForm(new AllRankForm($this->pe,"lapis","ラピスラズリ","個",1));
			return;
			case 4:
				$player->sendForm(new AllRankForm($this->pe,"iron","鉄","個",1));
			return;
			case 5:
				$player->sendForm(new AllRankForm($this->pe,"redstone","赤石","個",1));
			return;
			case 6:
				$player->sendForm(new AllRankForm($this->pe,"gold","金","個",1));
			return;
			case 7:
				$player->sendForm(new AllRankForm($this->pe,"diamond","ダイヤモンド","個",1));
			return;
		}
	}

	/**
	 * Specify data which should be serialized to JSON
	 * @link https://php.net/manual/en/jsonserializable.jsonserialize.php
	 * @return mixed data which can be serialized by <b>json_encode</b>,
	 * which is a value of any type other than a resource.
	 * @since 5.4.0
	 */
	public function jsonSerialize() {
		$buttons = [
			['text' => "戻る",],
			['text' => "エメラルド",],
			['text' => "石炭",],
			['text' => "ラピスラズリ",],
			['text' => "鉄",],
			['text' => "赤石",],
			['text' => "金",],
			['text' => "ダイヤモンド",],
		];
		return [
			'type'=>'form',
			'title'=>'§e§lTOPRANKING',
			'content'=>"§a各ランキングを確認できます",
			'buttons'=>$buttons,
		];
	}
}

class FishRankForm implements Form {

	/**
	 * @var PlayerEvent
	 */
	private $pe;
	/**
	 * @var array
	 */
	private $fishs=["シーラカンス","メガロドン","リュウグウノツカイ","オキナ","クラーケン","ダイオウイカ","赤えい","カメロケラス","アスピドケロン","マグロ",
			"ホオジロザメ","カジキ","シャチ","ドリアスピス","エデスタス","ヘリコプリオン","リオプレウロドン","ニューネッシー","キャディ","ウナギ","ホッケ",
			"ネッシー","イッシー","クッシー","モッシー","チュッシー","アッシー","ナイルパーチ","ヘラチョウザメ","オオメジロサメ","メコンオオナマズ",
			"デンキウナギ","アハイア・グランディ","アマゾンカワイルカ","アリゲーターガー",];

	public function __construct(PlayerEvent $pe) {
		$this->pe = $pe;
	}


	/**
	 * Handles a form response from a player.
	 *
	 * @param mixed $data
	 *
	 * @throws FormValidationException if the data could not be processed
	 */
	public function handleResponse(Player $player, $data): void {
		if (!is_numeric($data)) return;
		switch ($data) {
			case 0:
				$player->sendForm(new RankForm($this->pe));
				return;
			default:
				$player->sendForm(new FishAllRankForm($this->pe, $this->fishs[$data-1]));
				return;
		}
	}

	/**
	 * Specify data which should be serialized to JSON
	 * @link https://php.net/manual/en/jsonserializable.jsonserialize.php
	 * @return mixed data which can be serialized by <b>json_encode</b>,
	 * which is a value of any type other than a resource.
	 * @since 5.4.0
	 */
	public function jsonSerialize() {

		$buttons = [
			['text' => "戻る",],
		];

		foreach ($this->fishs as $fish){
			$buttons[]=["text"=>$fish];
		}
		return [
			'type' => 'form',
			'title' => '§e§lTOPRANKING',
			'content' => "§a各ランキングを確認できます",
			'buttons' => $buttons,
		];
	}
}
class FishAllRankForm implements Form {

	/**
	 * @var PlayerEvent
	 */
	private $pe;
	/**
	 * @var string
	 */
	private $rankdata;


	public function __construct(PlayerEvent $pe,string $rankdata) {
		$this->pe=$pe;
		$this->rankdata=$rankdata;
	}

	/**
	 * Handles a form response from a player.
	 *
	 * @param mixed $data
	 *
	 * @throws FormValidationException if the data could not be processed
	 */
	public function handleResponse(Player $player, $data): void {
		if (!is_bool($data)) return;
		if ($data) {
			$player->sendForm(new FishRankForm($this->pe));
		}
		return;
	}

	/**
	 * Specify data which should be serialized to JSON
	 * @link https://php.net/manual/en/jsonserializable.jsonserialize.php
	 * @return mixed data which can be serialized by <b>json_encode</b>,
	 * which is a value of any type other than a resource.
	 * @since 5.4.0
	 */
	public function jsonSerialize() {
		$content = "";
		$cls = new FishSizeConfig();
		$cls->reload();
		if (empty($cls->getTopRank($this->rankdata, 100))) {
			$content = "NODATA";
		}
		$rank = 1;
		$content=$content."§e===全体ランキング===§f\n";
		foreach ($cls->getTopRank($this->rankdata) as $name => $data) {
			$content = $content . "{$rank}位 {$name}:{$data}cm\n";
			$rank++;
		}
		return [
			'type' => 'modal',
			'title' => '§e§lTOPRANKING ' . $this->rankdata,
			'content' => $content,
			'button1' => "ほかを見る",
			'button2' => "おわる"
		];
	}
}