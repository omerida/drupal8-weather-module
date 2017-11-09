<?php
/**
 * @file
 * Contains \Drupal\phparch\Controller\WeatherController.
 */

namespace Drupal\phparch\Controller;

use Drupal\Core\Controller\ControllerBase;
use GuzzleHttp\Client;

/**
 * Provides route responses for the phparch  module.
 */
class WeatherController extends ControllerBase {

    public function zip() {

        // check $_GET for a zip code
        if (!isset($_GET['zipcode'])) {
            return $this->redirect('phparch.weather');
        }

        // filter zipcode
        $zipcode = filter_var($_GET['zipcode'], FILTER_SANITIZE_STRING);

        // get weather;
        try {
            /** @var \Drupal\phparch\Service\WeatherService $weatherService */
            $weatherService = \Drupal::service('phparch.weather');

            $config = $this->config('phparch.settings');
            $weatherService->setApiKey($config->get('api_key'));
            $weather = $weatherService->fetchByZipCode($zipcode);

            // use our theme function to render twig template
            $element = array(
                '#theme' => 'phparch_current_weather',
                '#location' => $weather->name,
                '#temperature' => $weather->main->temp,
                '#description' => $weather->weather[0]->description,
                '#zipcode' => $zipcode,
            );
            $element['#cache']['max-age'] = 0;
            return $element;
        } catch (\Exception $e) {
            drupal_set_message(t('Could not fetch weather, please try again later:' . $e->getMessage()), 'error');
            return $this->redirect('phparch.weather');
        }
    }
}
