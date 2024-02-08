<?php

namespace Library;

class Factory {

	static function getDBO() {
		static $instance;

		if (!is_object($instance)) {
			$instance = Factory::_createDBO();
		}
		return $instance;
	}

	static function getDocument() {

		static $instance;
		if (!is_object($instance)) {
			$instance = Factory::_createDocument();
		}
		return $instance;
	}

	static function getValidator() {
		static $instance;
		if (!is_object($instance)) {
			$instance = Factory::_createValidator();
		}
		return $instance;
	}

	static function setMsg($msg,$type) {
		static $instance;
		if (!is_object($instance)) {
			$instance = Factory::_createMsg($msg,$type);
		}
		return $instance;
	}

	static function getUpload($file) {
		static $instance;
		if (!is_object($instance)) {
			$instance = Factory::_createUpload($file);
		}
		return $instance;
	}

	static function getPagination() {
		static $instance;
		if (!is_object($instance)) {
			$instance = Factory::_createPagination();
		}
		return $instance;
	}

	static function getHeaderStatus($status,$description=null) {
		static $instance;
		if (!is_object($instance)) {
			$instance = Factory::_createHeaderStatus($status,$description);
		}
		return $instance;
	}

	static function getFtpClient() {
		static $instance;
		if (!is_object($instance)) {
			$instance = Factory::_ftpClient();
		}
		return $instance;
	}

	static function getFileHelper() {
		static $instance;
		if (!is_object($instance)) {
			$instance = Factory::_fileHelper();
		}
		return $instance;
	}

	static function getImageHelper() {
		static $instance;
		if (!is_object($instance)) {
			$instance = Factory::_imageHelper();
		}
		return $instance;
	}

	static function getUserClient() {
		static $instance;
		if (!is_object($instance)) {
			$instance = Factory::_userClient();
		}
		return $instance;
	}

	static function _userClient() {
		$UserClient = new \Library\UserClient;
		return $UserClient;
	}

	static function _imageHelper() {
		$ImageHelper = new \Library\ImageHelper;
		return $ImageHelper;
	}

	static function _fileHelper() {
		$fileHelper = new \Library\FileHelper;
		return $fileHelper;
	}

	static function _createHeaderStatus($status,$description) {
		$headers = new \Library\HttpHeaders;
		return $headers;
	}

	static function _createDBO() {
		$db = new \Library\Database;
		$db->dbConnect();
		return $db;
	}

	static function _createDocument() {
		$document = new \Library\Document;
		return $document;
	}

	static function _createValidator() {
		$validator = new \Library\Validator;
		return $validator;
	}

	static function _createPagination() {
		$paginate = new \Library\Pagination;
		return $paginate;
	}

	static function _createUpload($file) {
		return \Vendor\Upload\Upload($file);
	}

	static function _createMsg($msg,$type) {
		$message = new \Library\Message;
		$_SESSION['msg'] = $message->buildErrorMsg($msg,$type);
		return $_SESSION['msg'];
	}

	static function _ftpClient() {
		$ftp = new \Vendor\FtpClient\FtpClient;
		return $ftp;
	}

}

?>
