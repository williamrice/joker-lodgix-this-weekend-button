<?php

/**
 * Plugin Name: Lodgix This Weekend Button
 * Description: Adds a shortcode to render a button that will calculate the upcoming weekend dynamically. 
 * Version:     1.0
 * Author:      William Rice (Joker Business Solutions)
 * Author URI:  https://www.jokerbs.com
 */

// Exit if accessed directly.
if (! defined('ABSPATH')) {
    exit;
}

/**
 * Function to generate the button HTML.
 *
 * @return string The button HTML.
 */

function get_this_weekend_url()
{
    $currentDate = new DateTime('now');

    // Determine the current day of the week (0 = Sunday, 6 = Saturday)
    $dayOfWeek = $currentDate->format('w');


    $firstDate = null;
    if ($dayOfWeek == 0) {
        $firstDate = clone $currentDate;
        $firstDate->modify('Friday next week');
        $lastDate = clone $currentDate;
        $lastDate->modify('Sunday next week');
    } else if ($dayOfWeek == 6) {
        $firstDate = clone $currentDate;
        $lastDate = clone $currentDate;
        $lastDate->modify('Sunday this week');
    } else {
        $firstDate = clone $currentDate;
        $firstDate->modify('Friday this week');
        $lastDate = clone $currentDate;
        $lastDate->modify('Sunday this week');
    }


    $dateRange = $firstDate->format('m/d/Y') . ' - ' . $lastDate->format('m/d/Y');
    $encodedDateRange = urlencode($dateRange);


    // Encode the date range for use in a URL
    $current_domain = site_url();

    // Construct the URL
    $url = $current_domain . "/vacation-rentals/?ldx_date_range=" . $encodedDateRange;

    return $url;
}


function jbs_generate_button_shortcode()
{
    //get the current domain
    $domain = $_SERVER['HTTP_HOST'];
    // Define the URL for the button.
    $button_url = get_this_weekend_url();

    // Button HTML with the href variable.
    $button_html = '<a href="' . esc_url($button_url) . '" class="this-weekend-button">Available This Weekend</a>';

    return $button_html;
}

// Register the shortcode.
add_shortcode('lodgix_this_weekend_button', 'jbs_generate_button_shortcode');

/**
 * Function to enqueue plugin styles.
 */
function bsp_enqueue_styles()
{
    wp_enqueue_style('bsp-styles', plugin_dir_url(__FILE__) . 'css/styles.css');
}
add_action('wp_enqueue_scripts', 'bsp_enqueue_styles');
