<?php
/**
 * @file
 * Contains \Drupal\resume\Form\ResumeForm.
 */
namespace Drupal\soap_client\Form;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\soap_client\Plugin\nusoap_client;
use Symfony\Component\HttpFoundation\RedirectResponse;


class FilterForm extends FormBase {
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'filter_form';
  }


  public function buildForm(array $form, FormStateInterface $form_state,$anno=0,$numeroProtocollo=0) {


   //impostazione dei valori di default
    if ($anno ==0){

        $anno = date("Y");
    }

    if ($numeroProtocollo == 0)
    {

        $numeroProtocollo = "";
    }

    $form['filter_anno'] = array(
      '#type' => 'textfield',
      '#title' => t('Anno:'),
      '#required' => FALSE,
      '#default_value' => $anno,
    );
    $form['filter_numero'] = array(
      '#type' => 'textfield',
      '#title' => t('Numero protocollo:'),
      '#required' => FALSE,
      '#default_value' => $numeroProtocollo,
    );
    $form['filter_oggetto'] = array (
      '#type' => 'textfield',
      '#title' => t('Oggetto:'),
    );
    $form['filter_soggetto'] = array (
      '#type' => 'textfield',
      '#title' => t('Soggetto:'),
      '#required' => FALSE,
        '#weight' => '1'
    );

    $form['actions']['#type'] = 'actions';

    $form['actions']['submit'] = array(
      '#type' => 'submit',
      '#value' => $this->t('Save'),
      '#button_type' => 'primary',
      '#weight' => '-1'
    );


    $this->createTable($form,$anno,$numeroProtocollo);

    return $form;
  }


  public function validateForm(array &$form, FormStateInterface $form_state) {
      /*if (strlen($form_state->getValue('candidate_number')) < 10) {
        $form_state->setErrorByName('candidate_number', $this->t('Mobile number is too short.'));
      }*/
    }


  public function submitForm(array &$form, FormStateInterface $form_state) {
    /*
    foreach ($form_state->getValues() as $key => $value) 
    	{
      		drupal_set_message($key . ': ' . $value);
    	}
    */

    $dati = $form_state->getCompleteForm();
    $anno = $dati['filter_anno']['#value'];
    $nP = $dati['filter_numero']['#value'];

    $current_path = \Drupal::service('path.current')->getPath();
    $path = $current_path."/".$anno."/".$nP;
    $response = new RedirectResponse($path);
    $response->send();

    }


  public function createTable(array &$form, $anno =NULL,$numeroProtocollo=NULL){

          $config = \Drupal::config('soap_client.settings');
          $this->wsdl = $config->get('soap_client.wsdl');

          $client = new nusoap_client($this->wsdl, false);
          $client->charencoding = false;
          $cdataString = '<![CDATA[<?xml version="1.0" encoding="UTF-8"?>
                <BachecaFiltri>
                <TipoAtto></TipoAtto>
                <Anno>'.$anno.'</Anno>
                <Parola></Parola>
                <TipoRicercaParola>E</TipoRicercaParola>
                <NumeroProtocollo>'.$numeroProtocollo.'</NumeroProtocollo>
                </BachecaFiltri>
                ]]>';

          $username = $config->get('soap_client.username');
          $password = $config->get('soap_client.password');
          $j2eeuser = $config->get('soap_client.j2eeuser');
          $j2eepass = $config->get('soap_client.j2eepassword');

          $params = array('BachecaFiltriStr' => $cdataString,'CodiceAmministrazione'=>'!'.$username.'/'.$password.'@'.$j2eeuser.'/'.$j2eepass);


          $result = $client->call('ConsultazioneAlboPretorioString', $params );
          $xml_result = simplexml_load_string(utf8_encode($result), "SimpleXMLElement", LIBXML_NOCDATA);
          $documenti = $xml_result->Documenti->Documento;



          $output = array();


          foreach ($documenti as $key => $value) {
                $idDoc = (string)$value->IdDocumento;
                $tipo = (string)$value->TipoAtto_Descrizione;
                $Oggetto = (string)$value->Oggetto;
                $Numero = (string)$value->NumeroProtocollo;


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

		$form['table'] = [
	      '#theme' => 'table',
	      '#header' => $header,
	      '#rows' => $output,
            '#weight' => '10'
	    ];
	  // Finally add the pager.
	    $form['pager'] = array(
	      '#type' => 'pager'
	    );

	} 

}

