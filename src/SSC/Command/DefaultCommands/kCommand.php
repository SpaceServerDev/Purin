<?php


namespace SSC\Command\DefaultCommands;


use pocketmine\command\CommandSender;
use pocketmine\command\defaults\VanillaCommand;
use pocketmine\command\utils\CommandException;
use pocketmine\Server;

class kCommand extends VanillaCommand {

	public function __construct(string $name, string $description, string $usageMessage) {
		parent::__construct($name, $description, $usageMessage);
	}

	/**
	 * @param string[] $args
	 *
	 * @return mixed
	 * @throws CommandException
	 */
	public function execute(CommandSender $sender, string $commandLabel, array $args) {
		if($commandLabel==="k"){
			$kaomoji=["§a|^・ω・)/ ﾊﾛｰ♪","§aｺﾝﾆﾁ波！( ゜o）＜≡≡((((☆ｶﾞｺﾞｰﾝ☆）>o<)ノ ｵｩｰ","§a＝＝((( (/* ^^)/ ﾊﾛｰｰｰ!!","§aｺﾝﾁｬｯ(/∀＼*)ｷｬｯｷｬｯ","§a(｡･ω･)ﾉ こんてぃわー","§a(*´_｀)ﾉ ﾔﾎﾟｰ♪","§aこん(/・ω・)/にゃちゎ","§a(。･o･｡)ﾉ こんにちゎぁ♪","§a(*◎Ｕ∀Ｕp)q♪ﾁﾜｧ♪","§a(√･ω･) ちーっす"];
		}elseif($commandLabel==="otu"){
			$kaomoji=["§6(≧∇≦) ｵﾂｶﾚｻﾏー♪","§6お疲れ様(*･ω･*)ゞﾃﾞｼ!!","§6ｵﾂｶﾚｰ！Σd(ゝ∀･)","§6ヾ(*´I｀)ﾉ ｡ﾟ･+:.おつかれさま･.:+･ﾟ｡","§6(。っ・Д・)っ 【お疲れさまぁ♪】","§6ヽ(。ゝω・)ノﾎﾟｨｯ⌒【☆:*:･ｵﾂｶﾚｻﾏ･:*ﾟ☆】","§6ｼｭｯ!!(´･ω･｀)ﾉ≡【☆:*:･おつかれさま･:*ﾟ☆】","§6ｵﾂｶﾚｰ ヾ(=ﾟωﾟ)ゞ","§6おつかれー！(*´Ｉ ｀*)ﾉｼ","§6((*´ゝз･)ﾉﾞお疲れ様♪"];
		}else{
			$kaomoji=["§b(@＾皿＾@)ゞ『ォチﾏｽ!!』*｡+ﾟ★","§b(○´∇｀)ﾉ☆ﾟ+.退室ｲﾀｼﾏｽ.+ﾟ☆","§b★ﾟ+o｡(☆´･ω･)σ《ぉちます》a(･ω･｀★)｡o+ﾟ☆","§bp【+ﾟ*退室ｼﾁｬｳｮ:ﾟ+】qД｀｡)｡o.ﾟ｡","§bヾ(´Д｀q･ﾟ･ﾊﾞィﾊﾞィ! おちるﾈェｯ!!*:ﾟ･☆","§bヾ(★´Å｀★)σ【ｵﾁﾙﾈェ～】｡o+☆","§b人･∀･*).o0((ｿﾛｿﾛ☆落ﾁﾏｽ★))","§bｵﾁﾙﾈ～☆εε==≡ヾ(★,,´∀'｀)ﾉ))"];
		}
		Server::getInstance()->broadcastMessage("{$sender->getName()}>> ".$kaomoji[array_rand($kaomoji,1)]);
		return true;
	}
}