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
   return array(
      '#type' => 'markup',
      '#markup' => 'This block lists the weather.',
    );
  }
}