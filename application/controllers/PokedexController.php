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
        //$this->view->appendFile('https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js')->appendFile('/js/bootstrap.js')->appendFile('/js/bootstrap-tab.js')->appendFile('/js/pokedex.js');


        // action body
        echo "<pre>";
        print_r($this->_request->getParams('pokemon'));
        echo "</pre>";

        define('POKEDEX_BASE','http://localhost:8080/pokedex/');
        define('POKEMON_BY_NAME', POKEDEX_BASE . 'pokemon/');
        define('POKEMON_BY_RANGE', POKEMON_BY_NAME . 'all/');
        define('POKEMON_BY_ID', POKEDEX_BASE . 'national_Id/');
        define('POKEMON_EVO_CHAIN', POKEDEX_BASE . 'evolutions/pokemon/');


        define('DEFAULT_GEN','generation/5');
        define('POKEMON_IMAGE_DIR','/img/sugimori/');


        $pkm_name = $this->_request->getParam('pokemon');



        //define(POKEMON_MOVES_API, POKEDEX_API . 'moves/');


        $url = POKEMON_BY_NAME . $pkm_name . '/' . DEFAULT_GEN;
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
		$response = $client->request();
        $pkm = Zend_Json::decode($response->getBody());


        $previous_pkm = '';
        $next_pkm  = '';


        //die(var_dump($url));


        // Get JSON for previous & next Pokemon for top navigation
        if($pkm['metadata']['nationalId'] >= 2 && $pkm ['metadata']['nationalId'] <=648){

            // get previous & next pokemon if current pkm id is between
            $url = POKEMON_BY_RANGE . ($pkm['metadata']['nationalId'] - 1) . '/to/' . ($pkm['metadata']['nationalId'] + 1) . '/5';

            $client->setUri($url);
            $response = $client->request();
            $before_after_pkm = Zend_Json::decode($response->getBody());
            $previous_pkm = $before_after_pkm[0]['metadata'];
            $next_pkm = $before_after_pkm[2]['metadata'];
        }
        elseif($pkm['metadata']['nationalId'] == 1){

            echo "<h1> {$pkm['metadata']['nationalId']}</h1>";

            $url = POKEMON_BY_RANGE . ($pkm['metadata']['nationalId']) . '/to/' . ($pkm['metadata']['nationalId'] + 1) . '/5';

            $client->setUri($url);
            $response = $client->request();
            $before_after_pkm = Zend_Json::decode($response->getBody());
            $next_pkm = $before_after_pkm[1]['metadata'];
        }
        else{
            $url = POKEMON_BY_RANGE . ($pkm['metadata']['nationalId'] -1) . '/to/' . ($pkm['metadata']['nationalId']) . '/5';

            $client->setUri($url);
            $response = $client->request();
            $before_after_pkm = Zend_Json::decode($response->getBody());
            $previous_pkm = $before_after_pkm[0]['metadata'];
        }


        /*
         * ========================================
         * initiate evolution chain json
         * ========================================
         */

        $client->setUri(POKEMON_EVO_CHAIN . $pkm['metadata']['nationalId']);
        $response = $client->request();
        $evolution_chain = Zend_Json::decode($response->getBody());






        /*
         * ======================================================
         * start passing it all to the View here
         * ======================================================
         */





        $this->view->pkm = $pkm;
        $this->view->previous_pkm = $previous_pkm;
        $this->view->next_pkm = $next_pkm;
        $this->view->evolution_chain = $evolution_chain;
        $this->view->POKEMON_IMAGE_DIR = POKEMON_IMAGE_DIR;





        echo "<h2>EVOLUTION CHAIN JSON</h2> <pre>";
        var_dump($evolution_chain);

        echo "</pre>";


        echo "<h2>TEST PKM JSON</h2> <pre>";
        var_dump($evolution_chain);

        // var_dump($test_next_pkm);
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

