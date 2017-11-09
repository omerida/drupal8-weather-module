<?php

namespace Drupal\phparch\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Provides a 'weather' block.
 *
 * @Block(
 *   id = "weather_block",
 *   admin_label = @Translation("Weather block"),
 * )
 */
class WeatherBlock extends BlockBase {
    /**
     * {@inheritdoc}
     */
    public function build() {
        $weatherService = \Drupal::service('phparch.weather');

        // array dereferencing
        $zipcode = $this->getConfiguration()['zipcode'];
        if (!empty($zipcode)) {
            try {
                $weather = $weatherService->fetchByZipCode($zipcode);

                // use our theme function to render twig template
                $element = array(
                    '#theme' => 'phparch_current_weather',
                    '#location' => $weather->name,
                    '#temperature' => $weather->main->temp,
                    '#description' => $weather->weather[0]->description,
                    '#zipcode' => $zipcode,
                    '#cache' => [
                        'max-age' => 60, // cache for one minute
                    ]
                );

                return $element;
            } catch (\Exception $ex) {

            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function blockForm($form, FormStateInterface $form_state) {
        $form = parent::blockForm($form, $form_state);

        // Retrieve existing configuration for this block.
        $config = $this->getConfiguration();

        // Add a form field to the existing block configuration form.
        $form['zipcode'] = array(
            '#type' => 'textfield',
            '#title' => t('U.S. Zip Code'),
            '#required' => true,
            '#default_value' => isset($config['zipcode']) ? $config['zipcode'] : '',
        );

        return $form;
    }

    /**
     * {@inheritdoc}
     */
    public function blockSubmit($form, FormStateInterface $form_state) {
        // Save our custom settings when the form is submitted.
        $this->setConfigurationValue('zipcode', $form_state->getValue('zipcode'));
    }

    public function blockAccess(AccountInterface $account) {
        if ($account->hasPermission('access content')) {
            return AccessResult::allowed();
        } else {
            return AccessResult::forbidden();
        }
    }
}