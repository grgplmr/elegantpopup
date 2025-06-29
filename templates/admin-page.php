<?php
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="wrap">
    <h1><?php _e('Elegant Popups - Configuration', ELEGANT_POPUPS_TEXT_DOMAIN); ?></h1>
    
    <form method="post" action="">
        <?php wp_nonce_field('elegant_popups_admin', 'elegant_popups_nonce'); ?>
        
        <div class="elegant-popups-admin">
            <!-- Onglets -->
            <nav class="nav-tab-wrapper">
                <a href="#welcome-tab" class="nav-tab nav-tab-active" data-tab="welcome">
                    <?php _e('Pop-up d\'accueil', ELEGANT_POPUPS_TEXT_DOMAIN); ?>
                </a>
                <a href="#exit-tab" class="nav-tab" data-tab="exit">
                    <?php _e('Pop-up de sortie', ELEGANT_POPUPS_TEXT_DOMAIN); ?>
                </a>
                <a href="#preview-tab" class="nav-tab" data-tab="preview">
                    <?php _e('Aperçu', ELEGANT_POPUPS_TEXT_DOMAIN); ?>
                </a>
            </nav>
            
            <!-- Contenu Pop-up d'accueil -->
            <div id="welcome-tab" class="tab-content">
                <div class="postbox">
                    <div class="postbox-header">
                        <h2 class="hndle"><?php _e('Configuration Pop-up d\'accueil', ELEGANT_POPUPS_TEXT_DOMAIN); ?></h2>
                    </div>
                    <div class="inside">
                        <table class="form-table">
                            <tr>
                                <th scope="row"><?php _e('Activer', ELEGANT_POPUPS_TEXT_DOMAIN); ?></th>
                                <td>
                                    <label>
                                        <input type="checkbox" name="welcome_enabled" value="1" <?php checked($options['welcome_popup']['enabled']); ?>>
                                        <?php _e('Activer le pop-up d\'accueil', ELEGANT_POPUPS_TEXT_DOMAIN); ?>
                                    </label>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><?php _e('Contenu', ELEGANT_POPUPS_TEXT_DOMAIN); ?></th>
                                <td>
                                    <?php
                                    wp_editor($options['welcome_popup']['content'], 'welcome_content', array(
                                        'textarea_name' => 'welcome_content',
                                        'media_buttons' => true,
                                        'textarea_rows' => 8,
                                        'teeny' => false,
                                        'tinymce' => true,
                                        'quicktags' => true
                                    ));
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><?php _e('Couleur de fond', ELEGANT_POPUPS_TEXT_DOMAIN); ?></th>
                                <td>
                                    <input type="text" name="welcome_bg_color" value="<?php echo esc_attr($options['welcome_popup']['background_color']); ?>" class="color-field">
                                    <p class="description"><?php _e('Format RGBA recommandé pour la transparence (ex: rgba(255,255,255,0.1))', ELEGANT_POPUPS_TEXT_DOMAIN); ?></p>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><?php _e('Couleur du texte', ELEGANT_POPUPS_TEXT_DOMAIN); ?></th>
                                <td>
                                    <input type="text" name="welcome_text_color" value="<?php echo esc_attr($options['welcome_popup']['text_color']); ?>" class="color-picker">
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><?php _e('Taille de police', ELEGANT_POPUPS_TEXT_DOMAIN); ?></th>
                                <td>
                                    <input type="number" name="welcome_font_size" value="<?php echo esc_attr($options['welcome_popup']['font_size']); ?>" min="10" max="48"> px
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><?php _e('Dimensions', ELEGANT_POPUPS_TEXT_DOMAIN); ?></th>
                                <td>
                                    <label>
                                        <?php _e('Largeur:', ELEGANT_POPUPS_TEXT_DOMAIN); ?>
                                        <input type="number" name="welcome_width" value="<?php echo esc_attr($options['welcome_popup']['width']); ?>" min="200" max="800"> px
                                    </label>
                                    <br><br>
                                    <label>
                                        <?php _e('Hauteur:', ELEGANT_POPUPS_TEXT_DOMAIN); ?>
                                        <input type="number" name="welcome_height" value="<?php echo esc_attr($options['welcome_popup']['height']); ?>" min="150" max="600"> px
                                    </label>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><?php _e('Délai d\'apparition', ELEGANT_POPUPS_TEXT_DOMAIN); ?></th>
                                <td>
                                    <input type="number" name="welcome_delay" value="<?php echo esc_attr($options['welcome_popup']['delay']); ?>" min="0" max="30000" step="500"> ms
                                    <p class="description"><?php _e('Délai en millisecondes avant l\'affichage (1000ms = 1 seconde)', ELEGANT_POPUPS_TEXT_DOMAIN); ?></p>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><?php _e('Affichage unique', ELEGANT_POPUPS_TEXT_DOMAIN); ?></th>
                                <td>
                                    <label>
                                        <input type="checkbox" name="welcome_show_once" value="1" <?php checked($options['welcome_popup']['show_once']); ?>>
                                        <?php _e('N\'afficher qu\'une seule fois par visiteur', ELEGANT_POPUPS_TEXT_DOMAIN); ?>
                                    </label>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            
            <!-- Contenu Pop-up de sortie -->
            <div id="exit-tab" class="tab-content" style="display: none;">
                <div class="postbox">
                    <div class="postbox-header">
                        <h2 class="hndle"><?php _e('Configuration Pop-up de sortie', ELEGANT_POPUPS_TEXT_DOMAIN); ?></h2>
                    </div>
                    <div class="inside">
                        <table class="form-table">
                            <tr>
                                <th scope="row"><?php _e('Activer', ELEGANT_POPUPS_TEXT_DOMAIN); ?></th>
                                <td>
                                    <label>
                                        <input type="checkbox" name="exit_enabled" value="1" <?php checked($options['exit_popup']['enabled']); ?>>
                                        <?php _e('Activer le pop-up de sortie d\'intention', ELEGANT_POPUPS_TEXT_DOMAIN); ?>
                                    </label>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><?php _e('Contenu', ELEGANT_POPUPS_TEXT_DOMAIN); ?></th>
                                <td>
                                    <?php
                                    wp_editor($options['exit_popup']['content'], 'exit_content', array(
                                        'textarea_name' => 'exit_content',
                                        'media_buttons' => true,
                                        'textarea_rows' => 8,
                                        'teeny' => false,
                                        'tinymce' => true,
                                        'quicktags' => true
                                    ));
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><?php _e('Couleur de fond', ELEGANT_POPUPS_TEXT_DOMAIN); ?></th>
                                <td>
                                    <input type="text" name="exit_bg_color" value="<?php echo esc_attr($options['exit_popup']['background_color']); ?>" class="color-field">
                                    <p class="description"><?php _e('Format RGBA recommandé pour la transparence (ex: rgba(239,68,68,0.1))', ELEGANT_POPUPS_TEXT_DOMAIN); ?></p>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><?php _e('Couleur du texte', ELEGANT_POPUPS_TEXT_DOMAIN); ?></th>
                                <td>
                                    <input type="text" name="exit_text_color" value="<?php echo esc_attr($options['exit_popup']['text_color']); ?>" class="color-picker">
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><?php _e('Taille de police', ELEGANT_POPUPS_TEXT_DOMAIN); ?></th>
                                <td>
                                    <input type="number" name="exit_font_size" value="<?php echo esc_attr($options['exit_popup']['font_size']); ?>" min="10" max="48"> px
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><?php _e('Dimensions', ELEGANT_POPUPS_TEXT_DOMAIN); ?></th>
                                <td>
                                    <label>
                                        <?php _e('Largeur:', ELEGANT_POPUPS_TEXT_DOMAIN); ?>
                                        <input type="number" name="exit_width" value="<?php echo esc_attr($options['exit_popup']['width']); ?>" min="200" max="800"> px
                                    </label>
                                    <br><br>
                                    <label>
                                        <?php _e('Hauteur:', ELEGANT_POPUPS_TEXT_DOMAIN); ?>
                                        <input type="number" name="exit_height" value="<?php echo esc_attr($options['exit_popup']['height']); ?>" min="150" max="600"> px
                                    </label>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            
            <!-- Aperçu -->
            <div id="preview-tab" class="tab-content" style="display: none;">
                <div class="postbox">
                    <div class="postbox-header">
                        <h2 class="hndle"><?php _e('Aperçu des pop-ups', ELEGANT_POPUPS_TEXT_DOMAIN); ?></h2>
                    </div>
                    <div class="inside">
                        <p><?php _e('Cliquez sur les boutons ci-dessous pour prévisualiser vos pop-ups :', ELEGANT_POPUPS_TEXT_DOMAIN); ?></p>
                        
                        <button type="button" id="preview-welcome" class="button button-secondary">
                            <?php _e('Aperçu Pop-up d\'accueil', ELEGANT_POPUPS_TEXT_DOMAIN); ?>
                        </button>
                        
                        <button type="button" id="preview-exit" class="button button-secondary">
                            <?php _e('Aperçu Pop-up de sortie', ELEGANT_POPUPS_TEXT_DOMAIN); ?>
                        </button>
                        
                        <div id="preview-container" style="margin-top: 20px; border: 1px solid #ddd; min-height: 300px; position: relative; background: #f9f9f9;"></div>
                    </div>
                </div>
            </div>
        </div>
        
        <?php submit_button(__('Enregistrer les modifications', ELEGANT_POPUPS_TEXT_DOMAIN)); ?>
    </form>
</div>