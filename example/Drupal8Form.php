<?php
/**
 * Created by PhpStorm.
 * User: dvienne
 * Date: 22/08/2017
 * Time: 15:00
 */

namespace Drupal\fapi_example\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Dominiquevienne\Honeypot\Form;

/**
 * Implements InputDemo form controller.
 *
 * This example demonstrates the different input elements that are used to
 * collect data in a form.
 */
class InputDemo extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = array('drupalForm' => TRUE);
    $oForm  = new Form($config);
    $form   = $oForm->inputs();

    // Add a submit button that handles the submission of the form.
    $form['actions']['submit'] = [
      '#type'         => 'submit',
      '#value'        => $this->t('Submit'),
      '#description'  => $this->t('Submit, #type = submit'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'fapi_example_input_demo_form';
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    /** do the submit thing */
  }

}
