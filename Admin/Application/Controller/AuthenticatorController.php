<?php

namespace Admin\Application\Controller; 

use Admin\Application\Controller\AccountsController as Account;

class AuthenticatorController extends \Main\Controller
{

	private static $_instance = null;
	public $domain;
	public $session;
	
	public static function getInstance () {
        if (self::$_instance === null) {
            self::$_instance = new self;
        }
		
        return self::$_instance;
    }

    function __construct() {

		$this->setTempalteBasePath(ROOT."Admin");
		$this->domain = ADMIN;

        $this->session = new \Josantonius\Session\Session;

    }

    function beginSession($options = []) {
		if(!$this->session->isStarted()) { $this->session->start($options); }
	}

    function monitor() {

		$logged = $this->session->get("user_logged");

		if(isset($logged['user_id'])) {

			/* already logged */
			if(isset($_REQUEST['logout'])) {
				/** user wants to logout */
				return $this->endSession();
			}

			/* check if logging in the same domain */
			if($this->session->get('domain') != $this->domain) {
				return $this->endSession();
			}

			if(!$this->verifySession()) {
				/** invalid session */
				$this->endSession();
				\Library\Factory::setMsg("Someone has logged in using this account! This account will be logged out in all devices.","warning");

				return [
					"status" => 0
				];
			}

			return [
				"status" => 1
			];

		}

		/* not logged */
		return [
			"status" => 0
		];
		
	}

    function endSession() {

		$data = $this->session->get("user_logged");

		$user_login = $this->getModel("UserLogin");
		$user_login->setStatus($data['user_id'], 0);
	
		$this->session->regenerateId();
		$this->session->clear();
		$this->session->destroy();

		return [
			"status" => 0
		];

	}

    function verifySession() {

		$data = $this->session->get("user_logged");

		$user_login = $this->getModel("UserLogin");
		$user_login->column['user_id'] = $data['user_id'];
		$user_login->and(" status = 1 ");

		$user = $user_login->getByUserId();

		if($user) {

			/** check current user session */
			if($user['session_id'] != $data["login_session_id"]) {
				/** user current session not same */
				return false;
			}

			/** everything is alright, update current session */
			$this->updateSession();
			return true;

		}else {
			return false;
		}

	}

	function updateSession() {

		$session = $this->session->get("user_logged");

		$account = $this->getModel("Account");
		$account->column['account_id'] = $session['account_id'];
		$accountData = $account->getById();
		
		$user = $this->getModel("User");
		$user->column['user_id'] = $session['user_id'];
		$userData = $user->getById();

		$data = array_merge($accountData, $userData);

		$subscription = $this->getModel("AccountSubscription");
		$subscription->column['account_id'] = $session['account_id'];
		$data['privileges'] = $subscription->getSubscription();

		if($data['privileges'] === false) {
			$data['privileges'] = $accountData['privileges'];
		}
		
		foreach($data as $key => $val) {
			$_SESSION['user_logged'][$key] = $val;
		}

	}

	function getLoginForm() {

		$doc = $this->getLibrary("Factory")->getDocument();
	
		if($_SERVER['REQUEST_METHOD'] == "POST") {
			if($this->checkCredentials($_POST['email'],md5($_POST['password']))) {
				header("LOCATION: ".$this->domain."");
			}
		}

		$doc->addScriptDeclaration("
			$(document).ready(function(e) {
				height = $(document).height();
				$('.login-container').css('height',(height - 200));
			});
		");

		$doc->setTitle("MLS Login");

		$this->setTemplate("login/login.php");
		return $this->getTemplate();
		
	}

	function getForgotPasswordForm() {

		$doc = $this->getLibrary("Factory")->getDocument();
		$doc->setTitle("Send Password Reset Link - MLS");

		$this->setTemplate("login/forgot-password.php");
		return $this->getTemplate();
	}

	function getResetPasswordForm() {

		$doc = $this->getLibrary("Factory")->getDocument();
		$doc->setTitle("Password Reset - MLS");

		if(!isset($_REQUEST['ref'])) {
			response()->redirect('/not-found');
		}else {

			$ref = explode("&",base64_decode($_REQUEST['ref']));
								
			foreach($ref as $r) {
				$d = explode("=",$r);
				$data[@$d[0]] = @$d[1];
			}

			$this->setTemplate("login/reset-password.php");
			return $this->getTemplate($data);
		}

	}

	function getTwoStepVerificationCodeForm() {

		$doc = $this->getLibrary("Factory")->getDocument();

		$doc->addScriptDeclaration("

			document.addEventListener('DOMContentLoaded', function() {
				var inputs = document.querySelectorAll('[data-code-input]');
				// Attach an event listener to each input element
				for(let i = 0; i < inputs.length; i++) {
					inputs[i].addEventListener('input', function(e) {
						// If the input field has a character, and there is a next input field, focus it
						if(e.target.value.length === e.target.maxLength && i + 1 < inputs.length) {
							inputs[i + 1].focus();
						}
					});
					inputs[i].addEventListener('keydown', function(e) {
						// If the input field is empty and the keyCode for Backspace (8) is detected, and there is a previous input field, focus it
						if(e.target.value.length === 0 && e.keyCode === 8 && i > 0) {
							inputs[i - 1].focus();
						}
					});
				}
			});

		");

		$this->setTemplate("login/2-step-verification-code.php");
		return $this->getTemplate();
	}

	function checkCredentials($email,$password) {

		$user = $this->getModel('User');
		$user->column['email'] = $email;
		$user->column['password'] = $password;

		if($data = $user->getByEmailAndPassword()) {

			if($this->isBlock($data)) {

				if($_SERVER['HTTP_HOST'] == str_replace("/","",str_replace("http://","",ADMIN)) && $data['account_type'] != "Administrator") {
					$this->getLibrary("Factory")->setMsg("Only Administrator can login here.","error");
					return false;
				}

				if($_SERVER['HTTP_HOST'] == str_replace("/","",str_replace("http://","",WEBADMIN)) && !in_array($data['account_type'], ["Web Admin", "Administrator"]) ) {
					$this->getLibrary("Factory")->setMsg("Only Web Administrator can login here.","error");
					return false;
				}

				if($_SERVER['HTTP_HOST'] == str_replace("/","",str_replace("http://","",CS)) && !in_array($data['account_type'], ["Customer Service", "Administrator"]) ) {
					$this->getLibrary("Factory")->setMsg("Only Customer Service can login here.","error");
					return false;
				}

				if($_SERVER['HTTP_HOST'] == str_replace("/","",str_replace("http://","",MANAGE)) && !in_array($data['account_type'], ["Real Estate Practitioner", "Administrator"]) ) {
					$this->getLibrary("Factory")->setMsg("Only Real Estate Practitioner can login here.","error");
					return false;
				}

				if(!$this->checkLoggedUser($data)) {
					return false;
				}

				$this->recordLogin($this->setPrivileges($data));

				return true;
			}
		}

		$this->getLibrary("Factory")->setMsg("Invalid Username or Password.","error");
		return false;
		
	}

	function checkLoggedUser($data) {

		$user_login = $this->getModel("UserLogin");
		$user_login->and(" user_id = ".$data['user_id']);
		$user_login->where(" status = 1 ");
		
		if($user_login->getList()) {

			/** user already logged, we assume someone else login the account, end this login attempt and user already logged will be force to logout  */
			$user_login->setStatus($data['user_id'], 0);

			$this->getLibrary("Factory")->setMsg("Someone is already using this account! This account will be logout in all devices.","warning");
			return false;

		}

		return true;

	}

	function setPrivileges($data) {

		$subscription = $this->getModel("AccountSubscription");
		$subscription->column['account_id'] = $data['account_id'];
		$privileges = $subscription->getSubscription();

		if($privileges === false) {
			$data['privileges'] = $data['privileges'];
		}else {
			$data['privileges'] = $privileges;
		}

		$account = new Account();
		$account->limitWithExpiredPrivileges($data['account_id']);

		return $data;
	}

	function recordLogin($data) {

		/** LOGIN SESSION */
		$login_session_id = $this->session->getId();
		$data['login_session_id'] = $login_session_id;

		$client_info = \Library\UserClient::getInstance()->information();

		$user_login = $this->getModel("UserLogin");
		$user_login->saveNew([
			"user_id" => $data['user_id'],
			"session_id" => $login_session_id,
			"status" => 1,
			"login_at" => DATE_NOW,
			"login_details" => $client_info
		]);
	
		$arr = [
			'user_logged' => $data,
			'domain' => $this->domain,
			'logged' => true
		];

		foreach($arr as $key => $val) {
			$_SESSION[$key] = $val;
		}

		/** REGENERATE session_id FOR LISTING TRAFFIC SESSION */
		session_regenerate_id();

		return true;
		
	}
	
	function isBlock($data) {

		switch($data['status']) {
			
			case "banned":
				$this->getLibrary("Factory")->setMsg("This account have been blocked by the system administrator.","warning");
				return false;
				break;
			
			default:
				return true;
		}

		switch($data['user_status']) {
			case "inactive":
				$this->getLibrary("Factory")->setMsg("This user has been deactivated due to an expired subscription.","warning");
				return false;
				break;

			default:
				return true;
		}

	}

	function sendPasswordResetLink() {
		
		parse_str(file_get_contents('php://input'), $_POST);

		if($_POST['email']) {
		
			$user = $this->getModel("User");
			$user->column['email'] = $_POST['email'];
			
			if($data = $user->getByEmail()) {
			
				$html[] = "<h1><img src='".CDN."images/logo.png' /></h1><br/><table cellpadding='10' cellspacing='2' border='1'>";
				$html[] = "<p>Hi ".$data['name']."!</p>";
				
				$html[] = "<p>You request a password reset link through our system, Please click the link below to reset your password now.</p>";
				
				$ref = base64_encode("user_id=".$data['user_id']."&expires=".date("Y-m-d H:i:s",strtotime("+24 hours")));
				$link = url("LoginController@resetPassword",null,['ref' => $ref]);
				
				$html[] = "<p>This link will be available for the next 24 hours</p>";
				$html[] = "<p style='padding:10px;'><a href='$link'>Reset your password</a></p>";
				
				$mail = $this->getModel("Mail");
				if($mail->sendMail(CONFIG['email_address_responder'],$data['email'],"Password Reset Request",implode("",$html))) {
					$this->getLibrary("Factory")->setMsg("Password reset link has been sent to your registered email.","correct");
					$response = array(
						"status" => 1,
						"message" => getMsg()
					);
				}else {
					$this->getLibrary("Factory")->setMsg("Cannot request password reset link at the moment, please contact your system administrator.","warning");
					$response = array(
						"status" => 2,
						"message" => getMsg()
					);
				}
				
			}else {
				$this->getLibrary("Factory")->setMsg("Email \"".$_POST['email']."\" does not recognized by our system!.","warning");
				$response = array(
					"status" => 2,
					"message" => getMsg()
				);
			}
			
		
		}else {
			$this->getLibrary("Factory")->setMsg("Invalid email address!.","warning");
			$response = array(
				"status" => 2,
				"message" => getMsg()
			);
		}
		
		return json_encode($response);
	}
	
	function saveNewPassword() {
		
		parse_str(file_get_contents('php://input'), $_POST);

		$user = $this->getModel("User");
		$user->column['user_id'] = $_POST['user_id'];
		$data = $user->getById();

		if($data) {
			$response = $user->save($data['user_id'], $_POST);
		}else {
			$response = array(
				"status" => 2,
				"type" => "error",
                "message" => "Invalid Account! Request another password reset link!"
			);
		}
		
		$this->getLibrary("Factory")->setMsg($response['message'],$response['type']);
		$response = array(
			"status" => $response['status'],
			"message" => getMsg()
		);
		
		return json_encode($response);
		
	}

}