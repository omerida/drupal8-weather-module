<?php
namespace Drupal\phparch\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Configure example settings for this site.
 */
class settingsForm extends ConfigFormBase
{
    const configName = 'phparch.settings';

    /**
     * {@inheritdoc}
     */
    public function getFormId()
    {
        return 'phparch_admin_settings';
    }

    /**
     * {@inheritdoc}
     */
    protected function getEditableConfigNames()
    {
        return [
            self::configName,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state)
    {
        $config = $this->config(self::configName);

        $form['api_key'] = array(
            '#type' => 'textfield',
            '#title' => $this->t('API Key'),
            '#default_value' => $config->get('api_key'),
        );

        return parent::buildForm($form, $form_state);
    }

    /**
     * {@inheritdoc}
     */
    public function submitForm(array &$form, FormStateInterface $form_state)
    {
        // Retrieve the configuration
        $this->config(self::configName)
        // Set the submitted configuration setting
            ->set('api_key', $form_state->getValue('api_key'))
            ->save();

        parent::submitForm($form, $form_state);
    }
}
