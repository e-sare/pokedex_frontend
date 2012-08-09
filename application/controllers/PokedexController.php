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

    public function browseAction()
    {

        // action body
    }

    public function comparisonAction()
    {

        // action body
    }

    public function mapsAction()
    {

        // action body
    }

    public function pokemonAction()
    {
        // action body
        echo "<pre>";
        print_r($this->_request->getParams('pokemon'));
        echo "</pre>";
        $param_name = $this->_request->getParam('pokemon');

        var_dump($param_name);


        define('POKEDEX_API','http://localhost:8080/pokedex/');
        define('POKEMON_API', POKEDEX_API . 'pokemon/');
        define('POKEMON_IMAGE_DIR','/img/sugimori/');
        //define(POKEMON_MOVES_API, POKEDEX_API . 'moves/');

        $url = POKEMON_API . $param_name;
        // $moves_api = "localhost:8080/pokemon/moves";
        //$request = http_get($moves_api, array('timeout'=>1), $info);

        //$this->view->moves = json_decode($request);
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
		$pkm = Zend_Json::decode($response->getBody());

        $this->view->pkm = $pkm;
        $this->view->POKEMON_IMAGE_DIR = POKEMON_IMAGE_DIR;

        

        //$pkm = new Pokemon($pkm_json);

        //$this->view->pkm = $pkm;

        echo "<h2>name is:</h2>" . $pkm['metadata']['name'];



        echo "<h2>Metadata</h2> <pre> ";
        var_dump($pkm['metadata']);
        echo "</pre>";

        echo "<h2>Full PKM JSON</h2> <pre>";
        var_dump($pkm);
        echo "</pre>";


        /*
        $pkm = new Pokemon($json);

        $this->view->pkm = $pkm;



        $this->pkm->name;

        $this->view->moves = $moves;
        */

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

