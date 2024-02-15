<?php

$html[] = "<div class='page-header d-print-none text-white'>";
	$html[] = "<div class='container-xl'>";

		$html[] = "<div class='row g-2 '>";
			$html[] = "<div class='col'>";
				$html[] = "<div class='page-pretitle'>Multi-Listing Services System</div>";
				$html[] = "<h1 class='page-title'><i class='ti ti-building-estate me-2'></i> MLS System - Compare Table</h1>";
			$html[] = "</div>";

			$html[] = "<div class='col-auto ms-auto d-print-none'>";
				$html[] = "<div class='d-sm-block'>";
					$html[] = "<div class='btn-list'>";
						
						$html[] = "<a class='ajax btn btn-dark' href='".url("MlsController@handshakedIndex")."'><i class='ti ti-heart-handshake me-2'></i> Handshaked</a>";

						$html[] = "<div class='btn-group'>";
							$html[] = "<span class='btn btn-dark filter-btn dropdown-toggle' data-bs-toggle='dropdown'><i class='ti ti-filter me-2'></i> Filter Columns</span>";
							$html[] = "<ul class='dropdown-menu'>";
								$html[] = "<li class='dropdown-item pb-0'><div class='form-check pb-0 mb-0'><input type='checkbox' value='' class='form-check-input col-filter' checked id='col-avatar' /><label class='form-check-label' for='col-avatar'>Image</label></div></li>";
								$html[] = "<li class='dropdown-item pb-0'><div class='form-check pb-0 mb-0'><input type='checkbox' value='' class='form-check-input col-filter' checked id='col-foreclosed' /><label class='form-check-label' for='col-foreclosed'>Forclosure</label></div></li>";
								$html[] = "<li class='dropdown-item pb-0'><div class='form-check pb-0 mb-0'><input type='checkbox' value='' class='form-check-input col-filter' checked id='col-category' /><label class='form-check-label' for='col-category'>Category</label></div></li>";
								$html[] = "<li class='dropdown-item pb-0'><div class='form-check pb-0 mb-0'><input type='checkbox' value='' class='form-check-input col-filter' checked id='col-lot_area' /><label class='form-check-label' for='col-lot_area'>Land Area</label></div></li>";
								$html[] = "<li class='dropdown-item pb-0'><div class='form-check pb-0 mb-0'><input type='checkbox' value='' class='form-check-input col-filter' checked id='col-floor_area' /><label class='form-check-label' for='col-floor_area'>Floor Area</label></div></li>";
								$html[] = "<li class='dropdown-item pb-0'><div class='form-check pb-0 mb-0'><input type='checkbox' value='' class='form-check-input col-filter' checked id='col-bedroom' /><label class='form-check-label' for='col-bedroom'>Bedroom</label></div></li>";
								$html[] = "<li class='dropdown-item pb-0'><div class='form-check pb-0 mb-0'><input type='checkbox' value='' class='form-check-input col-filter' checked id='col-bathroom' /><label class='form-check-label' for='col-bathroom'>Bathroom</label></div></li>";
								$html[] = "<li class='dropdown-item pb-0'><div class='form-check pb-0 mb-0'><input type='checkbox' value='' class='form-check-input col-filter' checked id='col-parking' /><label class='form-check-label' for='col-parking'>Car Garage</label></div></li>";
								$html[] = "<li class='dropdown-item pb-0'><div class='form-check pb-0 mb-0'><input type='checkbox' value='' class='form-check-input col-filter' checked id='col-address' /><label class='form-check-label' for='col-address'>Address</label></div></li>";
								$html[] = "<li class='dropdown-item pb-0'><div class='form-check pb-0 mb-0'><input type='checkbox' value='' class='form-check-input col-filter' checked id='col-price' /><label class='form-check-label' for='col-price'>Price</label></div></li>";
							$html[] = "</ul>";
						$html[] = "</div>";

						$html[] = "<a class='ajax btn btn-dark' href='".url("MlsController@handshakedIndex")."'><i class='ti ti-heart-handshake me-2'></i> Handshaked</a>";
						
					$html[] = "</div>";
				$html[] = "</div>";
			$html[] = "</div>";
		$html[] = "</div>";

	$html[] = "</div>";
$html[] = "</div>";

$html[] = "<div class='page-body'>";
	$html[] = "<div class='container-xl'>";

		$html[] = "<div class='response'>";
			$html[] = getMsg();
		$html[] = "</div>";

		$columns = explode(",","listing_id,offer,category,lot_area,floor_area,bedroom,bathroom,parking,address,price");

		$html[] = "<h1 class='d-none d-print-block'>MLS System - Comparative Analysis Table</h1>";
		$html[] = "<div class='card'>";
			$html[] = "<div class='table-responsive'>";
				$html[] = "<table class='table table-vcenter table-bordered table-nowrap card-table caption-top'>";
				$html[] = "<thead>";
					$html[] = "<tr>";
						$html[] = "<td class='text-center col-avatar' style='width:150px;'>Image</td>";
						for($i=0; $i<count($data); $i++) {
							$html[] = "<td class='text-center col-avatar'>";
								$html[] = "<div class='avatar avatar-xl' style='background-image: url(".$data[$i]['thumb_img'].")'></div>";
							$html[] = "</td>";
						}
					$html[] = "</tr>";
				$html[] = "</thead>";

				$html[] = "<tbody>";

					foreach($columns as $col) {
						$html[] = "<tr>";
							$html[] = "<td class='text-center col-$col'>".ucwords(str_replace("_"," ",$col))."</td>";

							for($x=0; $x<count($data); $x++) {
								
								switch($col) {
									case 'address':
										$html[] = "<td class='text-center text-wrap col-$col' style='width:150px;'>".$data[$x]["address"]['municipality']." ".$data[$x]["address"]['province']."</td>";
										break;
									
									case 'price':
										$html[] = "<td class='text-center text-wrap col-$col' style='width:150px;'>&#8369;".number_format($data[$x]['price'],0)."</td>";
										break;

									default:
										$html[] = "<td class='text-center text-wrap col-$col' style='width:150px;'>".$data[$x][$col]."</td>";
								}

							}

						$html[] = "<tr>";
					}

				$html[] = "</tbody>";
				$html[] = "</table>";
			$html[] = "</div>";
		$html[] = "</div>";

	$html[] = "</div>";
$html[] = "</div>";