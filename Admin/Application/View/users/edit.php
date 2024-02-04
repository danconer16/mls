<?php

$html[] = "<input type='hidden' id='save_url' value='".url("UsersController@saveUpdate",["id" => $data['account_id'], "user_id" => $data['user_id']])."' />";

$html[] = "<form id='form' action='' method='POST'>";

	$html[] = "<div class='row g-0 justify-content-center mb-5 pb-5'>";
		$html[] = "<div class='col-lg-6 col-md-6 col-12'>";

			$html[] = "<div class='page-header d-print-none text-white'>";
				$html[] = "<div class='container-xl'>";

					$html[] = "<div class='row g-2 '>";
						$html[] = "<div class='col'>";
							$html[] = "<h1 class='page-title'><i class='ti ti-users me-2'></i> Update User Info</h1>";
						$html[] = "</div>";
						$html[] = "<div class='col-auto ms-auto d-print-none'>";
							if($_SESSION['account_type'] == "Administrator") {
								$html[] = "<div class='btn-list text-end'>";
									$html[] = "<a class='ajax btn btn-light' href='".url("AccountsController@view", ["id" => $data['account_id']])."'>";
										$html[] = "<span class='avatar avatar-sm' style='background-image: url(".$data['logo'].")'></span>";
										$html[] = $data['firstname']." ".$data['lastname']." account";
									$html[] = "</a>";
								$html[] = "</div>";
							}
						$html[] = "</div>";
					$html[] = "</div>";

				$html[] = "</div>";
			$html[] = "</div>";

			$html[] = "<div class='page-body'>";
				$html[] = "<div class='container-xl'>";

					$html[] = "<div class='response'>";
						$html[] = getMsg();
					$html[] = "</div>";

				
					$html[] = "<div class='card mb-3'>";
						$html[] = "<div class='card-status bg-orange'></div>";
						$html[] = "<div class='card-header'>";
							$html[] = "<h3 class='card-title text-blue'><i class='ti ti-user me-2'></i> User Info</h3>";
						$html[] = "</div>";
						
						$html[] = "<div class='card-body'>";

							$html[] = "<div class='mb-3'>";
								$html[] = "<div class='row'>";
									$html[] = "<div class='col-3'>";
										$html[] = "<label class='text-muted form-label mt-2 text-end'>Name</label>";
									$html[] = "</div>";
									$html[] = "<div class='col-9'>";
										$html[] = "<input type='text' name='name' id='name' value='".$data['name']."' class='form-control' autocomplete='off' style='font-size:16px;' />";
									$html[] = "</div>";
								$html[] = "</div>";
							$html[] = "</div>";

							$html[] = "<div class='mb-3'>";
								$html[] = "<div class='row'>";
									$html[] = "<div class='col-3'>";
										$html[] = "<label class='text-muted form-label mt-2 text-end'>Email</label>";
									$html[] = "</div>";
									$html[] = "<div class='col-9'>";
										$html[] = "<p class='form-control-plaintext'>".$data['email']."</p>";
									$html[] = "</div>";
								$html[] = "</div>";
							$html[] = "</div>";
							
						$html[] = "</div>";
					$html[] = "</div>";

					if($_SESSION['user_id'] != $data['user_id'] || $_SESSION['account_type'] == "Administrator") {
						$html[] = "<div class='card mb-3'>";
							$html[] = "<div class='card-status bg-green'></div>";
							$html[] = "<div class='card-header'>";
								$html[] = "<h3 class='card-title text-blue'><i class='ti ti-key me-2'></i> Change Password</h3>";
							$html[] = "</div>";
						
							$html[] = "<div class='card-body'>";
								$html[] = "<table class='table'>";
								$html[] = "<tr><td class='pt-0 border-top-0 align-middle' colspan='2' ><i>If you don't want to change your password leave blank.</i></td></tr>";
								
								$html[] = "<tr>";
									$html[] = "<td class='text-end border-top-0 align-middle' style='width:180px;'><label class='text-muted form-label'>New Password &nbsp; :</label></td>";
									$html[] = "<td class='border-top-0 align-middle' style='font-size:16px;'><input type='password' name='password' id='password' value='' class='form-control' /></td>";
								$html[] = "</tr>";
								
								$html[] = "<tr>";
									$html[] = "<td class='text-end border-top-0 align-middle'><label class='text-muted form-label'>Confirm Password &nbsp; :</label></td>";
									$html[] = "<td class='border-top-0 align-middle' style='font-size:16px;'><input type='password' name='cpassword' id='cpassword' value='' class='form-control' /></td>";
								$html[] = "</tr>";
								
								$html[] = "</table>";
								
							$html[] = "</div>";
						$html[] = "</div>";
					}

					if(($_SESSION['user_level'] == 1 && $data['user_level'] != 1) || $_SESSION['account_type'] == "Administrator") {
						$html[] = "<div class='card mb-3'>";
							$html[] = "<div class='card-header'>";
								$html[] = "<h3 class='card-title text-blue'><i class='ti ti-settings me-2'></i> User Permissions</h3>";
							$html[] = "</div>";
							
							$html[] = "<div class='card-body'>";
								
								$html[] = "<ul class='list-group list-group-flush'>";
								foreach(USER_PERMISSIONS as $app => $arr) {
									$html[] = "<li class='list-group-item'>";
									$html[] = "<h3>".ucwords(str_replace("_"," ",$app))."</h3>";
									foreach($arr as $access => $val) {
										$html[] = "<div class='form-check form-switch'>";
											$html[] = "<input class='form-check-input' type='checkbox' name='permissions[$app][$access]' value='true' id='flexSwitchCheckDefault_".$app."_".$access."' ".(isset($data['permissions'][$app][$access]) && $data['permissions'][$app][$access] == "true" ? "checked" : "").">";
											$html[] = "<label class='form-check-label' for='flexSwitchCheckDefault_".$app."_".$access."'>".(isset(DEFINITION[$app][$access]) ? DEFINITION[$app][$access] : $access)."</label>";
										$html[] = "</div>";
									}
									$html[] = "</li>";
								}
								$html[] = "</ul>";

							$html[] = "</div>";
						$html[] = "</div>";
					}

				$html[] = "</div>";
			$html[] = "</div>";
			
		$html[] = "</div>";
	$html[] = "</div>";

	$html[] = "<div class='btn-save-container fixed-bottom bg-white py-3 border-top'>";
		$html[] = "<div class='row g-0 justify-content-center'>";
			$html[] = "<div class='col-lg-6 col-md-6 col-sm-12 col-12'>";

				$html[] = "<div class='container-xl'>";
					$html[] = "<div class='text-end'>";
						$html[] = "<span class='btn btn-outline-primary btn-save'><i class='ti ti-device-floppy me-2'></i> Save User Details</span>";
					$html[] = "</div>";
				$html[] = "</div>";

			$html[] = "</div>";
		$html[] = "</div>";
	$html[] = "</div>";

$html[] = "</form>";
