<?php
/**
 * @file
 * Contains \Drupal\phparch\Controller\WeatherController.
 */

namespace Drupal\phparch\Controller;

use Drupal\Core\Controller\ControllerBase;
use GuzzleHttp\Client;
use Drupal\phparch\Service\WeatherService;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides route responses for the phparch  module.
 */
class WeatherController extends ControllerBase {

    protected $weatherService;

    public function __construct(WeatherService $ws) {
        $this->weatherService = $ws;
        $config = $this->config('phparch.settings');
        $this->weatherService->setApiKey($config->get('api_key'));
    }
    public function zip() {

        // check $_GET for a zip code
        if (!isset($_GET['zipcode'])) {
            return $this->redirect('phparch.weather');
        }

        // filter zipcode
        $zipcode = filter_var($_GET['zipcode'], FILTER_SANITIZE_STRING);

        // get weather;
        try {
            $weather = $this->weatherService->fetchByZipCode($zipcode);

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

    public static function create(ContainerInterface $container) {
        return new static(
            $container->get('phparch.weather')
        );
    }
}
