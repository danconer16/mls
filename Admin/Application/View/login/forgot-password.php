<?php

$html[] = "<div class='d-flex flex-column'>";
	$html[] = "<div class='page page-center'>";
		$html[] = "<div class='container container-tight py-4'>";
			$html[] = "<div class='text-center mb-4 mt-5'>";
				$html[] = "<a href='".WEBDOMAIN."' class='navbar-brand'><span class='d-block fs-30 fw-bold'><i class='ti ti-building-skyscraper'></i> MLS</span></a>";
				$html[] = "<span class='d-block'><b>MLS Account Password Reset Link</b></span>";
			$html[] = "</div>";

			$html[] = "<input type='hidden' id='save_url' value='".url("AuthenticatorController@sendPasswordResetLink")."' />";
			$html[] = "<form id='form' class='card card-md' action='' method='POST' autocomplete='off'>";
				$html[] = "<div class='card-body'>";
					$html[] = "<h2 class='card-title text-center mb-4'>Forgot Password</h2>";
					$html[] = "<p class='text-secondary mb-4'>Enter your email address and your password reset link will be emailed to you.</p>";

					$html[] = "<div class='response mb-4'>".getMsg()."</div>";

					$html[] = "<div class='mb-3'>";
						$html[] = "<label class='form-label'><i class='ti ti-email'></i> Registered email address</label>";
						$html[] = "<input type='email' class='form-control' name='email'  placeholder='Enter registered email' autocomplete='off' role='presentation' required />";
					$html[] = "</div>";

					$html[] = "<div class='form-footer'>";
						$html[] = "<span class='btn btn-primary w-100 btn-save'><i class='ti ti-send'></i> &nbsp; Send Password Reset Link</span>";
					$html[] = "</div>";
				$html[] = "</div>";
			$html[] = "</form>";

			$html[] = "<div class='text-center text-secondary mt-3'>";
				$html[] = "Forget it, <a href='".url("/")."'>send me back</a> to the sign in screen.";
			$html[] = "</div>";

		$html[] = "</div>";
	$html[] = "</div>";
$html[] = "</div>";