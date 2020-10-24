<?php
/**
 * @version        2.2.1
 * @package        Joomla
 * @subpackage     Membership Pro
 * @author         Tuan Pham Ngoc
 * @copyright      Copyright (C) 2012 - 2016 Ossolution Team
 * @license        GNU/GPL, see LICENSE.php
 */
// no direct access
defined('_JEXEC') or die;

class os_pagseguro extends MPFPayment
{
	
	private $PagSeguroVersion='6.0.0';
	private $merchant = null;
	private $token = null;
	#public function os_pagseguro($params, $config = array())
	#{
		#parent::__construct($params, $config);

		#$this->merchant = $params->get('merchant');
		#$this->token    = $params->get('token');	
		
		#require_once "/home/technofa/public_html/components/com_osmembership/plugins/pagseguro.6.0.0/vendor/autoload.php";
	#}

	public function processPayment($row, $data)
	{
		require_once "/home/technofa/public_html/components/com_osmembership/plugins/pagseguro.6.0.0/vendor/autoload.php";
	
		#require_once "./pagseguro.6.0.0/vendor/autoload.php";
		
		#echo dirname(__FILE__);
		
		
		
		\PagSeguro\Library::initialize();
		\PagSeguro\Library::cmsVersion()->setName("Nome")->setRelease("1.0.0");
		\PagSeguro\Library::moduleVersion()->setName("Nome")->setRelease("1.0.0");

		$payment = new \PagSeguro\Domains\Requests\Payment();
	
		
		$app     = JFactory::getApplication();
		$Itemid  = $app->input->getInt('Itemid', 0);
		$siteUrl = JUri::base();

		$payment->addItems()->withParameters(
			$row->id,
			utf8_decode($data['item_name']),
			1,
			sprintf('%02.2f', round($data['amount'], 2))
			);

		$payment->setCurrency("BRL");

		$payment->setReference($row->id);

		$payment->setRedirectUrl('http://www.plaas.com.br/index.php?option=com_osmembership&view=complete&Itemid=' . $Itemid);

		// Set your customer information.
		$payment->setSender()->setName(rtrim($row->first_name . ' ' . $row->last_name));
		$payment->setSender()->setEmail($row->email);


		//Add items by parameter using an array
		#$payment->addParameter()->withArray(['notificationURL', 'http://www.plaas.com.br/components/com_osmembership/plugins/returninfo.php?option=com_osmembership&task=payment_confirm&payment_method=os_pagseguro']);	
		#$payment->addParameter()->withArray(['notificationURL', $siteUrl . 'index.php?option=com_osmembership&task=payment_confirm&payment_method=os_pagseguro']);
		#$payment->setNotificationUrl($siteUrl . 'index.php?option=com_osmembership&task=payment_confirm&payment_method=os_pagseguro');
		#$payment->setNotificationUrl('http://www.plaas.com.br/components/com_osmembership/plugins/returninfo.php?option=com_osmembership&task=payment_confirm&payment_method=os_pagseguro');

		// Add a group and/or payment methods name
		$payment->acceptPaymentMethod()->groups(
			\PagSeguro\Enum\PaymentMethod\Group::CREDIT_CARD,
			\PagSeguro\Enum\PaymentMethod\Group::BALANCE
		);
		$payment->acceptPaymentMethod()->name(\PagSeguro\Enum\PaymentMethod\Name::DEBITO_ITAU);
		$payment->acceptPaymentMethod()->name(\PagSeguro\Enum\PaymentMethod\Name::DEBITO_BRADESCO);
		$payment->acceptPaymentMethod()->name(\PagSeguro\Enum\PaymentMethod\Name::DEBITO_BANCO_BRASIL);
		
		// Remove a group and/or payment methods name
		$payment->excludePaymentMethod()->group(\PagSeguro\Enum\PaymentMethod\Group::BOLETO);


		try {
			$result = $payment->register(
				\PagSeguro\Configuration\Configure::getAccountCredentials()
			);
			#echo "<h2>Criando requisi&ccedil;&atilde;o de pagamento</h2>"
			#		. "<p>URL do pagamento: <strong>$result</strong></p>"
			#		. "<p><a title=\"URL do pagamento\" href=\"$result\" target=\_blank\">Ir para URL do pagamento.</a></p>";
		
			$parts = parse_url($result);
			parse_str($parts['query'], $query);
			$paymentcode=$query['code'];
		
			header("Location: $result");
		
			?>
			
			<!-- html>
			<head>
				<script type="text/javascript"
					src="https://stc.pagseguro.uol.com.br/pagseguro/api/v2/checkout/pagseguro.lightbox.js">
				</script>
			</head>
			<body>
				<script>PagSeguroLightbox('<? #echo $paymentcode;?>');</script>
			</body>
			</html -->
		
			
			<?php
		
		
		
		
		} catch (Exception $e) {
				die($e->getMessage());
		}
	}

	protected function validate()
	{
		
		$this->notificationData = $_POST;
		$this->logGatewayData();
		$isValid = true;

		// Get incoming data
		$type = array_key_exists('notificationType', $this->notificationData) ? $this->notificationData['notificationType'] : 'INVALID';
		$code = array_key_exists('notificationCode', $this->notificationData) ? $this->notificationData['notificationCode'] : '';

		// Is it a valid notifiaction type (only "transaction" is supposed to be sent)
		if ($type != 'transaction')
		{
			$isValid = false;
		}

		// Is the notification code non-empty?
		if (empty($code))
		{
			$isValid = false;
		}

		// Get the transaction data
		if ($isValid)
		{
			try {
				$response = \PagSeguro\Services\Application\Search\Code::search(
				        $auth,
						$code
						);
			} catch (Exception $e) {
				die($e->getMessage());
			}
		}
		// Load the relevant subscription row and make sure it's valid
		if ($isValid)
		{
			// Get the ID
			$id         = $response->getReference();
			$status     = $response->getStatus();
			$statusType = $status->getTypeFromValue();
			if ($statusType == 'AVAILABLE' || $statusType == 'PAID')
			{

			}
			else
			{
				$isValid = false;
			}
		}

		// Check the payment_status

		$this->logGatewayData();
		if (!$isValid)
		{
			return false;
		}
		else
		{
			$this->notificationData['id']             = $id;
			$this->notificationData['gross_amount']   = $transaction->getGrossAmount();
			$this->notificationData['transaction_id'] = $transaction->getCode();

			return true;
		}
		
	}

	public function verifyPayment()
	{
		
		$ret = $this->validate();
		if ($ret)
		{
			$id            = $this->notificationData['id'];
			$transactionId = $this->notificationData['transaction_id'];
			$amount        = $this->notificationData['gross_amount'];
			if ($amount < 0)
			{
				return false;
			}
			$row = JTable::getInstance('OsMembership', 'Subscriber');
			$row->load($id);
			if (!$row->id)
			{
				return false;
			}
			if ($row->published)
			{
				return false;
			}

			$this->onPaymentSuccess($row, $transactionId);
		}

		return false;
		
	}
	
}

?>

