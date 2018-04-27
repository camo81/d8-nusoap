<?php
 
 
namespace Drupal\soap_client\Form;
 
use Drupal\Core\Form\ConfigFormBase;
 
use Drupal\Core\Form\FormStateInterface;
 
class soapClientConfigForm extends ConfigFormBase {
 
  /**
 
   * {@inheritdoc}
 
   */
 
  public function getFormId() {
 
    return 'soap_client_config_form';
 
  }
 
  /**
 
   * {@inheritdoc}
 
   */
 
  public function buildForm(array $form, FormStateInterface $form_state) {
 
    $form = parent::buildForm($form, $form_state);
 
    $config = $this->config('soap_client.settings');


      $form['connessione'] = array(
          '#type' => 'fieldset',
          '#title' => $this
              ->t('Dati connessione'),
      );


    $form['connessione']['username'] = array(

        '#type' => 'textfield',

        '#title' => $this->t('Enter username'),

        '#required' => TRUE,

        '#default_value' => $config->get('soap_client.username'),

    );


      $form['connessione']['password'] = array(

          '#type' => 'textfield',

          '#title' => $this->t('Enter password'),

          '#required' => TRUE,

          '#default_value' => $config->get('soap_client.password'),

      );

      $form['connessione']['j2eeuser'] = array(

          '#type' => 'textfield',

          '#title' => $this->t('Enter j2ee username'),

          '#required' => TRUE,

          '#default_value' => $config->get('soap_client.j2eeuser'),

      );


      $form['connessione']['j2eepassword'] = array(

          '#type' => 'textfield',

          '#title' => $this->t('Enter j2ee password'),

          '#required' => TRUE,

          '#default_value' => $config->get('soap_client.j2eepassword'),

      );

      /*
       *
       * wsalbo/wsalbo@bormio/bormio
       * http://212.124.185.114:58000/client/services/WSBachecaSoap
       *
       * */


    $form['wsdl'] = array(
 
      '#type' => 'textfield',
 
      '#title' => $this->t('Enter WS Bacheca wsdl url'),
 
      '#required' => TRUE,

      '#default_value' => $config->get('soap_client.wsdl'),
 
    );


 
    return $form;
 
  }
 
  /**
 
   * {@inheritdoc}
 
   */
 
  public function submitForm(array &$form, FormStateInterface $form_state) {
 
    $config = $this->config('soap_client.settings');
 
    $config->set('soap_client.wsdl', $form_state->getValue('wsdl'));
    $config->set('soap_client.username', $form_state->getValue('username'));
    $config->set('soap_client.password', $form_state->getValue('password'));
    $config->set('soap_client.j2eeuser', $form_state->getValue('j2eeuser'));
    $config->set('soap_client.j2eepassword', $form_state->getValue('j2eepassword'));

    $config->save();
 
    return parent::submitForm($form, $form_state);
 
  }
 
  /**
 
   * {@inheritdoc}
 
   */
 
  protected function getEditableConfigNames() {
 
    return [
 
      'soap_client.settings',
 
    ];
 
  }
 
}