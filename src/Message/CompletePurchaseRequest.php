<?php

namespace Omnipay\Perfectmoney\Message;

use Omnipay\Common\Exception\InvalidResponseException;

class CompletePurchaseRequest extends AbstractRequest
{
    public function getData()
    {
        $theirHash = (string)$this->httpRequest->request->get('V2_HASH');
        $ourHash = $this->createResponseHash($this->httpRequest->request->all());

        if ($theirHash !== $ourHash) {
            throw new InvalidResponseException("Callback hash does not match expected value");
        }

        return $this->httpRequest->request->all();
    }

    public function sendData($data)
    {
        return $this->response = new CompletePurchaseResponse($this, $data);
    }

    public function createResponseHash($parameters)
    {
        $this->validate('password');

        return strtoupper(md5(implode(':', [
        	$parameters['PAYMENT_ID'],
			$parameters['PAYEE_ACCOUNT'],
			$parameters['PAYMENT_AMOUNT'],
			$parameters['PAYMENT_UNITS'],
			$parameters['PAYMENT_BATCH_NUM'],
			$parameters['PAYER_ACCOUNT'],
			strtoupper(md5($this->getPassword())),
			$parameters['TIMESTAMPGMT'],
		])));
    }
}
