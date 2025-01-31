<?php

namespace Admin\Application\Controller;

class ListingsController extends \Main\Controller {
	
	public $doc;
	public $session;
	
	function __construct() {
		$this->setTempalteBasePath(ROOT."Admin");
		$this->doc = $this->getLibrary("Factory")->getDocument();

		$this->session = $this->getLibrary("SessionHandler")->get("user_logged");
	}

	function index($account_id) {

		$this->doc->setTitle("Property Listings");
	
		if(isset($_REQUEST['search'])) {
			$filters[] = " (title LIKE '%".$_REQUEST['search']."%')";
			$uri['search'] = $_REQUEST['search'];
		}

		$account = $this->getModel("Account");
		$account->column['account_id'] = $account_id;
		$data = $account->getById();

		$filters[] = " account_id = $account_id ";
		
		$listing = $this->getModel("Listing");
		$listing->where((isset($filters) ? implode(" AND ",$filters) : null))->orderby(" last_modified DESC ");
		
		$listing->page['limit'] = 20;
		$listing->page['current'] = isset($_REQUEST['page']) ? $_REQUEST['page'] : 1;
		$listing->page['target'] = url("ListingsController@index");
		$listing->page['uri'] = (isset($uri) ? $uri : []);
		
		$data['listings'] = $listing->getList();

		$this->setTemplate("listings/listings.php");
		return $this->getTemplate($data,$listing);
		
	}
	
	function edit($account_id, $listing_id) {
		
		$this->doc->setTitle("Update Property Listing");
		$this->doc->addScript(CDN."tinymce/tinymce.min.js");
		$this->doc->addScript(CDN."js/photo-uploader.js");
		$this->doc->addScript(CDN."tabler/dist/libs/tom-select/dist/js/tom-select.base.min.js?1695847769");

		$this->doc->addScriptDeclaration("
			$(document).ready(function() {
				if (localStorage.getItem('items') !== null) {
					localStorage.clear();
				}
			});
			
			$(document).on('change', '#is_mls_local_board, #is_mls_local_region, #is_mls_all', function() {
				if(this.checked) {
					$('#is_mls').prop('checked', true);
				}
			})

			$(document).on('change', '#status', function() {
				if($('#status option:selected').val() == 2) {
					$('.sold-price-input').removeClass('d-none');
				}else {
					$('.sold-price-input').addClass('d-none');
				}
			});

			$(document).on('click','.selection-tab',function() {
				link = $(this).data('link');
				$('.nav-tabs a[href=\"' + link + '\"]').tab('show');
			});
		");

		$account = $this->getModel("Account");
		$account->column['account_id'] = $account_id;
		$data = $account->getById();
		
		$listing = $this->getModel("Listing");
		$listing->column['listing_id'] = $listing_id;
		$listing->and(" account_id = $account_id ");

		$listing->addresses = $this->getModel("Address");

		$data['listing'] = $listing->getById();
		
		if($data['listing']) {

			$listingImage = $this->getModel("ListingImage");
			$listingImage->column['listing_id'] = $listing_id;
			$data['listing']['images'] = $listingImage->getByListingId();

			$this->setTemplate("listings/edit.php");
			return $this->getTemplate($data,$listing);
		}

		$this->response(404);
		
	}
	
	function add($account_id) {

		$this->doc->setTitle("New Property Listings");
		$this->doc->addScript(CDN."tinymce/tinymce.min.js");
		$this->doc->addScript(CDN."js/photo-uploader.js");
		$this->doc->addScript(CDN."tabler/dist/libs/tom-select/dist/js/tom-select.base.min.js?1695847769");

		$this->doc->addScriptDeclaration("
			$(document).ready(function() {
				if (localStorage.getItem('items') !== null) {
					localStorage.clear();
				}
			});

			$(document).on('change', '#is_mls_local_board, #is_mls_local_region, #is_mls_all', function() {
				if(this.checked) {
					$('#is_mls').prop('checked', true);
				}
			})
		");

		$account = $this->getModel("Account");
		$account->column['account_id'] = $account_id;
		$data = $account->getById();

		$listing = $this->getModel("Listing");
		
		$listing->select(" COUNT(listing_id) AS total ");
		$listing->column['account_id'] = $account_id;
		$data['listings'] = $listing->getByAccountId();

		$subscription = $this->getModel("AccountSubscription");
		$subscription->column['account_id'] = $account_id;
		$privileges = $subscription->getSubscription();

		if($privileges === false) {
			$data['privileges'] = $data['privileges'];
		}else {
			$data['privileges'] = $privileges;
		}

		if($data['privileges']['max_post'] <= $data['listings'][0]['total']) {
			$this->getLibrary("Factory")->setMsg("Maximum postings have been reached. You cannot add any more property listings. Subscribe to our premium package to increase your maximum postings allowance", "warning");
			if($_SESSION['domain'] == ADMIN) {
				response()->redirect(url("ListingsController@index",["id" => $data['account_id']]));
			}else {
				response()->redirect(url("ListingsController@listingIndex"));
			}
		}else {

			$listing->addresses = $this->getModel("Address");

			$this->setTemplate("listings/add.php");
			return $this->getTemplate($data, $listing);
			
		}
		
	}

	function view($listing_id) {
		
		$this->doc->setTitle("View Property Listing");
		$this->doc->addScript(CDN."bxslider/src/js/jquery.bxslider.js");
		$this->doc->addStylesheet(CDN."bxslider/src/css/jquery.bxslider.css");

		$this->doc->addScriptDeclaration("
			$(document).ready(function(){
				$('.slider').bxSlider({
					slideWidth: 600,
					auto: true,
					autoControls: true,
					pause: 3000,
					mode: 'fade'
				});
			});
		");
		
		$listing = $this->getModel("Listing");
		$listing->column['listing_id'] = $listing_id;
		
		$data['listing'] = $listing->getById();

		if($data) {

			$account = $this->getModel("Account");
			$account->select(" account_id, logo, profession, real_estate_license_number, account_name, mobile_number, email, registration_date");
			$account->column['account_id'] = $data['listing']['account_id'];
			$data['account'] = $account->getById();

			$listingImage = $this->getModel("ListingImage");
			$listingImage->page['limit'] = 100;
			$listingImage->column['listing_id'] = $listing_id;
			$data['listing']['images'] = $listingImage->getByListingId();

			if(!$data['listing']['images']) {
				$data['listing']['images'] = [];
			}

			$handshake = $this->getModel("Handshake");
			$handshake->column['requestor_account_id'] = $_SESSION['user_logged']['account_id'];
			$handshake->and(" listing_id = ".$listing_id." AND handshake_status NOT IN('done','cancel','denied')");
			$data['handshake'] = $handshake->getByRequestorAccountId();

			if($data['listing']['account_id'] !== $this->session['account_id']) {
				$traffic = $this->getModel("ListingView");
				$traffic->column['session_id'] = $this->getLibrary("SessionHandler")->get("id");
				
				if(!$traffic->getBySessionId()) {
					$traffic->saveNew(array(
						"listing_id" => $data['listing']['listing_id'],
						"account_id" => $data['account']['account_id'],
						"session_id" => $this->getLibrary("SessionHandler")->get("id"),
						"created_at" => DATE_NOW,
						"user_agent" => json_encode($this->getLibrary("SessionHandler")->get("user_agent"))
					));
				}

			}

			$this->setTemplate("listings/view.php");
			return $this->getTemplate($data,$listing);
		}

		$this->response(404);
		
	}
	
	function saveNew() {
		
		parse_str(file_get_contents('php://input'), $_POST);
		
		$_POST['name'] = sanitize($_POST['title']);
		$_POST['date_added'] = DATE_NOW;
		$_POST['last_modified'] = DATE_NOW;
		$_POST['thumb_img'] = $_POST['thumb_img'] != "" ? CDN."/images/listings/".$_POST['thumb_img'] : null;
		$_POST['foreclosed'] = isset($_POST['foreclosed']) ? $_POST['foreclosed'] : 0;
		$_POST['is_mls'] = isset($_POST['is_mls']) ? $_POST['is_mls'] : 0;
		$_POST['is_website'] = isset($_POST['is_website']) ? $_POST['is_website'] : 0;
		
		$_POST['payment_details'] = json_encode([
			"option_money_duration" => $_POST['payment_details']['option_money_duration'],
			"payment_mode" => $_POST['payment_details']['payment_mode'],
			"tax_allocation" => $_POST['payment_details']['tax_allocation'],
			"bank_loan" => isset($_POST['payment_details']['bank_loan']) ? 1 : 0,
			"pagibig_loan" => isset($_POST['payment_details']['pagibig_loan']) ? 1 : 0,
			"assume_balance" => isset($_POST['payment_details']['assume_balance']) ? 1 : 0
		]);

		$_POST['other_details'] = json_encode([
			"authority_type" => $_POST['authority_type'],
			"authority_to_sell_expiration" => strtotime($_POST['authority_to_sell_expiration']),
			"com_share" => $_POST['com_share']
		]);
		
		if(isset($_POST['address'])) { $_POST['address'] = json_encode($_POST['address']); }
		if(isset($_POST['tags'])) { $_POST['tags'] = json_encode($_POST['tags']); }
		if(isset($_POST['amenities'])) {$_POST['amenities'] = implode(",",$_POST['amenities']); }

		$_POST['is_mls_option'] = json_encode([
			"local_board" => isset($_POST['mls_local_board']) ? 1 : 0,
			"local_region" => isset($_POST['mls_local_region']) ? 1 : 0,
			"mls_all" => isset($_POST['mls_all']) ? 1 : 0
		]);
	
		$listing = $this->getModel("Listing");
		$response = $listing->saveNew($_POST);

		if($response['status'] == 1) {

			if(isset($_POST['listing_image_filename'])) {
				$listingImage = $this->getModel("ListingImage");
				$listingImage->saveImages($response['id'],$_POST['listing_image_filename']);
				
				unset($_POST['listing_image_url']);
				unset($_POST['listing_image_filename']);
			}

		}

		$this->getLibrary("Factory")->setMsg($response['message'],$response['type']);

		return json_encode(array(
			"status" => $response['status'],
			"message" => getMsg()
		));
		
	}
	
	function saveUpdate($id) {

		parse_str(file_get_contents('php://input'), $_POST);

		if($_POST['status'] == "available") {
			$listing = $this->getModel("Listing");
			$listing->select(" COUNT(listing_id) ")->where(" account_id = $account_id AND status = 'available' ");
			$total_listing = $listing->getList();

			if($total_listing > $_SESSION['user_logged']['privileges']['max_post']) {
				$this->getLibrary("Factory")->setMsg("This account has already reached the maximum number of listings; therefore, the property cannot activate", "warning");

				return json_encode(
					array(
						"status" => $response['status'],
						"message" => getMsg()
					)
				);
			}
		}
		
		$_POST['name'] = sanitize($_POST['title']);
		$_POST['last_modified'] = DATE_NOW;
		$_POST['thumb_img'] = $_POST['thumb_img'] != "" ? CDN."images/listings/".$_POST['thumb_img'] : null;
		$_POST['foreclosed'] = isset($_POST['foreclosed']) ? 1 : 0;
		$_POST['is_mls'] = isset($_POST['is_mls']) ? 1 : 0;
		$_POST['is_website'] = isset($_POST['is_website']) ? 1 : 0;

		$_POST['payment_details'] = json_encode([
			"option_money_duration" => $_POST['payment_details']['option_money_duration'],
			"payment_mode" => $_POST['payment_details']['payment_mode'],
			"tax_allocation" => $_POST['payment_details']['tax_allocation'],
			"bank_loan" => isset($_POST['payment_details']['bank_loan']) ? 1 : 0,
			"pagibig_loan" => isset($_POST['payment_details']['pagibig_loan']) ? 1 : 0,
			"assume_balance" => isset($_POST['payment_details']['assume_balance']) ? 1 : 0
		]);
		
		$_POST['other_details'] = json_encode([
			"authority_type" => $_POST['authority_type'],
			"authority_to_sell_expiration" => strtotime($_POST['authority_to_sell_expiration']),
			"com_share" => $_POST['com_share']
		]);

		$_POST['is_mls_option'] = json_encode([
			"local_board" => isset($_POST['mls_local_board']) ? 1 : 0,
			"local_region" => isset($_POST['mls_local_region']) ? 1 : 0,
			"all" => isset($_POST['mls_all']) ? 1 : 0
		]);

		if(isset($_POST['address'])) { $_POST['address'] = json_encode($_POST['address']); }
		if(isset($_POST['tags'])) { $_POST['tags'] = json_encode($_POST['tags']); }
		if(isset($_POST['amenities'])) {$_POST['amenities'] = implode(",",$_POST['amenities']); }

		$listing = $this->getModel("Listing");
		$response = $listing->save($id,$_POST);
		
		if(isset($_POST['listing_image_filename'])) {
			$listingImage = $this->getModel("ListingImage");
			$listingImage->saveImages($id,$_POST['listing_image_filename']);
		}

		$this->getLibrary("Factory")->setMsg($response['message'],$response['type']);

		return json_encode(array(
			"status" => $response['status'],
			"message" => getMsg()
		));
		
	}
	
	function uploadImages($account_id) {

		if(isset($_FILES['ImageBrowse'])) {
			$listing = $this->getModel("Listing");
			return $listing->uploadImage($_FILES['ImageBrowse']);
		}else {
			$this->getLibrary("Factory")->setMsg("There was something wrong, Please try selecting your image again.","info");
			$uploadedImages[] = array(
				"status" => 2,
				"message" => getMsg()
			);

			return json_encode($uploadedImages);
		}
		
	}
	
	function delete($id) {

		if(!$this->session['permissions']['properties']['delete']) {
			$this->getLibrary("Factory")->setMsg("You do not have permissions to access this content.", "warning");
			return getMsg();
		}

		$listing = $this->getModel("Listing");
		$listing->column['listing_id'] = $id;
		$data = $listing->getById();
		
		if($data) {

			if(isset($_REQUEST['delete'])) {

				$listing_image = $this->getModel("ListingImage");
				$listing_image->deleteListingImages($id);
				$listing->deleteListing($id);

				$this->getLibrary("Factory")->setMsg("Listing permanently deleted!","success");
				return json_encode(
					array(
						"status" => 1,
						"message" => getMsg()
					)
				);

			}

		}else {
			$this->getLibrary("Factory")->setMsg("Property listing not found.","warning");
		}

		$this->setTemplate("listings/delete.php");
		return $this->getTemplate($data);

	}
	
}