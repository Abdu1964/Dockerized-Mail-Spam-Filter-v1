<?php
/*
Plugin Name: Email Spam Classifier
Description: A plugin for integrating Flask API with WordPress.
Version: 1.0
Author: Abdu Mohammed
*/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Add a menu item in WordPress admin
function email_classifier_menu() {
    add_menu_page('Email Spam Classifier', 'Email Classifier', 'manage_options', 'email-classifier', 'email_classifier_page');
}
add_action('admin_menu', 'email_classifier_menu');

// Display the plugin settings page
function email_classifier_page() {
    ?>
    <div class="wrap">
        <h1>Email Spam Classifier</h1>
        <form method="post" action="">
            <label for="email">Enter Email Content:</label><br>
            <textarea name="email" id="email" rows="5" cols="50" required></textarea><br><br>
            <input type="submit" name="classify_email" value="Classify" class="button button-primary">
        </form>
        <?php
        if (isset($_POST['classify_email'])) {
            $email_content = sanitize_text_field($_POST['email']);
            $result = classify_email($email_content);
            echo '<h2>Classification Result: ' . esc_html($result) . '</h2>';
        }
        ?>
    </div>
    <?php
}

// Function to communicate with Flask API
function classify_email($email) {
    $flask_api_url = 'http://flask-api:5000/classify'; // Update with actual Flask API URL
    
    $response = wp_remote_post($flask_api_url, array(
        'body'    => json_encode(array('email' => $email)),
        'headers' => array('Content-Type' => 'application/json'),
        'method'  => 'POST',
    ));
    
    if (is_wp_error($response)) {
        return 'Error: Unable to reach API';
    }
    
    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);
    return isset($data['result']) ? $data['result'] : 'Unknown Result';
}

// Register the shortcode to display the email classifier form
function email_classifier_shortcode() {
    ob_start();
    ?>
    <div class="email-classifier-page">
        <h1>Email Spam Classifier</h1>
        <form method="post" action="" class="email-classifier-form">
            <label for="email">Enter Email Content:</label><br>
            <textarea name="email" id="email" rows="5" cols="50" required></textarea><br><br>
            <input type="submit" name="classify_email" value="Classify" class="button button-primary email-classify-btn">
        </form>
        <?php
        if (isset($_POST['classify_email'])) {
            $email_content = sanitize_text_field($_POST['email']);
            $result = classify_email($email_content);
            echo '<h2 class="result">Classification Result: ' . esc_html($result) . '</h2>';
        }
        ?>
    </div>
    <?php
    return ob_get_clean();
}

// Register the shortcode with WordPress
add_shortcode('email_classifier', 'email_classifier_shortcode');

// Enqueue plugin CSS
function email_classifier_styles() {
    wp_enqueue_style('email-classifier-styles', plugin_dir_url(__FILE__) . 'styles.css');
}
add_action('wp_enqueue_scripts', 'email_classifier_styles');
