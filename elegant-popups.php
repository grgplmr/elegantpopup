<?php
/**
 * Plugin Name: Elegant Popups
 * Plugin URI: https://example.com/elegant-popups
 * Description: Plugin WordPress pour afficher des pop-ups personnalisables avec design glassmorphism - pop-up d'accueil et de sortie d'intention.
 * Version: 1.0.0
 * Author: Votre Nom
 * Author URI: https://example.com
 * Text Domain: elegant-popups
 * Domain Path: /languages
 * Requires at least: 5.0
 * Tested up to: 6.4
 * Requires PHP: 7.4
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

// Sécurité : empêcher l'accès direct
if (!defined('ABSPATH')) {
    exit;
}

// Définir les constantes du plugin
define('ELEGANT_POPUPS_VERSION', '1.0.0');
define('ELEGANT_POPUPS_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('ELEGANT_POPUPS_PLUGIN_URL', plugin_dir_url(__FILE__));
define('ELEGANT_POPUPS_TEXT_DOMAIN', 'elegant-popups');

/**
 * Classe principale du plugin
 */
class ElegantPopups {
    
    /**
     * Instance unique de la classe
     */
    private static $instance = null;
    
    /**
     * Options par défaut
     */
    private $default_options = array(
        'welcome_popup' => array(
            'enabled' => false,
            'content' => 'Bienvenue sur notre site ! <a href="#" style="color: #3b82f6;">Découvrez nos offres</a>',
            'background_color' => 'rgba(255, 255, 255, 0.1)',
            'text_color' => '#1f2937',
            'font_size' => '16',
            'width' => '400',
            'height' => '300',
            'delay' => '3000',
            'show_once' => true
        ),
        'exit_popup' => array(
            'enabled' => false,
            'content' => 'Attendez ! Ne partez pas sans découvrir <a href="#" style="color: #ef4444;">nos promotions exclusives</a>',
            'background_color' => 'rgba(239, 68, 68, 0.1)',
            'text_color' => '#1f2937',
            'font_size' => '16',
            'width' => '400',
            'height' => '250'
        )
    );
    
    /**
     * Obtenir l'instance unique
     */
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Constructeur privé
     */
    private function __construct() {
        add_action('init', array($this, 'init'));
        add_action('plugins_loaded', array($this, 'load_textdomain'));
        register_activation_hook(__FILE__, array($this, 'activate'));
        register_deactivation_hook(__FILE__, array($this, 'deactivate'));
        register_uninstall_hook(__FILE__, array('ElegantPopups', 'uninstall'));
    }
    
    /**
     * Initialisation du plugin
     */
    public function init() {
        // Interface d'administration
        if (is_admin()) {
            add_action('admin_menu', array($this, 'add_admin_menu'));
            add_action('admin_init', array($this, 'admin_init'));
            add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts'));
        } else {
            // Frontend - seulement si on n'est pas dans l'admin
            add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
            add_action('wp_footer', array($this, 'render_popups'));
        }
    }
    
    /**
     * Charger les traductions
     */
    public function load_textdomain() {
        load_plugin_textdomain(
            ELEGANT_POPUPS_TEXT_DOMAIN,
            false,
            dirname(plugin_basename(__FILE__)) . '/languages/'
        );
    }
    
    /**
     * Activation du plugin
     */
    public function activate() {
        if (!get_option('elegant_popups_options')) {
            add_option('elegant_popups_options', $this->default_options);
        }
        flush_rewrite_rules();
    }
    
    /**
     * Désactivation du plugin
     */
    public function deactivate() {
        flush_rewrite_rules();
    }
    
    /**
     * Désinstallation du plugin
     */
    public static function uninstall() {
        delete_option('elegant_popups_options');
        delete_option('elegant_popups_version');
    }
    
    /**
     * Ajouter le menu d'administration
     */
    public function add_admin_menu() {
        add_menu_page(
            __('Elegant Popups', ELEGANT_POPUPS_TEXT_DOMAIN),
            __('Elegant Popups', ELEGANT_POPUPS_TEXT_DOMAIN),
            'manage_options',
            'elegant-popups',
            array($this, 'admin_page'),
            'dashicons-visibility',
            30
        );
    }
    
    /**
     * Initialisation de l'administration
     */
    public function admin_init() {
        register_setting('elegant_popups_options', 'elegant_popups_options');
        
        // Section Pop-up d'accueil
        add_settings_section(
            'welcome_popup_section',
            __('Pop-up d\'accueil', ELEGANT_POPUPS_TEXT_DOMAIN),
            array($this, 'welcome_popup_section_callback'),
            'elegant-popups'
        );
        
        // Section Pop-up de sortie
        add_settings_section(
            'exit_popup_section',
            __('Pop-up de sortie d\'intention', ELEGANT_POPUPS_TEXT_DOMAIN),
            array($this, 'exit_popup_section_callback'),
            'elegant-popups'
        );
    }
    
    /**
     * Enregistrer les scripts d'administration
     */
    public function admin_enqueue_scripts($hook) {
        if ($hook !== 'toplevel_page_elegant-popups') {
            return;
        }
        
        wp_enqueue_media();
        wp_enqueue_editor();
        wp_enqueue_script('wp-color-picker');
        wp_enqueue_style('wp-color-picker');
        
        wp_enqueue_script(
            'elegant-popups-admin',
            ELEGANT_POPUPS_PLUGIN_URL . 'assets/js/admin.js',
            array('jquery', 'wp-color-picker'),
            ELEGANT_POPUPS_VERSION,
            true
        );
        
        wp_enqueue_style(
            'elegant-popups-admin',
            ELEGANT_POPUPS_PLUGIN_URL . 'assets/css/admin.css',
            array(),
            ELEGANT_POPUPS_VERSION
        );
    }
    
    /**
     * Enregistrer les scripts frontend
     */
    public function enqueue_scripts() {
        // Ne charger que si au moins un popup est activé
        $options = get_option('elegant_popups_options', $this->default_options);
        
        if (!$options['welcome_popup']['enabled'] && !$options['exit_popup']['enabled']) {
            return;
        }
        
        wp_enqueue_script(
            'elegant-popups-frontend',
            ELEGANT_POPUPS_PLUGIN_URL . 'assets/js/frontend.js',
            array(),
            ELEGANT_POPUPS_VERSION,
            true
        );
        
        wp_enqueue_style(
            'elegant-popups-frontend',
            ELEGANT_POPUPS_PLUGIN_URL . 'assets/css/frontend.css',
            array(),
            ELEGANT_POPUPS_VERSION
        );
        
        // Passer les options au JavaScript
        wp_localize_script('elegant-popups-frontend', 'elegantPopupsData', array(
            'options' => $options,
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('elegant_popups_nonce')
        ));
    }
    
    /**
     * Page d'administration
     */
    public function admin_page() {
        $options = get_option('elegant_popups_options', $this->default_options);
        
        if (isset($_POST['submit'])) {
            check_admin_referer('elegant_popups_admin', 'elegant_popups_nonce');
            
            $new_options = array();
            
            // Pop-up d'accueil
            $new_options['welcome_popup'] = array(
                'enabled' => isset($_POST['welcome_enabled']),
                'content' => wp_kses_post($_POST['welcome_content']),
                'background_color' => sanitize_text_field($_POST['welcome_bg_color']),
                'text_color' => sanitize_hex_color($_POST['welcome_text_color']),
                'font_size' => intval($_POST['welcome_font_size']),
                'width' => intval($_POST['welcome_width']),
                'height' => intval($_POST['welcome_height']),
                'delay' => intval($_POST['welcome_delay']),
                'show_once' => isset($_POST['welcome_show_once'])
            );
            
            // Pop-up de sortie
            $new_options['exit_popup'] = array(
                'enabled' => isset($_POST['exit_enabled']),
                'content' => wp_kses_post($_POST['exit_content']),
                'background_color' => sanitize_text_field($_POST['exit_bg_color']),
                'text_color' => sanitize_hex_color($_POST['exit_text_color']),
                'font_size' => intval($_POST['exit_font_size']),
                'width' => intval($_POST['exit_width']),
                'height' => intval($_POST['exit_height'])
            );
            
            update_option('elegant_popups_options', $new_options);
            $options = $new_options;
            
            echo '<div class="notice notice-success"><p>' . __('Options sauvegardées avec succès !', ELEGANT_POPUPS_TEXT_DOMAIN) . '</p></div>';
        }
        
        include ELEGANT_POPUPS_PLUGIN_DIR . 'templates/admin-page.php';
    }
    
    /**
     * Callback pour la section pop-up d'accueil
     */
    public function welcome_popup_section_callback() {
        echo '<p>' . __('Configurez votre pop-up d\'accueil qui s\'affiche après un délai choisi.', ELEGANT_POPUPS_TEXT_DOMAIN) . '</p>';
    }
    
    /**
     * Callback pour la section pop-up de sortie
     */
    public function exit_popup_section_callback() {
        echo '<p>' . __('Configurez votre pop-up de sortie d\'intention qui s\'affiche quand l\'utilisateur veut quitter la page.', ELEGANT_POPUPS_TEXT_DOMAIN) . '</p>';
    }
    
    /**
     * Rendu des pop-ups en frontend
     */
    public function render_popups() {
        $options = get_option('elegant_popups_options', $this->default_options);
        
        if ($options['welcome_popup']['enabled'] || $options['exit_popup']['enabled']) {
            include ELEGANT_POPUPS_PLUGIN_DIR . 'templates/frontend-popups.php';
        }
    }
}

// Initialiser le plugin
ElegantPopups::getInstance();