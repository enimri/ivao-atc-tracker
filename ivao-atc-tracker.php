<?php
/*
Plugin Name: IVAO ATC Tracker
Description: Displays online ATCs at specific airports and allows adding/removing ATCs via a backend interface.
Version: 1.7
Author: Your Name
*/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Enqueue admin scripts
function ivao_atc_tracker_admin_scripts() {
    wp_enqueue_script('ivao-atc-admin-js', plugin_dir_url(__FILE__) . 'admin.js', array('jquery'), '1.0', true);
}
add_action('admin_enqueue_scripts', 'ivao_atc_tracker_admin_scripts');

// Register settings
function ivao_atc_tracker_register_settings() {
    register_setting('ivao_atc_tracker_settings_group', 'ivao_atc_list');
}
add_action('admin_init', 'ivao_atc_tracker_register_settings');

// Add settings page
function ivao_atc_tracker_add_admin_menu() {
    add_options_page('IVAO ATC Tracker Settings', 'IVAO ATC Tracker', 'manage_options', 'ivao-atc-tracker', 'ivao_atc_tracker_settings_page');
}
add_action('admin_menu', 'ivao_atc_tracker_add_admin_menu');

// Render settings page
function ivao_atc_tracker_settings_page() {
?>
    <div class="wrap">
        <h1>IVAO ATC Tracker Settings</h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('ivao_atc_tracker_settings_group');
            do_settings_sections('ivao_atc_tracker_settings_group');
            ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">ATC Callsigns</th>
                    <td>
                        <?php ivao_atc_list_render(); ?>
                    </td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
<?php
}

// Render ATC callsign input fields
function ivao_atc_list_render() {
    $atc_list = get_option('ivao_atc_list', []);

    echo '<div id="ivao-atc-list">';
    if (!empty($atc_list)) {
        foreach ($atc_list as $atc) {
            echo '<div class="atc-input"><input type="text" name="ivao_atc_list[]" class="regular-text" value="' . esc_attr($atc) . '" />';
            echo '<button class="remove-atc button">Remove</button></div>';
        }
    } else {
        echo '<div class="atc-input"><input type="text" name="ivao_atc_list[]" class="regular-text" />';
        echo '<button class="remove-atc button">Remove</button></div>';
    }
    echo '</div>';
    echo '<button id="add-atc" class="button">Add ATC</button>';
}

// Fetch IVAO ATC data
function fetch_ivao_atc_data() {
    $response = wp_remote_get('https://api.ivao.aero/v2/tracker/whazzup');
    if (is_wp_error($response)) {
        return [];
    }

    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);
    $atc_list = get_option('ivao_atc_list', []);

    $result = [];

    foreach ($data['clients']['atcs'] as $atc) {
        if (in_array($atc['callsign'], $atc_list)) {
            $online_since_time = gmdate('H:i:s', $atc['onlineSince']); // Convert to UTC time only
            $result[] = [
                'callsign' => $atc['callsign'],
                'frequency' => $atc['atcSession']['frequency'],
                'online_since' => $online_since_time
            ];
        }
    }

    return $result;
}

// Shortcode to display ATC data
function render_ivao_atc_tracker() {
    $data = fetch_ivao_atc_data();

    ob_start();
    echo '<div class="ivao-atc-tracker">';
    echo '<h2>ATC Online</h2>';
    if (!empty($data)) {
        echo '<table>';
        echo '<tr><th>CALLSIGN</th><th>FREQUENCY</th><th>ONLINE SINCE (UTC)</th></tr>';
        foreach ($data as $atc) {
            echo '<tr>';
            echo '<td>' . esc_html($atc['callsign']) . '</td>';
            echo '<td>' . esc_html($atc['frequency']) . '</td>';
            echo '<td>' . esc_html($atc['online_since']) . '</td>';
            echo '</tr>';
        }
        echo '</table>';
    } else {
        echo '<p>No ATCs online.</p>';
    }
    echo '</div>';
    return ob_get_clean();
}
add_shortcode('ivao_atc_tracker', 'render_ivao_atc_tracker');
?>
