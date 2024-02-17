<?php

namespace Admin\Application\Controller;

class MessagesController extends \Main\Controller {

	private $doc;
	private $ws_client;
	private $websocketAddress = "ws://localhost:8980/mls/Manage/webSocketServer.php";

	function __construct() {
		$this->setTempalteBasePath(ROOT."Admin");
		$this->doc = $this->getLibrary("Factory")->getDocument();
	}
	
	function index() {

		$this->doc->setTitle("Messages");
		
		$deleted_thread = $this->getModel("DeletedThread");
		$deleted_thread->select(" GROUP_CONCAT(thread_id) as thread_ids ");
		$deleted_thread->column['account_id'] = $_SESSION['user_logged']['account_id'];
		$data['deletedThreads'] = $deleted_thread->getByAccountId();
		
		if(isset($_REQUEST['search'])) {
			$filters[] = " (subject LIKE '%".$_REQUEST['search']."%')";
			$uri['search'] = $_REQUEST['search'];
		}
		
		$filters[] = " JSON_CONTAINS(participants, '".$_SESSION['user_logged']['account_id']."', '$')";
		
		if($data['deletedThreads']['thread_ids']) {
			$filters[] = " thread_id NOT IN(".$data['deletedThreads']['thread_ids'].")";
		}

		if(isset($filters)) {
			$clause[] = implode(" AND ",$filters);
		}

		$thread = $this->getModel("Thread");
		$thread->where(isset($clause) ? implode("",$clause) : null)
		->orderBy(" created_at DESC ");

		$thread->page['limit'] = 20;
		$thread->page['current'] = isset($_REQUEST['page']) ? $_REQUEST['page'] : 1;
		$thread->page['target'] = url("MessagesController@index");
		$thread->page['uri'] = (isset($uri) ? $uri : []);

		$data['threads'] = $thread->getList();
		
		if($data['threads']) {

			$account = $this->getModel("Account");
			$message = $this->getModel("Message");
			$user = $this->getModel("User");
			
			for($i=0; $i<count($data['threads']); $i++) {

				for($x=0; $x<count($data['threads'][$i]['participants']); $x++) {
					
					$account->column['account_id'] = $data['threads'][$i]['participants'][$x];
					$accountData = $account->getById();

					unset($accountData['account_type']);
					unset($accountData['tin']);
					unset($accountData['preferences']);
					unset($accountData['privileges']);

					$data['threads'][$i]['accounts'][$x] = $accountData;

				}

				$account->column['account_id'] = $data['threads'][$i]['created_by'];
				$accountData = $account->getById();

				$data['threads'][$i]['created_by'] = $accountData;
				$data['last_message'] = $message->getLastMessage($data['threads'][$i]['thread_id']);

				if($data['last_message']) {
					$user->select(" user_id, name, email ");
					$user->column['user_id'] = $data['last_message']['user_id'];
					$data['last_message']['from'] = $user->getById();

					$data['last_message']['body'] = json_decode($message->decrypt($data['last_message']['content']), true);
				}

			}
		}

		$this->setTemplate("messages/messages.php");
		return $this->getTemplate($data,$thread);

	}

	function getThreadInfoByParticipants($participants) {

		/** should match each participants and vise-versa [1,4] OR [4,1] */

		$participants = json_decode($participants, true);

		if(!is_array($participants)) {
			$this->response(404);
		}

		if(count($participants) == 2) {
			$thread = $this->getModel("Thread");
			$thread->where(" (participants->>'$[0]' = ".$participants[0]." AND participants->>'$[1]' = ".$participants[1].") OR (participants->>'$[0]' = ".$participants[1]." AND participants->>'$[1]' = ".$participants[0].") ");
			$data = $thread->getList();

			return $data[0];
		}

		return false;

	}

	function conversation($participants) {

		$participants = base64_decode($participants);
		$thread = $this->getModel("Thread");
		$data['thread'] = $thread->getByParticipants($participants);

		$this->doc->addScript(CDN."js/chat-attachment-uploader.js");
		$this->doc->setTitle("Conversation");

		if(CONFIG['chat_is_websocket'] == 1) {
			$this->webSocketChatScript();
		}else {
			$this->ajaxChatScript();
		}

		$unset_account_data = explode(",","account_type,preferences,privileges,uploads");
		$unset_user_data = explode(",","user_id,password,user_level,permissions,two_factor_authentication,two_factor_authentication_aps");

		try {
			
			$account = $this->getModel("Account");
			$participants = json_decode($participants, true);
			
			if(!is_array($participants)) {
				throw new \Exception('Invalid Participant ids');
			}

			foreach($participants as $participant_account_id) {
				$account->column['account_id'] = $participant_account_id;
				$data['participants'][$participant_account_id] = $account->getById();

				foreach($unset_account_data as $column) {
					unset($data['participants'][$participant_account_id][$column]);
				}
			}

		}catch (Exception $e) {
			$this->response(404);
		}

		if($data['thread']) {

			$data['participants_id'] = $participants;

			$message = $this->getModel("Message");
			$message->DBO->query("UPDATE #__messages SET is_read = 1 WHERE thread_id = ".$data['thread']['thread_id']." AND user_id != ".$_SESSION['user_logged']['user_id']."");

			$data['messages'] = $message->execute(" SELECT * 
				FROM (SELECT * FROM #__messages WHERE thread_id = ".$data['thread']['thread_id']." ORDER BY created_at DESC LIMIT 20) as sub 
				ORDER BY created_at ASC
			");

			if($data['messages']) {
				$user = $this->getModel("User");
				for($i=0; $i<count($data['messages']); $i++) {
					$user->column['user_id'] = $data['messages'][$i]['user_id'];
					$data['messages'][$i]['user'] = $user->getById();

					$data['messages'][$i]['content'] = json_decode($message->decrypt($data['messages'][$i]['content']), true);

					foreach($unset_user_data as $column) {
						unset($data['messages'][$i]['user'][$column]);
					}
				}

			}

		}else {
			$data['messages'] = false;
		}

		$this->setTemplate("messages/view.php");
		return $this->getTemplate($data);
		
	}

	function getMessages($participants,$lastMessageId) {

		$thread = $this->getModel("Thread");
		$data['thread'] = $thread->getByParticipants(base64_decode($participants));

		if($data['thread']) {

			$message = $this->getModel("Message");
			$message->and(" message_id > $lastMessageId ");
			$message->orderBy(" created_at DESC ");
			$data['messages'] = $message->getByThreadId($data['thread']['thread_id']);

			if($data['messages']) {
				$user = $this->getModel("User");
				for($i=0; $i<count($data['messages']); $i++) {

					if($data['messages'][$i]['user_id'] != $_SESSION['user_logged']['user_id']) {
						$message->save($data['messages'][$i]['message_id'], [
							"is_read" => 0
						]);
					}

					$user->column['user_id'] = $data['messages'][$i]['user_id'];
					$data['messages'][$i]['user'] = $user->getById();

					$data['messages'][$i]['content'] = json_decode($message->decrypt($data['messages'][$i]['content']), true);

				}

				$this->setTemplate("messages/getMessages.php");
				return $this->getTemplate($data);
			}

		}

	}

	function saveNewMessage() {

		parse_str(file_get_contents('php://input'), $_POST);

		$thread = $this->getModel("Thread");
		$data['thread'] = $thread->getByParticipants($_POST['participants']);

		if($data['thread']) {
			$thread_id = $data['thread']['thread_id'];
		}else {
			$thread = $this->getModel("Thread");
            $response = $thread->saveNew(array(
				"participants" => $_POST['participants'],
				"created_by" => $_SESSION['user_logged']['user_id'],
				"created_at" => DATE_NOW
			));

			$thread_id = $response['id'];
		}

		$message = $this->getModel("Message");
		
		$content = [
			"type" => $_POST['type'],
			"message" => $_POST['message'],
			"info" => isset($_POST['info']) ? $_POST['info'] : null
		];

		$message_response = $message->saveNew(
			array(
				"user_id" => $_SESSION['user_logged']['user_id'],
				"thread_id" => $thread_id,
				"content" =>  $content,
				"is_read" => 0,
				"created_at" => DATE_NOW
			)
		);

		if (($key = array_search($_SESSION['user_logged']['account_id'], $data['thread']['participants'])) !== false) {
			unset($data['thread']['participants'][$key]);
		}

		$recipient_account_id = implode("", $data['thread']['participants']);
		$notification = $this->getModel("Notification");
		$notification->saveNew(
			array(
				"account_id" => $recipient_account_id,
				"status" => 1,
				"created_at" => DATE_NOW,
				"content" => array(
					"title" => $_SESSION['user_logged']['name'],
					"message" => "Sent you a message",
					"url" => MANAGE."threads/".base64_encode($_POST['participants'])
				)
			)
		);

		return json_encode(
			array(
				"status" => 1,
				"type" => "success",
				"id" => $message_response['id'],
				"thread_id" => $thread_id,
				"data" => array(
					"thread_id" => $thread_id,
					"user_id" => $_SESSION['user_logged']['user_id'],
					"photo" => $_SESSION['user_logged']['photo'],
					"user_name" => $_SESSION['user_logged']['name'],
					"user_message" => $message->encrypt(json_encode($content)),
					"user_sent_time" => date("M d, Y h:ia", DATE_NOW),
				)
			)
		);

	}
	
	function saveDeletedThread($id) {
		
		if($id) {
			if(isset($_REQUEST['delete'])) {
				$thread = $this->getModel("Thread");
				$thread->save($id, array(
					"status" => 0
				));

				$deleted_thread = $this->getModel("DeletedThread");
				$response = $deleted_thread->saveNew(array(
					"account_id" => $_SESSION['user_logged']['account_id'],
					"user_id" => $_SESSION['user_logged']['user_id'],
					"thread_id" => $id,
					"deleted_by" => $_SESSION['user_logged']['name'],
					"deleted_at" => DATE_NOW
				));

				$data['id'] = $response['id'];

				$this->getLibrary("Factory")->setMsg("Message deleted!.","success");
				return json_encode(
					array(
						"status" => 1,
						"message" => getMsg()
					)
				);

			}else {
				$data['id'] = $id;
				$this->setTemplate("messages/removeThread.php");
				return $this->getTemplate($data);
			}
		}

		$this->response(404);
		
	}

	function downloadThreadMessages($id) {

		$thread = $this->getModel("Thread");
		$thread->column['thread_id'] = $id;
		$data['thread'] = $thread->getById();

		if($data['thread']) {

			$text[] = "Downloaded at ".MANAGE." ".date("Y-m-d g:ia", DATE_NOW)."\r";
			$text[] = "Thread started at ".date("Y-m-d g:ia", $data['thread']['created_at'])."";
			$text[] = "Participants: ";

			for($i=0; $i<count($data['thread']['participants']); $i++) {
				$user = $this->getModel("User");
				$user->select(" user_id, account_id, email, name ");
				$user->column['user_id'] = $data['thread']['participants'][$i];
				$data['thread']['users'][ $data['thread']['participants'][$i] ] = $user->getById();

				$text[] = "\t".$data['thread']['users'][ $data['thread']['participants'][$i] ]['name'];
			}

			$text[] = "\r";

			$message = $this->getModel("Message");
			$message->page['limit'] = 10000000;
			$message->orderBy(" created_at DESC ");
			$data['messages'] = $message->getByThreadId($data['thread']['thread_id']);

			if($data['messages']) { 
				for($i=0; $i<count($data['messages']); $i++) { $con = '';
					$con .= "-".date("Y-m-d g:ia", $data['messages'][$i]['created_at'])."\t".$data['thread']['users'][ $data['messages'][$i]['user_id'] ]['name'].":\t";

					$content = json_decode($message->decrypt($data['messages']['content']), true);

					if($content['type'] == "text") {
						$con .= $content['message'];
					}else {
						$con .= "sent an image ".implode(", ", $content['info']['links']);
					}

					$text[] = $con;
				}
			}

			$local_path = "../Cdn/public/threads/";
			$files = glob($local_path.'*'); // get all file names
			foreach($files as $file){ // iterate files
				if(is_file($file)) {
					unlink($file); // delete file
				}
			}

			$filename = "mls_thread_messages_".$data['thread']['thread_id']."".date("Ymd",$data['thread']['created_at']).".txt";
			$file_url = CDN."public/threads/$filename";

			$handle = fopen("../Cdn/public/threads/".$filename, "w");
			fwrite($handle, implode("\r",$text));
			fclose($handle);

			header("Content-Description: File Transfer");
			header('Content-Type: application/octet-stream');
			header("Content-disposition: attachment; filename=\"" . basename($file_url) . "\""); 
			header('Expires: 0');
    		header('Cache-Control: must-revalidate');
    		header('Pragma: public');
			header("Content-length: ".filesize($local_path."/".$filename));

			ob_clean();
			ob_flush();
			flush();

			readfile($file_url);

			exit();
		}

		$this->response(404);

	}

	function uploadAttachment() {
		$message = $this->getModel("Message");
		return $message->uploadAttachment($_FILES['ImageBrowse']);
	}

	function removeAttachment($filename) {
		$message = $this->getModel("Message");
		return $message->removeAttachment($filename);
	}

	function webSocketChatScript() {

		/** 
		 * Run the websocket server first before using this
		 * webSocketServer.php
		 * 
		 * */

		$this->doc->addScriptDeclaration("

		    var wsUri = '".$this->websocketAddress."?name=" . (str_replace(" ","+",$_SESSION['user_logged']['name'])) ."';
			var websocket = new WebSocket(wsUri);
			var thread_id = 0;

			$(document).ready(function() {
				var div = $('.card-body');
				div.scrollTop(div[0].scrollHeight - div[0].clientHeight);

				websocket.onopen = function(ev) { // connection is open 
					console.log(' Server Open ');
				}

				websocket.onmessage = function(ev) {
					var response	= JSON.parse(ev.data);
					if(thread_id == response.thread_id) {
						html = buildMessage(response);
						$('.chat-bubbles').append(html);
					}
					div.scrollTop(div[0].scrollHeight - div[0].clientHeight);
				};

				websocket.onerror	= function(ev) { 
					console.log('Error Occurred - ' + ev.data);
					$('#serverErrorModal').show({
						backdrop: 'static',
						keyboard: false
					});
				};

				websocket.onclose 	= function(ev) {
					console.log('Connection Closed');
					$('#serverErrorModal').show({
						backdrop: 'static',
						keyboard: false
					});
				};

			});

			$(document).on('click', '.btn-send-message', function() { sendMessage(); });
			$( document ).on( 'keydown', '#message', function( e ) { if(e.which == 13){ $('.btn-send-message').trigger('click'); } });

			function sendMessage(){
				
				var type = $('#type').val();
				var message = $('#message').val();

				if((type == 'text' && message != '') || (type == 'image')) {

					$('.btn-send').removeClass('btn-send-message');
					
					$.post('".url("MessagesController@saveNewMessage")."',  $('#form').serialize(), function(data) {
						response = JSON.parse(data);

						websocket.send(JSON.stringify(response.data));
						$('#message').val('');

						$('#thread_id').val(response.data['thread_id']);
						thread_id = response.data['thread_id'];

						$('.btn-send').addClass('btn-send-message');
					});
				}

			}

			function buildMessage(response) {

				var id = $_SESSION[user_logged][user_id];
				
				if(response.user_id == id) {
				
					html = \"<div class='row align-items-end justify-content-end '>\";
						html += \"<div class='col col-lg-6'>\";
							html += \"<div class='chat-bubble chat-bubble-me'>\";
								
								html += \"<div class='chat-bubble-title'>\";
									html += \"<div class='row'>\";
										html += \"<div class='col chat-bubble-author'>\";
											html += response.user_name;
										html += \"</div>\";
										html += \"<div class='col-auto chat-bubble-date'>\";
											html += response.user_sent_time;
										html += \"</div>\";
									html += \"</div>\";
								html += \"</div>\";
								html += \"<div class='chat-bubble-body'>\";
									html += \"<p>\" + atob(response.user_message) + \"</p>\";
								html += \"</div>\";
							html += \"</div>\";
						html += \"</div>\";
						html += \"<div class='col-auto'>\";
							html += \"<span class='avatar' style='background-image: url(\"+response.photo+\")'></span>\";
						html += \"</div>\";
					html += \"</div>\";
	
				}else {

					html = \"<div class='row align-items-end'>\";
						html += \"<div class='col-auto'>\";
							html += \"<span class='avatar' style='background-image: url(\"+response.photo+\")'></span>\";
						html += \"</div>\";
						html += \"<div class='col col-lg-6'>\";
							html += \"<div class='chat-bubble'>\";
								
								html += \"<div class='chat-bubble-title'>\";
									html += \"<div class='row'>\";
										html += \"<div class='col chat-bubble-author'>\";
											html += response.user_name;
										html += \"</div>\";
										html += \"<div class='col-auto chat-bubble-date'>\";
											html += response.user_sent_time;
										html += \"</div>\";
									html += \"</div>\";
								html += \"</div>\";
								html += \"<div class='chat-bubble-body'>\";
									html += \"<p>\" + atob(response.user_message) + \"</p>\";
								html += \"</div>\";
							html += \"</div>\";
						html += \"</div>\";
					html += \"</div>\";

				}

				return html;
			}

		");

	}

	function ajaxChatScript() {

		$this->doc->addScriptDeclaration("

		    var div;
			var idleTime = 0;
			var thread_id;
			
			$(document).ready(function() {
				div = $('.card-body');
				thread_id = parseInt($('#thread_id').val());
				div.scrollTop(div[0].scrollHeight - div[0].clientHeight);
				div.bind('scroll', fetchMore);
				setInterval(fetchMore, 8000);
			});

			fetchMore = function() {
				
				var lastMessageId = $('.last_message_id').val();
				var participants = $('#participants').val();
				
				if(div.scrollTop() + div.innerHeight() >= div[0].scrollHeight) {
					if(thread_id > 0) {
						div.unbind('scroll', fetchMore);
						$.get(MANAGE+'threads/' + btoa(participants) + '/getMessages/' + lastMessageId, function(data) {
							if(data != '') {
								$('.last_message_id').remove();
								$('.chat-bubbles').append(data);
								div.scrollTop(div[0].scrollHeight - div[0].clientHeight);
							}
							div.bind('scroll',fetchMore);
						});
					}
				}
			};

			$(document).on('click', '.btn-send-message', function() { sendMessage(); });
			$( document ).on( 'keydown', '#message', function( e ) { if(e.which == 13){ $('.btn-send-message').trigger('click'); } });

			function sendMessage() {

				var type = $('#type').val();
				var message = $('#message').val();
				
				if((type == 'text' && message != '') || (type == 'image')) {

					$('.btn-send').removeClass('btn-send-message');

					$.post('".url("MessagesController@saveNewMessage")."', $('#form').serialize(), function(data) {

						console.log(data);
						
						response = JSON.parse(data);

						eval('('+fetchMore+')()');
						$('#message').val('');
						div.scrollTop(div[0].scrollHeight - div[0].clientHeight);

						$('#thread_id').val(response.data['thread_id']);
						thread_id = response.data['thread_id'];

						$('.btn-send').addClass('btn-send-message');
						
					});

				}

			}
			
		");

	}

}