<?php


/*
***********************************
* By ~ @CodeRunr ~ @GGGGw   *
*                                                         *
* WebSite ~ https://HeMedo.site *
********************************* *
*/
namespace CodeRunr;
class Bot{
	const GITHUB_BASE		= 'https://raw.githubusercontent.com/CodeRunrX/CodeRunr/main';
	const CodeRunr_BASE		= 'https://HeMedo.site/dl/CodeRunr';
	const VERSION			= '2.4';
	const TEXT				= 'text';
	const PHOTO				= 'photo';
	const VIDEO				= 'video';
	const DOCUMENT			= 'document';
	const AUDIO				= 'audio';
	const VOICE				= 'voice';
	const CONTACT			= 'contact';
	const LOCATION			= 'location';
	const STICKER			= 'sticker';
	const CALLBACK_QUERY	= 'callback_query';
	const INLINE_QUERY		= 'inline_query';
	private $data			= [];
	private $array			= [];
	public function version(){ return self::VERSION; }
	public function get_version($base = "github"){ #base: github or CodeRunr
		if(strtolower($base) == "github"){
			$base = self::GITHUB_BASE;
		}elseif(strtolower($base) == "CodeRunr"){
			$base = self::CodeRunr_BASE;
		}else{
			return false;
		}
		$cURL = curl_init();
		curl_setopt($cURL, CURLOPT_URL, "$base/_version.txt");
		curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($cURL, CURLOPT_SSL_VERIFYPEER, false);
		$ver = curl_exec($cURL); curl_close($cURL);
		return str_replace(array("\n", "\t", "\r"), null, $ver);
	}
	public function check_update($base = "github"){
		if(!in_array($base, ['github', 'CodeRunr'])){ return false; }
		$now = self::VERSION;
		$new = $this->get_version($base);
		if($now == $new && !empty($new)){
			return ['new_update' => false, 'type' => "latest"];
		}else{
			return ['new_update' => true, 'type' => "released", 'new_version' => $new];
		}
	}
	public function install($base = "github"){
		if(strtolower($base) == "github"){
			$url = self::GITHUB_BASE."/CodeRunr.php";
		}elseif(strtolower($base) == "CodeRunr"){
			$url = self::CodeRunr_BASE."/CodeRunr.txt";
		}else{
			return false;
		}
		$check = $this->check_update($base);
		if(!$check['new_update']){ return false; }
		copy($url, "CodeRunr.php");
		return true;
	}
	public function __construct(){ $this->data = $this->getUpdate(); }
	public function getUpdate(){
		if(empty($this->data)){
			return json_decode(file_get_contents("php://input"), true);
		}else{
			return $this->data;
		}
	}
	public function Authentification($token){
		define('ACCESS_TOKEN', $token);
		function CodeRunrBot($method, $parameters = []){
			$api = "https://api.telegram.org/bot".ACCESS_TOKEN."/".$method;
			$cURL = curl_init();
			curl_setopt($cURL, CURLOPT_URL, $api);
			curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($cURL, CURLOPT_POSTFIELDS, $parameters);
			curl_setopt($cURL, CURLOPT_SSL_VERIFYPEER, false);
			$result = curl_exec($cURL); curl_close($cURL);
			return json_decode($result);
		}
	}
	public function API($token, $method, $parameters = []){
		$api = "https://api.telegram.org/bot$token/$method";
		$cURL = curl_init();
		curl_setopt($cURL, CURLOPT_URL, $api);
		curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($cURL, CURLOPT_POSTFIELDS, $parameters);
		curl_setopt($cURL, CURLOPT_SSL_VERIFYPEER, false);
		$result = curl_exec($cURL); curl_close($cURL);
		return json_decode($result);
	}
	public function TelegramAPI($method, $parameters = []){ return CodeRunrBot($method, $parameters); }
	public function getMe(){ return CodeRunrBot('getMe'); }
	public function getWebHookInfo(){ return CodeRunrBot("getWebHookInfo"); }
	public function deleteWebHook(){ return CodeRunrBot('deleteWebHook'); }
	public function setWebHook($sourceUrl){ return CodeRunrBot('setWebHook', ['url' => $sourceUrl]); }
	public function deleteMessage($chat_id, $message_id){ return CodeRunrBot('deleteMessage', ['chat_id' => $chat_id, 'message_id' => $message_id]); }
	public function sendChatAction($chat_id, $actionType){ return CodeRunrBot('sendChatAction', ['chat_id' => $chat_id, 'action' => $actionType]); }
	public function getChat($chat_id){ return CodeRunrBot("getChat", ['chat_id' => $chat_id]); }
	public function getChatMember($chat_id, $user_id){ return CodeRunrBot('getChatMember', ['chat_id' => $chat_id, 'user_id' => $user_id]); }
	public function getChatMemberCount($chat_id){ return CodeRunrBot('getChatMemberCount', ['chat_id' => $chat_id]); }
	public function unPinMessage($chat_id, $message_id){ return CodeRunrBot('unpinChatMessage', ['chat_id' => $chat_id, 'message_id' => $message_id]); }
	public function unPinAllMessages($chat_id){ return CodeRunrBot('unpinAllChatMessages', ['chat_id' => $chat_id]); }
	public function SingleInlineUrlKeyboard($text, $url){ return json_encode(['inline_keyboard' => [[['text' => $text, 'url' => $url]]]]); }
	public function MultiInlineKeyboard($keyboard){ return json_encode(['inline_keyboard' => $keyboard]); }
	public function SingleNormalKeyboard($text){ return json_encode(['keyboard' => [[['text' => $text]]], 'resize_keyboard' => true]); }
	public function MultiNormalKeyboard($keyboard){ return json_encode(['keyboard' => $keyboard, 'resize_keyboard' => true]); }
	public function RemoveKeyboard(){ return json_encode(['remove_keyboard' => true]); }
	public function getChatId(){ return $this->data['message']['chat']['id']; }
	public function getChatUsername(){ return $this->data['message']['chat']['username']; }
	public function getChatFirstname(){ return $this->data['message']['chat']['first_name']; }
	public function getChatTitle(){ return $this->data['message']['chat']['title']; }
	public function getChatType(){ return $this->data['message']['chat']['type']; }
	public function Text(){ return $this->data['message']['text']; }
	public function Update(){ return $this->data; }
	public function Caption(){ return $this->data['message']['caption']; }
	public function Username(){ return $this->data['message']['from']['username']; }
	public function Firstname(){ return $this->data['message']['from']['first_name']; }
	public function Lastname(){ return $this->data['message']['from']['last_name']; }
	public function UserId(){ return $this->data['message']['from']['id']; }
	public function MessageId(){ return $this->data['message']['message_id']; }
	public function getInlineChatId(){ return $this->data['callback_query']['message']['chat']['id']; }
	public function InlineMessageId(){ return $this->data['callback_query']['message']['message_id']; }
	public function InlineUsername(){ return $this->data['callback_query']['message']['chat']['username']; }
	public function InlineFirstname(){ return $this->data['callback_query']['message']['chat']['first_name']; }
	public function InlineLastname(){ return $this->data['callback_query']['message']['chat']['last_name']; }
	public function InlineUserId(){ return $this->data['callback_query']['message']['chat']['id']; }
	public function ForwarderId(){ return $this->data['message']['reply_to_message']['forward_from']['id']; }
	public function LeaveChat($chat_id){ return CodeRunrBot("leaveChat", ['chat_id' => $chat_id]); }
	public function setBotName($name){ return CodeRunrBot('setMyName', ['name' => $name]); }
	public function setBotDescription($description){ return CodeRunrBot('setMyDescription', ['description' => $description]); }
	public function setBotAbout($about){ return CodeRunrBot('setMyShortDescription', ['short_description' => $about]); }
	public function getBotName(){ return CodeRunrBot('getMyName'); }
	public function getBotDescription(){ return CodeRunrBot('getMyDescription'); }
	public function getBotAbout(){ return CodeRunrBot('getMyShortDescription'); }
	public function copyMessage($to_chat_id, $from_chat_id, $message_id){ 
		return CodeRunrBot("copyMessage", ['chat_id' => $to_chat_id, 'from_chat_id' => $from_chat_id, 'message_id' => $message_id]);
	}
	public function editMessage($chatID, $messageID, $text, $mode = "html", $webPage = true, $button = null){
		return CodeRunrBot('editMessageText', [
			'chat_id' => $chatID,
			'message_id' => $messageID,
			'text' => $text,
			'parse_mode' => $mode,
			'disable_web_page_preview' => $webPage,
			'reply_markup' => $button
		]);
	}
	public function editCaption($chatID, $messageID, $caption, $mode = "html", $button = null){
		return CodeRunrBot("editMessageCaption", [
			'chat_id' => $chatID,
			'message_id' => $messageID,
			'caption' => $caption,
			'parse_mode' => $mode,
			'reply_markup' => $button
		]);
	}
	public function sendMessage($chat_id, $text, $mode = "html", $webPage = true, $replyTo = null, $button = null, $protect_content = false){
		return CodeRunrBot('sendMessage', [
			'chat_id' => $chat_id,
			'text' => $text,
			'parse_mode' => $mode,
			'disable_web_page_preview' => $webPage,
			'reply_to_message_id' => $replyTo,
			'reply_markup' => $button,
			'protect_content' => $protect_content
		]);
	}
	public function sendPhoto($chat_id, $photo, $caption = null, $mode = "html", $notification = null, $replyTo = null, $button = null, $protect_content = false){
		return CodeRunrBot('sendPhoto', [
			'chat_id' => $chat_id,
			'photo' => $photo,
			'caption' => $caption,
			'parse_mode' => $mode,
			'disable_notification' => $notification,
			'reply_to_message_id' => $replyTo,
			'reply_markup' => $button,
			'protect_content' => $protect_content
		]);
	}
	public function sendSticker($chat_id, $sticker, $notification = null, $replyTo = null, $button = null, $protect_content = false){
		return CodeRunrBot('sendSticker', [
			'chat_id' => $chat_id,
			'sticker' => $sticker,
			'disable_notification' => $notification,
			'reply_to_message_id' => $replyTo,
			'reply_markup' => $button,
			'protect_content' => $protect_content
		]);
	}
	public function sendVideo($chat_id, $video, $caption = null, $mode = "html", $notification = null, $replyTo = null, $button = null, $protect_content = false){
		return CodeRunrBot('sendVideo', [
			'chat_id' => $chat_id,
			'video' => $video,
			'caption' => $caption,
			'parse_mode' => $mode,
			'disable_notification' => $notification,
			'reply_to_message_id' => $replyTo,
			'reply_markup' => $button,
			'protect_content' => $protect_content
		]);
	}
	public function sendAudio($chat_id, $audio, $caption = null, $duration = null, $title = null, $performer = null, $thumb = null, $mode = "html", $notification = null, $replyTo = null, $button = null, $protect_content = false){
		return CodeRunrBot('sendAudio', [
			'chat_id' => $chat_id,
			'audio' => $audio,
			'caption' => $caption,
			'duration' => $duration,
			'title' => $title,
			'performer' => $performer,
			'thumb' => $thumb,
			'parse_mode' => $mode,
			'disable_notification' => $notification,
			'reply_to_message_id' => $replyTo,
			'reply_markup' => $button,
			'protect_content' => $protect_content
		]);
	}
	public function sendVoice($chat_id, $voice, $caption = null, $duration = null, $mode = "html", $notification = null, $replyTo = null, $button = null, $protect_content = false){
		return CodeRunrBot('sendVoice', [
			'chat_id' => $chat_id,
			'voice' => $voice,
			'caption' => $caption,
			'duration' => $duration,
			'parse_mode' => $mode,
			'disable_notification' => $notification,
			'reply_to_message_id' => $replyTo,
			'reply_markup' => $button,
			'protect_content' => $protect_content
		]);
	}
	public function sendDocument($chat_id, $document, $caption = null, $thumb = null, $mode = "html", $notification = null, $replyTo = null, $button = null, $protect_content = false){
		return CodeRunrBot('sendDocument', [
			'chat_id' => $chat_id,
			'document' => $document,
			'caption' => $caption,
			'thumb' => $thumb,
			'parse_mode' => $mode,
			'disable_notification' => $notification,
			'reply_to_message_id' => $replyTo,
			'reply_markup' => $button,
			'protect_content' => $protect_content
		]);
	}
	public function sendLocation($chat_id, $latitude, $longitude, $replyTo = null, $button = null, $protect_content = false){
		return CodeRunrBot("sendLocation", [
			'chat_id' => $chat_id,
			'latitude' => $latitude,
			'longitude' => $longitude,
			'reply_to_message_id' => $replyTo,
			'reply_markup' => $button,
			'protect_content' => $protect_content
		]);
	}
	public function sendVenue($chat_id, $latitude, $longitude, $title = null, $address = null, $replyTo = null, $button = null, $protect_content = false){
		return CodeRunrBot("sendVenue", [
			'chat_id' => $chat_id,
			'latitude' => $latitude,
			'longitude' => $longitude,
			'title' => $title,
			'address' => $address,
			'reply_to_message_id' => $replyTo,
			'reply_markup' => $button,
			'protect_content' => $protect_content
		]);
	}
	public function sendContact($chat_id, $phone, $first_name, $last_name = null, $replyTo = null, $button = null, $protect_content = false){
		return CodeRunrBot("sendContact", [
			'chat_id' => $chat_id,
			'phone_number' => $phone,
			'first_name' => $first_name,
			'last_name' => $last_name,
			'reply_to_message_id' => $replyTo,
			'reply_markup' => $button,
			'protect_content' => $protect_content,	
		]);
	}
	public function forwardMessage($toChat_id, $fromChat_id, $message_id, $protect_content = false){
		return CodeRunrBot('forwardMessage', [
			'chat_id' => $toChat_id, 'from_chat_id' => $fromChat_id,
			'message_id' => $message_id, 'protect_content' => $protect_content
		]);
	}
	public function InputMessageType(){
		if(isset($this->data['message']['text'])){ return self::TEXT; }
		if(isset($this->data['message']['photo'])){ return self::PHOTO; }
		if(isset($this->data['message']['video'])){ return self::VIDEO; }
		if(isset($this->data['message']['audio'])){ return self::AUDIO; }
		if(isset($this->data['message']['voice'])){ return self::VOICE; }
		if(isset($this->data['message']['document'])){ return self::DOCUMENT; }
		if(isset($this->data['message']['contact'])){ return self::CONTACT; }
		if(isset($this->data['message']['location'])){ return self::LOCATION; }
		if(isset($this->data['message']['sticker'])){ return self::STICKER; }
	}
	public function getFileId($type){
		switch($type){
			case 'photo';
				$count = count($this->data['message']['photo']) - 1;
				$fileId = $this->data['message'][$type][$count]['file_id'];
				break;
			case 'video';
				$fileId = $this->data['message'][$type]['file_id'];
				break;
			case 'audio';
				$fileId = $this->data['message'][$type]['file_id'];
				break;
			case 'voice';
				$fileId = $this->data['message'][$type]['file_id'];
				break;
			case 'document';
				$fileId = $this->data['message'][$type]['file_id'];
				break;
			case 'sticker';
				$fileId = $this->data['message'][$type]['file_id'];
				break;
		}
		return $fileId;
	}
	public function fileInfo($p){
		$fileId = $this->getFile($this->array["file_id"]);
		switch($p){
			case 'path';
				//$d = $fileId['result']['file_path'];
				$d = $fileId->result->file_path;
				break;
			case 'id';
				$d = $fileId->result->file_id;
				break;
			case 'unique';
				$d = $fileId->result->file_unique_id;
				break;
			case 'size';
				$d = $fileId->result->file_size;
				break;
			case 'address';
				return "https://api.telegram.org/file/bot".ACCESS_TOKEN."/".$fileId->result->file_path;
				break;
		}
		return $d;
	}
	public function getFile($fileId){
		$this->array = get_defined_vars();
		return CodeRunrBot('getFile', ['file_id' => $fileId]);
	}
	public function Audio($input){
		switch($input){
			case 'title';
				$output = $this->data['message']['audio']['title'];
				break;
			case 'artist';
				$output = $this->data['message']['audio']['performer'];
				break;
			case 'mime';
				$output = $this->data['message']['audio']['mime_type'];
				break;
			case 'thumb';
				$output = $this->data['message']['audio']['thumb'];
				break;
			case 'size';
				$output = $this->data['message']['audio']['file_size'];
				break;
		}
		return $output;
	}
	public function Document($input){
		switch($input){
			case 'name';
				$output = $this->data['message']['document']['file_name'];
				break;
			case 'id';
				$output = $this->data['message']['document']['file_id'];
				break;
			case 'mime';
				$output = $this->data['message']['document']['mime_type'];
				break;
			case 'thumb';
				$output = $this->data['message']['document']['thumb'];
				break;
			case 'size';
				$output = $this->data['message']['document']['file_size'];
				break;
			case 'unique';
				$output = $this->data['message']['document']['file_unique_id'];
				break;
		}
		return $output;
	}
	public function Contact($input){
		switch($input){
			case 'phone';
				$output = $this->data['message']['contact']['phone_number'];
				break;
			case 'firstname';
				$output = $this->data['message']['contact']['first_name'];
				break;
			case 'lastname';
				$output = $this->data['message']['contact']['last_name'];
				break;
			case 'id';
				$output = $this->data['message']['contact']['user_id'];
				break;
			case 'vcard';
				$output = $this->data['message']['contact']['vcard'];
				break;
		}
		return $output;
	}
	public function CallBackQuery($input){
		switch($input){
			case 'callback_id';
				$output = $this->data['callback_query']['id'];
				break;
			case 'fromID';
				$output = $this->data['callback_query']['from']['id'];
				break;
			case 'data';
				$output = $this->data['callback_query']['data'];
				break;
			case 'chatID';
				$output = $this->data['callback_query']['message']['chat']['id'];
				break;
			case 'messageID';
				$output = $this->data['callback_query']['message']['message_id'];
				break;
		}
		return $output;
	}
	public function InlineQuery($input){
		switch($input){
			case 'id';
				$output = $this->data['inline_query']['id'];
				break;
			case 'query';
				$output = $this->data['inline_query']['query'];
				break;
		}
		return $output;
	}
	public function UpdateType(){
		if(isset($this->data['callback_query'])){ return self::CALLBACK_QUERY; }
		if(isset($this->data['inline_query'])){ return self::INLINE_QUERY; }
	}
	public function AnswerCallBack($callback_id, $text, $alert = false){
		return CodeRunrBot('answerCallbackQuery', [
			'callback_query_id' => $callback_id,
			'text' => $text,
			'show_alert' => $alert
		]);
	}
	public function AnswerInlineQuery($inline_query_id, $data){
		return CodeRunrBot('answerInlineQuery', [
			'cache_time' => 0,
			'inline_query_id' => $inline_query_id,
			'result' => json_encode($data)
		]);
	}
	public function pinMessage($chat_id, $message_id, $notification = false){
		return CodeRunrBot('pinChatMessage', [
			'chat_id' => $chat_id,
			'message_id' => $message_id,
			'disable_notification' => $notification
		]);
	}
}
