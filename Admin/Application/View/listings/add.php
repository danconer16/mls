<?php

$html[] = "<style type='text/css'>";
	$html[] = "
	input[type=file] {
		position: absolute;
		left: -10000px;
		top: 0;
		opacity: 0;
	}";
$html[] = "</style>";

$html[] = "<input type='hidden' id='save_url' value='".url("ListingsController@saveNew", ["id" => $data['account_id']])."' />";

$html[] = "<div class='row g-0 justify-content-center mb-5 pb-5'>";
	$html[] = "<div class='col-lg-8 col-md-8 col-sm-12 col-12'>";

		$html[] = "<div class='page-header d-print-none text-white'>";
			$html[] = "<div class='container-xl'>";
				$html[] = "<div class='row g-2 '>";
					$html[] = "<div class='col'>";
						$html[] = "<div class='page-pretitle'>Manage Property Listing of ".$data['firstname']." ".$data['lastname']."</div>";
						$html[] = "<h1 class='page-title'><span class='stamp stamp-md me-1'><i class='ti ti-home me-1'></i></span> Update Property Listing</h1>";
					$html[] = "</div>";
					$html[] = "<div class='col-auto ms-auto d-print-none'>";
						$html[] = "<div class='page-options text-end'>";
							$html[] = "<div class='btn-list'>";
								if($data['account_type'] != "Administrator") {
									$html[] = "<a class='ajax btn btn-dark' href='".url("AccountsController@view", ["id" => $data['account_id']])."'>";
										$html[] = "<span class='avatar avatar-sm' style='background-image: url(".$data['logo'].")'></span>";
										$html[] = $data['firstname']." ".$data['lastname']." account";
									$html[] = "</a>";
									$html[] = "<a class='btn btn-dark' href='".url("ListingsController@index",["id" => $data['account_id']])."' title='Listings'><i class='ti ti-list me-1'></i> Property Listings</a>";
								}else {
									$html[] = "<a class='btn btn-dark' href='".url("ListingsController@listingIndex")."' title='Listings'><i class='ti ti-list me-1'></i> Property Listings</a>";
								}
							$html[] = "</div>";
						$html[] = "</div>";
					$html[] = "</div>";
				$html[] = "</div>";
			$html[] = "</div>";
		$html[] = "</div>";

		/** START PAGE BODY */
		$html[] = "<div class='page-body'>";
			$html[] = "<div class='container-xl'>";

				$html[] = "<div class='response'>";
					$html[] = getMsg();
				$html[] = "</div>";

				$html[] = "<input type='hidden' id='photo_uploader' value='listings' />";
				$html[] = "<form action='".url("ListingsController@uploadImages", ["id" => $data['account_id']])."' id='imageUploadForm' method='POST' enctype='multipart/form-data'>";
					$html[] = "<center>";
						$html[] = "<input type='file' name='ImageBrowse[]' id='ImageBrowse' multiple='multiple' accept='image/*' />";
					$html[] = "</center>";
				$html[] = "</form>";

				$html[] = "<form id='form' action='' method='POST'>";
					$html[] = "<input name='_method' id='_method' type='hidden' value='post' />";
					$html[] = "<input name='thumb_img' id='thumb_img' type='hidden' value='' />";
					$html[] = "<input name='account_id' id='account_id' type='hidden' value='".$data['account_id']."' />";
					$html[] = "<input name='last_modified' id='last_modified' type='hidden' value='".DATE_NOW."' />";

					$html[] = "<div class='card mb-3'>";
						$html[] = "<div class='card-header pt-4'>";
							$html[] = "<ul class='nav nav-tabs card-header-tabs' data-bs-toggle='tabs' role='tablist'>";
								$html[] = "<li class='nav-item' role='pressentation'><a href='#property_description' 	class='pb-3 fw-bold text-blue nav-link active' data-bs-toggle='tab' aria-selected='true'><i class='ti ti-file-description me-2'></i> Property Description</a></li>";
								$html[] = "<li class='nav-item' role='pressentation'><a href='#technical_description' 	class='pb-3 fw-bold text-blue nav-link' data-bs-toggle='tab' aria-selected='false'><i class='ti ti-ruler me-2'></i> Technical Details</a></li>";
								$html[] = "<li class='nav-item' role='pressentation'><a href='#payment_details' 		class='pb-3 fw-bold text-blue nav-link' data-bs-toggle='tab' aria-selected='false'><i class='ti ti-cash me-2'></i> Payment Details</a></li>";
								$html[] = "<li class='nav-item' role='pressentation'><a href='#images_list' 			class='pb-3 fw-bold text-blue nav-link' data-bs-toggle='tab' aria-selected='false'><i class='ti ti-photo me-2'></i> Images</a></li>";
								$html[] = "<li class='nav-item' role='pressentation'><a href='#settings'	 			class='pb-3 fw-bold text-blue nav-link' data-bs-toggle='tab' aria-selected='false'><i class='ti ti-photo me-2'></i> Settings</a></li>";
							$html[] = "</ul>";
						$html[] = "</div>";
						
						$html[] = "<div class='card-body'>";
							$html[] = "<div class='tab-content'>";
								
								$html[] = "<div id='property_description' class='tab-pane active'>";
									
									/***** PROPERTY DESCRIPTION *****/
									
									$html[] = "<div class='row justify-content-center py-3'>";
										$html[] = "<div class='col-md-8 col-lg-8 col-12'>";

											$html[] = "<div class='form-group mb-3'>";
												$html[] = "<label class='form-label text-muted'>Title</label>";
												$html[] = "<div class='input-icon mb-3'>";
													$html[] = "<span class='input-icon-addon'><i class='ti ti-writing'></i></span>";
													$html[] = "<input type='text' name='title' id='title' value='' class='form-control' placeholder='Title' />";
												$html[] = "</div>";
												$html[] = "<p class='p-0 text-info'>Do not include \"For Sale\", \"RFO\", \"Re-Sale\" in your title.</p>";
											$html[] = "</div>";
												
											$html[] = "<div class='form-group mb-3'>";
												$html[] = "<label class='form-label text-muted'>Description</label>";
												$html[] = "<textarea id='snow-container' name='long_desc' class='form-control'></textarea>";
											$html[] = "</div>";

										$html[] = "</div>";
									$html[] = "</div>";
									
								$html[] = "</div>";
								
								$html[] = "<div id='technical_description' class='tab-pane '>";
									/***** TECHNICAL DESCRIPTION *****/
										
									$html[] = "<div class='row py-3'>";
										$html[] = "<div class='col-lg-8 col-md-8 col-12 m-auto'>";

											$html[] = "<div class='row border mb-4 p-3 bg-azure-lt'>";
												$html[] = "<div class='col-lg-6 col-md-6'>";

													$html[] = "<label class='form-label text-muted mb-2'>Foreclosure Property</label>";
													$html[] = "<div class='form-group'>";
														$html[] = "<label class='form-check form-switch cursor-pointer'>";
															$html[] = "<input class='form-check-input' type='checkbox' name='foreclosed' value='1' id='foreclosure'  />";
															$html[] = "<span class='form-check-label' for='foreclosure'>Is this foreclosure property?</span>";
														$html[] = "</label>";
													$html[] = "</div>";
													
												$html[] = "</div>";
												$html[] = "<div class='col-lg-6 col-md-6'>";

													$html[] = "<div class=''>";
														$html[] = "<label class='form-label text-muted'>Property Status</label>";
														$html[] = "<div class='input-icon '>";
															$html[] = "<span class='input-icon-addon'><i class='ti ti-status-change'></i></span>";
															$html[] = "<select name='status' id='status' class='form-select'>";
																$statuses = array(1=>"Available",2=>"Sold");
																foreach($statuses as $key => $val) {
																	$html[] = "<option value='$key'>$val</option>";
																}
															$html[] = "</select>";
															$html[] = "<span class='input-icon-addon'><i class='ti ti-caret-down-filled'></i></span>";
														$html[] = "</div>";
													$html[] = "</div>";
													
												$html[] = "</div>";
											$html[] = "</div>";

											$html[] = "<div class='row'>";
												$html[] = "<div class='col-lg-6 col-md-6'>";
										
													$html[] = "<div class='form-group mb-3'>";
														$html[] = "<label class='form-label text-muted'>Offer</label>";
														$html[] = "<div class='input-icon mb-3'>";
															$html[] = "<span class='input-icon-addon'><i class='ti ti-tags'></i></span>";
															$html[] = "<select class='form-control' name='offer' id='offer'>";
																$offer_type = array("For Sale","For Rent");
																foreach($offer_type as $key => $val) {
																	$html[] = "<option value='".strtolower($val)."'>$val</option>";
																}
															$html[] = "</select>";
															$html[] = "<span class='input-icon-addon'><i class='ti ti-caret-down-filled'></i></span>";
														$html[] = "</div>";
													$html[] = "</div>";
												
												$html[] = "</div>";
												$html[] = "<div class='col-lg-6 col-md-6'>";

													$html[] = "<div class='form-group mb-3'>";
														$html[] = "<label class='form-label text-muted'>Property Type</label>";
														$html[] = "<div class='input-icon mb-3'>";
															$html[] = "<span class='input-icon-addon'><i class='ti ti-building-estate'></i></span>";
															$html[] = "<select class='form-control' name='type' id='type'>";
																$offer_type = array("Residential","Commercial");
																foreach($offer_type as $key => $val) {
																	$html[] = "<option value='".$val."'>$val</option>";
																}
															$html[] = "</select>";
															$html[] = "<span class='input-icon-addon'><i class='ti ti-caret-down-filled'></i></span>";
														$html[] = "</div>";
													$html[] = "</div>";

												$html[] = "</div>";
											$html[] = "</div>";

											
											
											$html[] = "<div class='form-group mb-3'>";
												$html[] = "<label class='form-label text-muted'>Category</label>";
												$html[] = "<div class='input-icon mb-3'>";
													$html[] = "<span class='input-icon-addon'><i class='ti ti-building-store'></i></span>";
													$html[] = $model->categorySelection();
													$html[] = "<span class='input-icon-addon'><i class='ti ti-caret-down-filled'></i></span>";
												$html[] = "</div>";
											$html[] = "</div>";
										
											$html[] = "<div class='row'>";
												$html[] = "<div class='col-md-4 col-lg-4 col-12'>";
													$html[] = "<div class='form-group mb-3'>";
														$html[] = "<label class='form-label text-muted'>Bedroom</label>";
														$html[] = "<div class='input-icon mb-3'>";
															$html[] = "<span class='input-icon-addon'><i class='ti ti-bed-flat'></i></span>";
															$html[] = "<select class='form-select' name='bedroom' id='bedroom'>";
																$html[] = "<option value='Studio'>Studio</option>";
																for($i=1; $i<11; $i++) {
																	$html[] = "<option value='$i'>$i Bedroom</option>";
																}
															$html[] = "</select>";
															$html[] = "<span class='input-icon-addon'><i class='ti ti-caret-down-filled'></i></span>";
														$html[] = "</div>";
													$html[] = "</div>";
												$html[] = "</div>";
												$html[] = "<div class='col-md-4 col-lg-4 col-12'>";
													$html[] = "<div class='form-group mb-3'>";
														$html[] = "<label class='form-label text-muted'>Bathroom</label>";
														$html[] = "<div class='input-icon mb-3'>";
															$html[] = "<span class='input-icon-addon'><i class='ti ti-bath'></i></span>";
															$html[] = "<select class='form-select' name='bathroom' id='bathroom'>";
																for($i=0; $i<11; $i++) {
																	$html[] = "<option value='$i'>".($i == 0 ? "No" : $i)." Bathroom</option>";
																}
															$html[] = "</select>";
															$html[] = "<span class='input-icon-addon'><i class='ti ti-caret-down-filled'></i></span>";
														$html[] = "</div>";
													$html[] = "</div>";
												$html[] = "</div>";
												$html[] = "<div class='col-md-4 col-lg-4 col-12'>";
													$html[] = "<div class='form-group mb-3'>";
														$html[] = "<label class='form-label text-muted'>Car Garage</label>";
														$html[] = "<div class='input-icon mb-3'>";
															$html[] = "<span class='input-icon-addon'><i class='ti ti-car-garage'></i></span>";
															$html[] = "<select class='form-select' name='parking' id='parking'>";
																for($i=0; $i<11; $i++) {
																	$html[] = "<option value='$i'>".($i == 0 ? "No Garage" : $i." car slot")."</option>";
																}
															$html[] = "</select>";
															$html[] = "<span class='input-icon-addon'><i class='ti ti-caret-down-filled'></i></span>";
														$html[] = "</div>";
													$html[] = "</div>";
												$html[] = "</div>";
											$html[] = "</div>";
											
											$html[] = "<div class='row'>";

												$tech_details = array("floor_area","lot_area");
												foreach($tech_details as $details) {
													$label = ucwords(str_replace("_"," ",$details));
													$html[] = "<div class='col-md-6 col-lg-6 col-12'>";
														$html[] = "<div class='mb-3'>";
															$html[] = "<label class='form-label text-muted'>$label</label>";
															$html[] = "<div class='input-icon mb-3'>";
																$html[] = "<input type='text' name='$details' id='$details' value='' class='form-control' placeholder='$label' />";
																$html[] = "<span class='input-icon-addon'><small>sq.m <i class='ti ti-ruler me-1'></i></small></span>";
															$html[] = "</div>";
														$html[] = "</div>";
													$html[] = "</div>";
												}

											$html[] = "</div>";

											$html[] = "<div class='form-group mb-3'>";
												$html[] = "<label class='form-label text-muted'>Tags</label>";
												$html[] = "<div class='input-icon mb-3'>";
													$html[] = "<span class='input-icon-addon'><i class='ti ti-building-cottage'></i></span>";
													$html[] = "<select class='form-select' name='tags[]' id='tags' multiple='multiple'>";
														/** Additional tags can be add in /includes/define.php */
														foreach(PROPERTY_TAGS as $key => $val) {
															$html[] = "<option value='$val'>$val</option>";
														}
													$html[] = "</select>";
													$html[] = "<span class='input-icon-addon'><i class='ti ti-caret-down-filled'></i></span>";
												$html[] = "</div>";
											$html[] = "</div>";

											$html[] = "<div class='form-group mb-3'>";
												$html[] = "<label class='form-label text-muted'>Address</label>";
												$html[] = $model->addresses->addressSelection();
											$html[] = "</div>";

										$html[] = "</div>";
									$html[] = "</div>";

									$html[] = "<div class='amenities-wrap mt-3'>";
										$html[] = "<div class='form-group'>";
											$html[] = "<label class='form-label text-muted'><i class='ti ti-home-shield me-2'></i> Features and Amenities</label>";
											
											$amenities = $model->amenities();
											$amenities_data = explode(", ","24 Hours Security, Near in Churches, Near in Schools, Near Malls, Near Hospitals, Gated Community, CCTV Cameras, Near Public Markets, Guard House, Club House");
											
											$html[] = "<div class='row p-4 border  bg-yellow-lt text-dark'>";
												for($i=0; $i<count($amenities); $i++) {
												
													$check = in_array($amenities[$i],$amenities_data) ? "checked" : "";
												
													$html[] = "<div class='col-3'>";
														$html[] = "<label class='form-check'>";
															$html[] = "<input type='checkbox' class='form-check-input' id='customCheck_$i' name='amenities[]' value='".$amenities[$i]."' $check>";
															$html[] = "<span class='form-check-label' for='customCheck_$i'>".$amenities[$i]."</span>";
														$html[] = "</label>";
													$html[] = "</div>";

												}
											$html[] = "</div>";
											
										$html[] = "</div>";
									$html[] = "</div>";
									
								$html[] = "</div>";
								
								$html[] = "<div id='payment_details' class='tab-pane '>";
									/***** PAYMNENT DETAILS *****/
								
									$html[] = "<div class='row justify-content-center py-3'>";
										$html[] = "<div class='col-md-8 col-lg-8 col-12'>";

											$html[] = "<div class='form-group mb-3'>";
												$html[] = "<label class='form-label text-muted'>Price</label>";
												$html[] = "<div class='input-icon mb-3'>";
													$html[] = "<span class='input-icon-addon'><i class='ti ti-currency-peso'></i></span>";
													$html[] = "<input type='number' name='price' id='price' value='0' step='0.05' class='form-control' placeholder='Price' />";
												$html[] = "</div>";
											$html[] = "</div>";
											
											$html[] = "<div class='form-group mb-3'>";
												$html[] = "<label class='form-label text-muted'>Reservation</label>";
												$html[] = "<div class='input-icon mb-3'>";
													$html[] = "<span class='input-icon-addon'><i class='ti ti-currency-peso'></i></span>";
													$html[] = "<input type='number' name='reservation' id='reservation' value='0' step='0.05' class='form-control' placeholder='Reservation' />";
												$html[] = "</div>";
											$html[] = "</div>";
											
											$html[] = "<div class='form-group mb-3'>";
												$html[] = "<label class='form-label text-muted'>Monthly Down Payment</label>";
												$html[] = "<div class='input-icon mb-3'>";
														$html[] = "<span class='input-icon-addon'><i class='ti ti-currency-peso'></i></span>";
														$html[] = "<input type='number' name='monthly_downpayment' id='monthly_downpayment' value='0' step='0.05' class='form-control' placeholder='Monthly Downpayment' />";
												$html[] = "</div>";
											$html[] = "</div>";
											
											$html[] = "<div class='form-group mb-3'>";
												$html[] = "<label class='form-label text-muted'>Monthly Amortization</label>";
												$html[] = "<div class='input-icon mb-3'>";
													$html[] = "<span class='input-icon-addon'><i class='ti ti-currency-peso'></i></span>";
													$html[] = "<input type='number' name='monthly_amortization' id='monthly_amortization' value='0' step='0.05' class='form-control' placeholder='Monthly Amortization' />";
												$html[] = "</div>";
											$html[] = "</div>";

										$html[] = "</div>";
									$html[] = "</div>";
									
								$html[] = "</div>";
								
								$html[] = "<div id='images_list' class='tab-pane '>";
									/***** IMAGES *****/
									
									$html[] = "<div class='row py-3'>";
										$html[] = "<div class='col-lg-8 col-md-8 col-12'>";

											$html[] = "<div class='card mb-3 bg-dark-lt'>";
												$html[] = "<div class='card-body'>";
													$html[] = "<div class='text-center'>";
														$html[] = "<p style='' class='p-0 m-0'>";
														$html[] = "<span class='photo-upload-loader'></span>";
														$html[] = "<span class='btn btn-dark btn-photo-browse'><i class='ti ti-upload me-2'></i> Upload Images</span></p>";
													$html[] = "</div>";
												$html[] = "</div>";
											$html[] = "</div>";
											
											$html[] = "<div class='upload-response'></div>";
											
											$html[] = "<div class='' style='max-height:520px; overflow-y:auto;'>";
												$html[] = "<div class='d-flex flex-wrap justify-content-center images-container m-0'>";
													$html[] = "<span class='text-muted'>Images will be shown here.</span>";
												$html[] = "</div>";
											$html[] = "</div>";
												
										$html[] = "</div>";

										$html[] = "<div class='col-lg-4 col-md-4 d-md-block d-none'>";

											$html[] = "<div class='card mb-3'>";

												$html[] = "<div class='card-status-top bg-azure'></div>";
												$html[] = "<div class='card-stamp'>";
													$html[] = "<div class='card-stamp-icon bg-azure'><i class='ti ti-info-circle'></i></div>";
												$html[] = "</div>";

												$html[] = "<div class='card-body'>";
													$html[] = "<p>Please read the following before uploading images</p>";
													$html[] = "<ul class='list-group'>";
														$html[] = "<li class='list-group-item'><i class='ti ti-arrow-badge-right me-2 text-danger'></i>Only .jpg, .png, .gif are allowed</li>";
														$html[] = "<li class='list-group-item'><i class='ti ti-arrow-badge-right me-2 text-danger'></i>Select 5 or less images per upload</li>";
														$html[] = "<li class='list-group-item'><i class='ti ti-arrow-badge-right me-2 text-danger'></i>Images less than 2MB file sizes are allowed</li>";
														$html[] = "<li class='list-group-item'><i class='ti ti-arrow-badge-right me-2 text-danger'></i>Resize your images before uploading</li>";
														$html[] = "<li class='list-group-item'><i class='ti ti-arrow-badge-right me-2 text-danger'></i>For website compatibility, only upload landscape images</li>";
													$html[] = "</ul>";
												$html[] = "</div>";
											$html[] = "</div>";

										$html[] = "</div>";
									$html[] = "</div>";

								$html[] = "</div>";

								$html[] = "<div id='settings' class='tab-pane '>";
									/***** SETTINGS *****/
									
									$html[] = "<div class='row justify-content-center py-3'>";
										$html[] = "<div class='col-md-8 col-lg-8 col-12'>";

											$html[] = "<div class='mb-3'>";
												$html[] = "<label class='form-label text-muted'>Property Status</label>";
												$html[] = "<div class='input-icon '>";
													$html[] = "<span class='input-icon-addon'><i class='ti ti-status-change'></i></span>";
													$html[] = "<select name='status' id='status' class='form-select'>";
														$statuses = array(1=>"Available",2=>"Sold");
														foreach($statuses as $key => $val) {
															$html[] = "<option value='$key' >$val</option>";
														}
													$html[] = "</select>";
													$html[] = "<span class='input-icon-addon'><i class='ti ti-caret-down-filled'></i></span>";
												$html[] = "</div>";
											$html[] = "</div>";

											$html[] = "<div class='form-group mb-3'>";
												$html[] = "<label class='form-check form-switch cursor-pointer'>";
													$html[] = "<input class='form-check-input' type='checkbox' name='foreclosed' value='1' id='foreclosure' />";
													$html[] = "<span class='form-check-label' for='foreclosure'>Is this property a foreclosure?</span>";
												$html[] = "</label>";
											$html[] = "</div>";
													
											$html[] = "<div class='form-group mb-3'>";
												$html[] = "<label class='form-check form-switch cursor-pointer'>";
													$html[] = "<input class='form-check-input cursor-pointer' name='is_mls' type='checkbox' value='1' id='is_mls' checked  />";
													$html[] = "<span class='form-check-label cursor-pointer' for='is_mls'>Display this property listing on the Multiple Listing Service (MLS)</span>";
												$html[] = "</label>";
											$html[] = "</div>";

											$html[] = "<div class='form-group mb-4'>";
												$html[] = "<label class='form-check form-switch cursor-pointer'>";
													$html[] = "<input class='form-check-input cursor-pointer' name='is_website' type='checkbox' value='1' id='is_website' checked   />";
													$html[] = "<span class='form-check-label cursor-pointer' for='is_website'>Publish this property listing on the website.</span>";
												$html[] = "</label>";
											$html[] = "</div>";

											$html[] = "<div class='mb-3'>";
												$html[] = "<label class='form-label text-muted'>Commission Sharing Details</label>";
												$html[] = "<div class='input-icon mb-2'>";
													$html[] = "<span class='input-icon-addon'><i class='ti ti-percentage'></i></span>";
													$html[] = "<input type='number' name='com_share' id='com_share' value='1.5' step='0.5' class='form-control' placeholder='Commission Share' />";
												$html[] = "</div>";
												$html[] = "<span class='form-hint'>Please specify the percentage of commission you are prepared to distribute.</span>";
											$html[] = "</div>";

											$html[] = "<div class='mb-3'>";
												$html[] = "<label class='form-label text-muted'>What type of authority do you hold for this property?</label>";
												$html[] = "<div class='input-icon mb-3'>";
													$html[] = "<span class='input-icon-addon'><i class='ti ti-certificate'></i></span>";
													$html[] = "<select class='form-select' name='authority_type' id='authority_type'>";
														foreach(["N/A","Non-Exclusive Authority To Sell", "Exclusive Authority To Sell"] as $authority) {
															$html[] = "<option value='$authority'>".$authority."</option>";
														}
													$html[] = "</select>";
													$html[] = "<span class='input-icon-addon'><i class='ti ti-caret-down-filled'></i></span>";
												$html[] = "</div>";
												$html[] = "<span class='form-hint'>The legal permission granted to an individual or entity to sell a property on behalf of the owner(s)</span>";
											$html[] = "</div>";

										$html[] = "</div>";
									$html[] = "</div>";
									
								$html[] = "</div>";
								
							$html[] = "</div>"; /*** TAB CONTENT END ***/
						$html[] = "</div>";
					$html[] = "</div>";
					
				$html[] = "</form>";
			
			$html[] = "</div>";
		$html[] = "</div>";

	$html[] = "</div>";
$html[] = "</div>";

$html[] = "<div class='btn-save-container fixed-bottom bg-white py-3 border-top'>";
	$html[] = "<div class='row g-0 justify-content-center'>";
		$html[] = "<div class='col-lg-8 col-md-8 col-sm-12 col-12'>";

			$html[] = "<div class='container-xl'>";
				$html[] = "<div class='text-end'>";
					$html[] = "<span class='btn btn-outline-primary btn-save'><i class='ti ti-device-floppy me-2'></i> Save Property Listing</span>";
				$html[] = "</div>";
			$html[] = "</div>";

		$html[] = "</div>";
	$html[] = "</div>";
$html[] = "</div>";

$html[] = "<script type='text/javascript'>";
	$html[] = "
	
	document.addEventListener('DOMContentLoaded', function () {
    	var el;
    	window.TomSelect && (new TomSelect(el = document.getElementById('tags'), {
    		copyClassesToDropdown: false,
    		dropdownParent: 'body',
    		controlInput: '<input>',
    		render:{
    			item: function(data,escape) {
    				if( data.customProperties ){
    					return '<div><span class=\"dropdown-item-indicator\">' + data.customProperties + '</span>' + escape(data.text) + '</div>';
    				}
    				return '<div>' + escape(data.text) + '</div>';
    			},
    			option: function(data,escape){
    				if( data.customProperties ){
    					return '<div><span class=\"dropdown-item-indicator\">' + data.customProperties + '</span>' + escape(data.text) + '</div>';
    				}
    				return '<div>' + escape(data.text) + '</div>';
    			},
    		},
    	}));
    });
	
	
	$(document).ready(function() {
		tinymce.remove();
				
		tinymce.init({
			selector: 'textarea#snow-container',
			height: 500,
			menubar: false,
			plugins: [
				'advlist autolink lists link charmap print preview anchor',
				'searchreplace visualblocks code fullscreen',
				'insertdatetime media table paste code wordcount'
			],
			toolbar: 'link | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat code ',
			content_css: [
				'//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
				'".WEBDOMAIN."/css/style.css'
			]
		});
	});";
$html[] = "</script>";