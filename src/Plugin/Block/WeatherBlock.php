<?php

namespace Drupal\phparch\Plugin\Block;

use Drupal\Core\Block\BlockBase;

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

        $zip = 22030;
        $weather = $weatherService->fetchByZipCode($zip);

        // use our theme function to render twig template
        $element = array(
            '#theme' => 'phparch_current_weather',
            '#location' => $weather->name,
            '#temperature' => $weather->main->temp,
            '#description' => $weather->weather[0]->description,
            '#zipcode' => $zip,
        );

        return $element;
    }
}