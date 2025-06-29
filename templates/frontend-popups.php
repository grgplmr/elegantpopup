<?php
if (!defined('ABSPATH')) {
    exit;
}
?>

<!-- Pop-ups Container -->
<div id="elegant-popups-container">
    <!-- Overlay -->
    <div id="elegant-popups-overlay" class="elegant-popups-overlay" aria-hidden="true"></div>
    
    <!-- Pop-up d'accueil -->
    <?php if ($options['welcome_popup']['enabled']): ?>
    <div id="elegant-welcome-popup"
         class="elegant-popup welcome-popup"
         role="dialog"
         aria-labelledby="welcome-popup-title"
         aria-hidden="true"
         data-delay="<?php echo esc_attr($options['welcome_popup']['delay']); ?>"
         data-show-once="<?php echo $options['welcome_popup']['show_once'] ? 'true' : 'false'; ?>"
         style="
            width: <?php echo esc_attr($options['welcome_popup']['width']); ?>px;
            max-width: 90vw;
            height: <?php echo esc_attr($options['welcome_popup']['height']); ?>px;
            max-height: 80vh;
            background: <?php echo esc_attr($options['welcome_popup']['background_color']); ?>;
            color: <?php echo esc_attr($options['welcome_popup']['text_color']); ?>;
            font-size: <?php echo esc_attr($options['welcome_popup']['font_size']); ?>px;
         ">

        <h2 id="welcome-popup-title" class="elegant-popup-title">
            <?php _e("Pop-up d'accueil", ELEGANT_POPUPS_TEXT_DOMAIN); ?>
        </h2>


        <button class="elegant-popup-close" aria-label="<?php _e('Fermer', ELEGANT_POPUPS_TEXT_DOMAIN); ?>" tabindex="1">
            <span aria-hidden="true">&times;</span>
        </button>
        
        <div class="elegant-popup-content" tabindex="0">
            <?php echo wp_kses_post($options['welcome_popup']['content']); ?>
        </div>
    </div>
    <?php endif; ?>
    
    <!-- Pop-up de sortie -->
    <?php if ($options['exit_popup']['enabled']): ?>
    <div id="elegant-exit-popup"
         class="elegant-popup exit-popup"
         role="dialog"
         aria-labelledby="exit-popup-title"
         aria-hidden="true"
         style="
            width: <?php echo esc_attr($options['exit_popup']['width']); ?>px;
            max-width: 90vw;
            height: <?php echo esc_attr($options['exit_popup']['height']); ?>px;
            max-height: 80vh;
            background: <?php echo esc_attr($options['exit_popup']['background_color']); ?>;
            color: <?php echo esc_attr($options['exit_popup']['text_color']); ?>;
            font-size: <?php echo esc_attr($options['exit_popup']['font_size']); ?>px;
         ">
        <h2 id="exit-popup-title" class="elegant-popup-title">
            <?php _e('Pop-up de sortie', ELEGANT_POPUPS_TEXT_DOMAIN); ?>
        </h2>

        <button class="elegant-popup-close" aria-label="<?php _e('Fermer', ELEGANT_POPUPS_TEXT_DOMAIN); ?>" tabindex="1">
            <span aria-hidden="true">&times;</span>
        </button>
        
        <div class="elegant-popup-content" tabindex="0">
            <?php echo wp_kses_post($options['exit_popup']['content']); ?>
        </div>
    </div>
    <?php endif; ?>
</div>