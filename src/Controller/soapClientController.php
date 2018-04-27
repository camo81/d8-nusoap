<?php
namespace Drupal\soap_client\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\soap_client\nusoap_client;



class soapClientController extends ControllerBase {
  public $wsdl = "";

  public function __construct(){

      $config = \Drupal::config('soap_client.settings');
      // Will print 'Hello'.
      $this->wsdl = $config->get('soap_client.wsdl');
  }



/*
  public function ListaProvince(){


    $client = new nusoap_client('http://212.124.185.114:58000/client/services/WSBachecaSoap', false);
    $client->charencoding = false;
    $cdataString = '<![CDATA[<?xml version="1.0" encoding="UTF-8"?>
     	<BachecaFiltri>
     	<TipoAtto>DETERMINAZIONE</TipoAtto>
     	<Anno>2018</Anno>
     	</BachecaFiltri>
     	]]>';
    $params = array('BachecaFiltriStr' => $cdataString,'CodiceAmministrazione'=>'!wsalbo/wsalbo@bormio/bormio');
    				
	$mynamespace = "soap.computerhalley.it";

    $result = $client->call('ListaDelibereString', $params );
    $xml_result = simplexml_load_string(utf8_encode($result), "SimpleXMLElement", LIBXML_NOCDATA);
    $documenti = $xml_result->Documenti->Documento;

    //ksm($documenti);




	$output = array();


	foreach ($documenti as $key => $value) {
		$idDoc = (string)$value->IdDocumento;
		$tipo = (string)$value->TipoAtto_Descrizione;
		$Oggetto = (string)$value->Oggetto;
		$Numero = (string)$value->Numero;

	
		$output[$idDoc] = array('data' => array(
			'Id'=>$idDoc,
			'Tipo' => $tipo,
			'Oggetto' => $Oggetto,
			'Numero' => $Numero,
			));

		

	}



//dichiarazione tabella

  $header = array(
    array('data' => 'Id'),
    array('data' => 'Tipo'),
    array('data' => 'Oggetto'),
    array('data' => 'Numero'),
    );

	$table['table'] = [
      '#theme' => 'table',
      '#header' => $header,
      '#rows' => $output,
    ];
  // Finally add the pager.
    $table['pager'] = array(
      '#type' => 'pager'
    );




    return array(
      '#theme' => 'soap_client',
      '#source_text' => $table,
    );


  }


*/

  public function content() {

    return array(
      '#theme' => 'soap_client',
      '#source_text' => $this->wsdl,
    );
  }

}