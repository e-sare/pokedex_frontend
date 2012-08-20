<?php

class PokedexController extends Zend_Controller_Action {

  public function init() {
    define('POKEDEX_BASE', 'http://localhost:8080/pokedex/');
    define('POKEMON_BY_NAME', POKEDEX_BASE . 'pokemon/slug/');
    define('POKEMON_BY_ID', POKEDEX_BASE . 'pokemon/national-id/');
    define('POKEMON_EVO_CHAIN', POKEDEX_BASE . 'evolutions/pokemon/national-id/');
    define('POKEMON_TYPE_EFFICACY', POKEDEX_BASE . 'types/efficacy/type1/');
    define('DEFAULT_GEN', 'generation/5');
    define('POKEMON_IMAGE_DIR', '/img/sugimori/');
    $this->_redirector = $this->_helper->getHelper('Redirector');
  }

  public function indexAction() {
    if ($this->getRequest()->isPost() && $this->_getParam("name")) {
      $query = htmlentities($this->_getParam("name"));
      if (is_numeric($query)) {
        $url = POKEMON_BY_ID . $query;
        $client = new Zend_Http_Client($url);
        $response = $client->request();
        $pkm = Zend_Json::decode($response->getBody());
        if (array_key_exists('IGNPokedexError', $pkm)) {
          $this->_redirector->gotoSimple('ohsnap', 'pokedex', null, array());
        } else {
          $this->_redirector->gotoSimple('pokemon', 'pokedex', null, array('pokemon' => $pkm['slug'])
          );
        }
      } else {
        $url = POKEMON_BY_NAME . $query;
        $client = new Zend_Http_Client($url);
        $response = $client->request();
        $pkm = Zend_Json::decode($response->getBody());
        if (array_key_exists('IGNPokedexError', $pkm)) {
          die('YOU ARE NOT COOL ENOUGH TO CATCH EM ALL');
        } else {
          $this->_redirector->gotoSimple('pokemon', 'pokedex', null, array('pokemon' => $pkm['metadata']['name'])
          );
        }
      }
    }
  }

  public function browseAction() {
  }

  public function comparisonAction() {
  }

  public function mapsAction() {
  }

  public function ohsnapAction() {
  }

  public function pokemonAction() {

    $pkmName = $this->_request->getParam('pokemon');
    $url = POKEMON_BY_NAME . $pkmName;
    $client = new Zend_Http_Client($url);
    $pokemonJSON = $client->request();
    $pkm = Zend_Json::decode($pokemonJSON->getBody());
    // previous and next pkm
    $prevPkmNationalId = ($pkm['metadata']['nationalId']) - 1;
    $prevPkmUrl = POKEMON_BY_ID . $prevPkmNationalId;
    $prevClient = new Zend_Http_Client($prevPkmUrl);
    $prevPkmJSON = $prevClient->request();
    $prevPkm = Zend_Json::decode($prevPkmJSON->getBody());
    if (array_key_exists('IGNPokedexError', $prevPkm)) {
      $prevPkm = null;
    }

    $nextPkmNationalId = ($pkm['metadata']['nationalId']) + 1;
    $nextPkmUrl = POKEMON_BY_ID . $nextPkmNationalId;
    $nextClient = new Zend_Http_Client($nextPkmUrl);
    $nextPkmJSON = $nextClient->request();
    $nextPkm = Zend_Json::decode($nextPkmJSON->getBody());
    if (array_key_exists('IGNPokedexError', $nextPkm)) {
      $nextPkm = null;
    }
    

    if (count($pkm['metadata']['type']) == 1) {
      $url = POKEMON_TYPE_EFFICACY . strtolower($pkm['metadata']['type']['type_1']);
      $client->setUri($url);
      $typeJSON = $client->request();
      $typeDefense = Zend_Json::decode($typeJSON->getBody());
    } else {
      $url = POKEMON_TYPE_EFFICACY . strtolower($pkm['metadata']['type']['type_1']) . '/type2/' . strtolower($pkm['metadata']['type']['type_2']);
      $client->setUri($url);
      //die($url);
      $typeJSON = $client->request();
      $typeDefense = Zend_Json::decode($typeJSON->getBody());
    }

    // evolution
    $url = POKEMON_EVO_CHAIN . $pkm['metadata']['nationalId'];
    $client->setUri($url);
    $evolutionJSON = $client->request();
    $eChain = Zend_Json::decode($evolutionJSON->getBody());
    $evoBasic = array(array());
    $evoStage1 = array();
    $evoStage2 = array();
    if (!array_key_exists('IGNPokedexError', $eChain)) {
      // chain has 1 object
      if (array_key_exists('evolutionChain', $eChain)) {
        // no need to loop
        $evoBasic = array(array($eChain['fromName'], $eChain['from'], 'Base form'));
        $evoStage1 = array(array($eChain['toName'], $eChain['to'], $eChain['how']));
      } else {
        // we have more..loop
        $evoBasic = array(array($eChain[0]['fromName'], $eChain[0]['from'], 'Base form'));
        foreach ($eChain as $stage) {
          if ($stage['from'] == $evoBasic[0][1]) {
            $evoStage1[] = array($stage['toName'], $stage['to'], $stage['how']);
          } else {
            $evoStage2[] = array($stage['toName'], $stage['to'], $stage['how']);
          }
        }
      }
    }

    // initialize type_efficacy arrays
    $typeFourth = array();
    $typeHalf = array();
    $typeDouble = array();
    $typeFour = array();
    $typeImmune = array();

    // group type efficacy by damage ratio
    foreach ($typeDefense as $type => $value) {
      $type = ucwords($type);
      if ($value == 25) {
        $typeFourth[] = $type;
      } elseif ($value == 50) {
        $typeHalf[] = $type;
      } elseif ($value == 200) {
        $typeDouble[] = $type;
      } elseif ($value == 400) {
        $typeFour[] = $type;
      } elseif ($value == 0) {
        $typeImmune[] = $type;
      }
    }

    $this->view->pkm = $pkm;
    $this->view->evoBasic = $evoBasic;
    $this->view->evoSt1 = $evoStage1;
    $this->view->evoSt2 = $evoStage2;
    $this->view->prevPkm = $prevPkm;
    $this->view->next_pkm = $nextPkm;
    $this->view->evolution_chain = $eChain;
    $this->view->type_defense = $typeDefense;
    $this->view->POKEMON_IMAGE_DIR = POKEMON_IMAGE_DIR;
    $this->view->typeFourth = $typeFourth;
    $this->view->typeHalf = $typeHalf;
    $this->view->typeDouble = $typeDouble;
    $this->view->typeFour = $typeFour;
    $this->view->typeImmune = $typeImmune;
  }

}
