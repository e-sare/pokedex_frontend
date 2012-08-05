<?php

//require_once ('Zend/Http/Client.php');

class PokedexController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        // action body
    }

    public function pokemonAction()
    {
        // action body
        $name = $this->_request->getParam('pokemon');

        // $moves_api = "localhost:8080/pokemon/moves";
        //$request = http_get($moves_api, array('timeout'=>1), $info);

        //$this->view->moves = json_decode($request);
        $url = 'http://localhost:8080/pokedex/moves';
        /*
        $http = new Zend_Rest_Client($url);
        $response = $http->get();

        if($response->isSuccessful()){
        	echo $reponse->getBody();
        }else{
        	echo 'response failed!!!';
        }
        */

        $client = new Zend_Http_Client($url);
		//$this->view->response = $client->request();
		$response = $client->request();
		$moves = Zend_Json::decode($response->getBody());

		var_dump($moves);
		//die(var_dump($response));

/*
        try {
	        $http = new Zend_Http_Client($url);
	        $response = $http->get();
	        if ($response->isSuccessful()) {
            echo $response->getBody();
        } else {
            echo '<p>An error occurred</p>';
	        }
	    } catch (Zend_Http_Client_Exception $e) {
	        echo '<p>An error occurred (' .$e->getMessage(). ')</p>';
    }
*/


    }


}

