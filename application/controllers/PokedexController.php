<?php

class PokedexController extends Zend_Controller_Action
{

    public function init()
    {
        define('POKEDEX_BASE','http://localhost:8080/pokedex/');
        define('POKEMON_BY_NAME', POKEDEX_BASE . 'pokemon/');
        define('POKEMON_BY_RANGE', POKEMON_BY_NAME . 'all/');
        define('POKEMON_BY_ID', POKEMON_BY_NAME . 'national_id/');
        define('POKEMON_EVO_CHAIN', POKEDEX_BASE . 'evolutions/pokemon/');

        define('POKEMON_TYPE_EFFICACY', POKEDEX_BASE . 'types/efficacy/type1/');

        define('DEFAULT_GEN','generation/5');
        define('POKEMON_IMAGE_DIR','/img/sugimori/');


        /* Initialize action controller here */
        $this->_redirector = $this->_helper->getHelper('Redirector');
    }

    public function indexAction()
    {
        if($this->getRequest()->isPost() && $this->_getParam("name")){

            $query = htmlentities($this->_getParam("name"));

            if(is_numeric($query)){

                $url = POKEMON_BY_ID . $query . '/' . DEFAULT_GEN;

                //die(var_dump($url));

                $client = new Zend_Http_Client($url);
                $response = $client->request();

                //die(var_dump($response));
                $pkm = Zend_Json::decode($response->getBody());

                if(empty($pkm)){
                    $this->_redirector->gotoSimple('ohsnap',
                    'pokedex',
                    null,
                    array());
                }
                else{
                    $this->_redirector->gotoSimple('pokemon',
                    'pokedex',
                    null,
                    array( 'pokemon' => $pkm['metadata']['name'] )
                    );
                }
            }
            else{
                $query = explode(' ', $query)[0];
                $url = POKEMON_BY_NAME . $query;
                // die(var_dump($url));

                $client = new Zend_Http_Client($url);
                $response = $client->request();

                //die(var_dump($response));
                $pkm = Zend_Json::decode($response->getBody());

                if(empty($pkm)){
                    die('YOU ARE NOT COOL ENOUGH TO CATCH EM ALL' );
                }
                else{
                     $this->_redirector->gotoSimple('pokemon',
                    'pokedex',
                    null,
                    array( 'pokemon' => $pkm['metadata']['name'])
                    );
                }
            }
        }
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

    public function ohsnapAction()
    {

    }

    public function pokemonAction()
    {

        //localhost:8080/pokedex/pokemon/pikachu


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




        /*
         * ========================================
         * initiate pokemon json
         * ========================================
         */

        $pkm_name = $this->_request->getParam('pokemon');

        $url = POKEMON_BY_NAME . $pkm_name . '/' . DEFAULT_GEN;
        $client = new Zend_Http_Client($url);
		$response = $client->request();
        $pkm = Zend_Json::decode($response->getBody());


        $previous_pkm = '';
        $next_pkm  = '';


        // Get JSON for previous & next Pokemon for top navigation
        if($pkm['metadata']['nationalId'] >= 2 && $pkm ['metadata']['nationalId'] <= 648){

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
            $url = POKEMON_BY_RANGE . ($pkm['metadata']['nationalId'] - 1) . '/to/' . ($pkm['metadata']['nationalId']) . '/5';

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

        $url = POKEMON_EVO_CHAIN . $pkm['metadata']['nationalId'];

        $client->setUri($url);
        $response = $client->request();
        $evolution_chain = Zend_Json::decode($response->getBody());


        /*
         * ========================================
         * initiate type defense json
         * ========================================
         */

        if(count($pkm['metadata']['name']) == 1){

            $url = POKEMON_TYPE_EFFICACY. strtolower($pkm['metadata']['type']['type_1']) ;

            $client->setUri($url);
            $response = $client->request();
            $type_defense = Zend_Json::decode($response->getBody());


        }else{

            $url = POKEMON_TYPE_EFFICACY. strtolower($pkm['metadata']['type']['type_1']) . '/type2/' . strtolower($pkm['metadata']['type']['type_2']);

            $client->setUri($url);
            //die($url);
            $response = $client->request();
            $type_defense = Zend_Json::decode($response->getBody());
        }





        /*
         * ======================================================
         * start passing it all to the View here
         * ======================================================
         */





        $this->view->pkm = $pkm;
        $this->view->previous_pkm = $previous_pkm;
        $this->view->next_pkm = $next_pkm;
        $this->view->evolution_chain = $evolution_chain;
        $this->view->type_defense = $type_defense;
        $this->view->POKEMON_IMAGE_DIR = POKEMON_IMAGE_DIR;





//        echo "<h2>TYPE DEFENSE JSON</h2> <pre>";
//        var_dump($type_defense);
//
//        echo "</pre>";
//
//
//        echo "<h2>TEST PKM JSON</h2> <pre>";
//        echo count($pkm['metadata']['type']) . "<br >";
//        var_dump($pkm);
//
//        // var_dump($test_next_pkm);
//        echo "</pre>";



        /*
        $pkm = new Pokemon($json);

        $this->view->pkm = $pkm;


k
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

