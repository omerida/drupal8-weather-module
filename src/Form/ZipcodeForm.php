<?php
/**
 * @file
 * Contains \Drupal\example\Form\ExampleForm.
 */

namespace Drupal\phparch\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Implements an example form.
 */
class ZipcodeForm extends FormBase {

    /**
     * {@inheritdoc}.
     */
    public function getFormId() {
        return 'zipcode_form';
    }

    /**
     * {@inheritdoc}.
     */
    public function buildForm(array $form, FormStateInterface $form_state) {
        $form['zipcode'] = array(
            '#type' => 'textfield',
            '#title' => $this->t('Your zip code')
        );
        $form['actions']['#type'] = 'actions';
        $form['actions']['submit'] = array(
            '#type' => 'submit',
            '#value' => $this->t('Check Conditions'),
            '#button_type' => 'primary',
        );
        return $form;
    }

    /**
     * {@inheritdoc}
     */
    public function validateForm(array &$form, FormStateInterface $form_state) {
        $value = $form_state->getValue('zipcode');
        if (false == ctype_digit($value) || strlen($value) !== 5) {
            $form_state->setErrorByName(
                'zipcode',
                $this->t('The value you entered is not a valid 5 digit zipcode, please enter it again.'));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function submitForm(array &$form, FormStateInterface $form_state) {
        $form_state->setRedirect('phparch.zip',
            ['zipcode' => $form_state->getValue('zipcode')]);
    }

}