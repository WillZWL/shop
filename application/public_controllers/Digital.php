<?php
defined('BASEPATH') OR exit('No direct script access allowed');

DEFINE ('ALLOW_REDIRECT_DOMAIN', 1);

class Digital extends PUB_Controller
{
	public function Digital()
	{
		parent::PUB_Controller();
		$this->load->library('service/affiliate_service');
	}

#	SBF #4444 - TradeTracker Redirect
	public function index($country="BE")
	{

		// session_start();

		$subdomain = (substr($_SERVER['HTTP_HOST'], 0, 3));	 # for easier debugging in dev
		$afid = "TT".$country;
		switch (strtoupper($country))
		{
			case "BE":
				// $defaultUrl = "http://$subdomain.valuebasket.be/fr_BE";
				$domainName = "valuebasket.be";
				break;

			default:
				$domainName = "valuebasket.com";
				break;
		}

		// Define parameters.
		$canRedirect = true;

		// Set parameters.
		if (isset($_GET['campaignID']))
		{
			$campaignID = $_GET['campaignID'];
			$materialID = isset($_GET['materialID']) ? $_GET['materialID'] : '';
			$affiliateID = isset($_GET['affiliateID']) ? $_GET['affiliateID'] : '';
			$redirectURL = isset($_GET['redirectURL']) ? $_GET['redirectURL'] : '';
			$reference = '';
		}
		else if (isset($_GET['tt']))
		{
			$trackingData = explode('_', $_GET['tt']);

			$campaignID = isset($trackingData[0]) ? $trackingData[0] : '';
			$materialID = isset($trackingData[1]) ? $trackingData[1] : '';
			$affiliateID = isset($trackingData[2]) ? $trackingData[2] : '';
			$reference = isset($trackingData[3]) ? $trackingData[3] : '';

			$redirectURL = isset($_GET['r']) ? $_GET['r'] : '';
		}
		else
			$canRedirect = false;

		if ($canRedirect)
		{
			// Calculate MD5 checksum.
			$checkSum = md5('CHK_' . $campaignID . '::' . $materialID . '::' . $affiliateID . '::' . $reference);

			// Set session/cookie arguments.
			$cookieName = 'TT2_' . $campaignID;
			$cookieValue = $materialID . '::' . $affiliateID . '::' . $reference . '::' . $checkSum . '::' . time();

			// Create tracking cookie.
			setcookie($cookieName, $cookieValue, (time() + 31536000), '/', !empty($domainName) ? '.' . $domainName : null);

			// create VB own affiliate tracking
			setcookie("AF", $afid);

			// Create tracking session.
			session_start();

			// Set session data.
			$_SESSION[$cookieName] = $cookieValue;

			// Set track-back URL.
			$trackBackURL = 'http://tc.tradetracker.net/?c=' . $campaignID . '&m=' . $materialID . '&a=' . $affiliateID . '&r=' . urlencode($reference) . '&u=' . urlencode($redirectURL);

			// Redirect to TradeTracker.
			header('Location: ' . $trackBackURL, true, 301);
		}

	}
}

