<?php 

require_once 'database/connection.php';
require_once 'function/WebApi.php';
use Api\WebApi\RequestHttp as Request;
use Api\WebApi\RequestHttp\Filter as Filter;

$app = new Request;

//Filter::getEmpty('type');
Filter::getEmpty('uid');
//Filter::getEmpty('api_key');
Filter::getEmpty('halaman');

$app->requestType(Filter::get('type'), Filter::get('uid'), Filter::get('halaman'), Filter::get('api_key'));



	
