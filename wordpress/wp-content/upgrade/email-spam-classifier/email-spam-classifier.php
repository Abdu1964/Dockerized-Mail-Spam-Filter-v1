<?php
if (!defined('ABSPATH')) {
    exit;
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
    $flask_api_url = 'http://localhost:5000/classify'; // Update with actual Flask API URL
    
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
