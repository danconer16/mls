<?php

$html[] = "<div class='response'>";
	$html[] = "<div class='container-xl'>";
		$html[] = getMsg();
	$html[] = "</div>";
$html[] = "</div>";

$html[] = "<div class='page-header d-print-none text-white'>";
	$html[] = "<div class='container-xl'>";
		$html[] = "<div class='row g-2 '>";
			$html[] = "<div class='col'>";
				$html[] = "<div class='page-pretitle'>Account Overview</div>";
				$html[] = "<h1 class='page-title'><i class='ti ti-layout-dashboard me-2'></i> Dashboard</h1>";
			$html[] = "</div>";
			$html[] = "<div class='col-auto ms-auto d-print-none'>";
				$html[] = "<div class='btn-list text-end'>";
					
				$html[] = "</div>";
			$html[] = "</div>";
		$html[] = "</div>";
	$html[] = "</div>";
$html[] = "</div>";
$html[] = "<div class='page-body'>";
	$html[] = "<div class='container-xl'>";
		
		$html[] = "<div class='row row-deck row-cards'>";

			$html[] = "<div class='col-12'>";
				$html[] = "<div class='row row-cards'>";

					$html[] = "<div class='col-sm-6 col-lg-3'>";
						$html[] = "<div class='card card-sm'>";
							$html[] = "<div class='card-body'>";
								$html[] = "<div class='row align-items-center'>";
									$html[] = "<div class='col-auto'>";
										$html[] = "<span class='bg-primary text-white avatar'><i class='ti ti-article'></i></span>";
									$html[] = "</div>";
									$html[] = "<div class='col'>";
										$html[] = "<div class='font-weight-medium'>58 Posted Listings</div>";
										$html[] = "<div class='text-secondary'>2 more left</div>";
									$html[] = "</div>";
								$html[] = "</div>";
							$html[] = "</div>";
						$html[] = "</div>";
					$html[] = "</div>";

					$html[] = "<div class='col-sm-6 col-lg-3'>";
						$html[] = "<div class='card card-sm'>";
							$html[] = "<div class='card-body'>";
								$html[] = "<div class='row align-items-center'>";
									$html[] = "<div class='col-auto'>";
										$html[] = "<span class='bg-teal text-white avatar'><i class='ti ti-users'></i></span>";
									$html[] = "</div>";
									$html[] = "<div class='col'>";
										$html[] = "<div class='font-weight-medium'>285 Leads</div>";
										$html[] = "<div class='text-secondary'>Generated by MLS Website</div>";
									$html[] = "</div>";
								$html[] = "</div>";
							$html[] = "</div>";
						$html[] = "</div>";
					$html[] = "</div>";

					$html[] = "<div class='col-sm-6 col-lg-3'>";
						$html[] = "<div class='card card-sm'>";
							$html[] = "<div class='card-body'>";
								$html[] = "<div class='row align-items-center'>";
									$html[] = "<div class='col-auto'>";
										$html[] = "<span class='bg-green text-white avatar'><i class='ti ti-layers-union'></i></span>";
									$html[] = "</div>";
									$html[] = "<div class='col'>";
										$html[] = "<div class='font-weight-medium'>1 Active Premium</div>";
										$html[] = "<div class='text-secondary'>Platinum Package 365 days left</div>";
									$html[] = "</div>";
								$html[] = "</div>";
							$html[] = "</div>";
						$html[] = "</div>";
					$html[] = "</div>";

					$html[] = "<div class='col-sm-6 col-lg-3'>";
						$html[] = "<div class='card card-sm'>";
							$html[] = "<div class='card-body'>";
								$html[] = "<div class='row align-items-center'>";
									$html[] = "<div class='col-auto'>";
										$html[] = "<span class='bg-red text-white avatar'><i class='ti ti-heart'></i></span>";
									$html[] = "</div>";
									$html[] = "<div class='col'>";
										$html[] = "<div class='font-weight-medium'>2 Active Handshaked</div>";
										/* $html[] = "<div class='text-secondary'>Platinum Package 365 days left</div>"; */
										
										$html[] = "<div class='d-flex gap-3'>";
											$html[] = "<div class='avatar-list avatar-list-stacked'>";
												$html[] = "<span class='avatar avatar-xs rounded' style='background-image: url(".$_SESSION['user_logged']['logo'].")'></span>";
												$html[] = "<span class='avatar avatar-xs rounded'>JL</span>";
											$html[] = "</div>";

											$html[] = "<div class='avatar-list avatar-list-stacked'>";
												$html[] = "<span class='avatar avatar-xs rounded'>EO</span>";
												$html[] = "<span class='avatar avatar-xs rounded' style='background-image: url(".$_SESSION['user_logged']['photo'].")'></span>";
											$html[] = "</div>";
										$html[] = "</div>";

									$html[] = "</div>";
								$html[] = "</div>";
							$html[] = "</div>";
						$html[] = "</div>";
					$html[] = "</div>";

				$html[] = "</div>";
			$html[] = "</div>";

			$html[] = "<div class='col-6'>";
				$html[] = "<div class='card mb-3'>";
					$html[] = "<div class='card-body'>";
						$html[] = "<h3 class='card-title'>Traffic summary</h3>";
						$html[] = "<div id='chart-mentions' class='chart-lg'></div>";
					$html[] = "</div>";
				$html[] = "</div>";
			$html[] = "</div>";

			$html[] = "<div class='col-6'>";
				$html[] = "<div class='card mb-3'>";
					$html[] = "<div class='card-header'>";
						$html[] = "<h3 class='card-title'>Most Visited Pages</h3>";
					$html[] = "</div>";
					
					$html[] = '<div class="card-table table-responsive">
                    <table class="table table-vcenter">
                      <thead>
                        <tr>
                          <th>Post name</th>
                          <th>Visitors</th>
                          <th>Unique</th>
                          <th colspan="2">Bounce rate</th>
                        </tr>
                      </thead>
                      <tr>
                        <td>
                          Modern 2 storey 5 bedrooms Alabang 400 Village, Muntinlupa City
                          <a href="#" class="ms-1" aria-label="Open website"><!-- Download SVG icon from http://tabler-icons.io/i/link -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 15l6 -6" /><path d="M11 6l.463 -.536a5 5 0 0 1 7.071 7.072l-.534 .464" /><path d="M13 18l-.397 .534a5.068 5.068 0 0 1 -7.127 0a4.972 4.972 0 0 1 0 -7.071l.524 -.463" /></svg>
                          </a>
                        </td>
                        <td class="text-secondary">4,896</td>
                        <td class="text-secondary">3,654</td>
                        <td class="text-secondary">82.54%</td>
                      </tr>
                      <tr>
                        <td>
                          Modern 2 storey 5 bedrooms Alabang 400 Village, Muntinlupa City
                          <a href="#" class="ms-1" aria-label="Open website"><!-- Download SVG icon from http://tabler-icons.io/i/link -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 15l6 -6" /><path d="M11 6l.463 -.536a5 5 0 0 1 7.071 7.072l-.534 .464" /><path d="M13 18l-.397 .534a5.068 5.068 0 0 1 -7.127 0a4.972 4.972 0 0 1 0 -7.071l.524 -.463" /></svg>
                          </a>
                        </td>
                        <td class="text-secondary">3,652</td>
                        <td class="text-secondary">3,215</td>
                        <td class="text-secondary">76.29%</td>
                      </tr>
                      <tr>
                        <td>
                          Modern 2 storey 5 bedrooms Alabang 400 Village, Muntinlupa City
                          <a href="#" class="ms-1" aria-label="Open website"><!-- Download SVG icon from http://tabler-icons.io/i/link -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 15l6 -6" /><path d="M11 6l.463 -.536a5 5 0 0 1 7.071 7.072l-.534 .464" /><path d="M13 18l-.397 .534a5.068 5.068 0 0 1 -7.127 0a4.972 4.972 0 0 1 0 -7.071l.524 -.463" /></svg>
                          </a>
                        </td>
                        <td class="text-secondary">3,256</td>
                        <td class="text-secondary">2,865</td>
                        <td class="text-secondary">72.65%</td>
                      </tr>
                      <tr>
                        <td>
                          Modern 2 storey 5 bedrooms Alabang 400 Village, Muntinlupa City
                          <a href="#" class="ms-1" aria-label="Open website"><!-- Download SVG icon from http://tabler-icons.io/i/link -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 15l6 -6" /><path d="M11 6l.463 -.536a5 5 0 0 1 7.071 7.072l-.534 .464" /><path d="M13 18l-.397 .534a5.068 5.068 0 0 1 -7.127 0a4.972 4.972 0 0 1 0 -7.071l.524 -.463" /></svg>
                          </a>
                        </td>
                        <td class="text-secondary">986</td>
                        <td class="text-secondary">865</td>
                        <td class="text-secondary">44.89%</td>
                      </tr>
                      <tr>
                        <td>
                          Modern 2 storey 5 bedrooms Alabang 400 Village, Muntinlupa City
                          <a href="#" class="ms-1" aria-label="Open website"><!-- Download SVG icon from http://tabler-icons.io/i/link -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 15l6 -6" /><path d="M11 6l.463 -.536a5 5 0 0 1 7.071 7.072l-.534 .464" /><path d="M13 18l-.397 .534a5.068 5.068 0 0 1 -7.127 0a4.972 4.972 0 0 1 0 -7.071l.524 -.463" /></svg>
                          </a>
                        </td>
                        <td class="text-secondary">912</td>
                        <td class="text-secondary">822</td>
                        <td class="text-secondary">41.12%</td>
                      </tr>
                      <tr>
                        <td>
                          Modern 2 storey 5 bedrooms Alabang 400 Village, Muntinlupa City
                          <a href="#" class="ms-1" aria-label="Open website"><!-- Download SVG icon from http://tabler-icons.io/i/link -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 15l6 -6" /><path d="M11 6l.463 -.536a5 5 0 0 1 7.071 7.072l-.534 .464" /><path d="M13 18l-.397 .534a5.068 5.068 0 0 1 -7.127 0a4.972 4.972 0 0 1 0 -7.071l.524 -.463" /></svg>
                          </a>
                        </td>
                        <td class="text-secondary">855</td>
                        <td class="text-secondary">798</td>
                        <td class="text-secondary">32.65%</td>
                      </tr>
                    </table>
                  </div>';
				$html[] = "</div>";
			$html[] = "</div>";

		$html[] = "</div>";


    $html[] = "</div>";
$html[] = "</div>";
    