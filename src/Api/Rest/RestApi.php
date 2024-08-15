<?php

namespace Infusionsoft\Api\Rest;

use Infusionsoft\InfusionSoft;

abstract class RestApi {

	public function __construct(InfusionSoft $client)
	{
		$this->client = $client;
	}

}