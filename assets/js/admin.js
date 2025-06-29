/**
 * Elegant Popups - JavaScript Administration
 * Interface d'administration avec aperçu en temps réel
 */

jQuery(document).ready(function($) {
    'use strict';
    
    // Initialisation
    ElegantPopupsAdmin.init();
});

const ElegantPopupsAdmin = {
    
    // Variables
    currentTab: 'welcome',
    previewContainer: null,
    
    // Initialisation
    init() {
        this.setupTabs();
        this.setupColorPickers();
        this.setupPreview();
        this.setupFormValidation();
        this.bindEvents();
    },
    
    // Configuration des onglets
    setupTabs() {
        jQuery('.nav-tab').on('click', (e) => {
            e.preventDefault();
            const tab = jQuery(e.target).data('tab');
            this.switchTab(tab);
        });
        
        // Onglet actif par défaut
        this.switchTab('welcome');
    },
    
    // Changement d'onglet
    switchTab(tab) {
        // Mettre à jour la navigation
        jQuery('.nav-tab').removeClass('nav-tab-active');
        jQuery('.nav-tab[data-tab="' + tab + '"]').addClass('nav-tab-active');
        
        // Afficher le bon contenu
        jQuery('.tab-content').hide();
        jQuery('#' + tab + '-tab').show();
        
        this.currentTab = tab;
    },
    
    // Configuration des sélecteurs de couleur
    setupColorPickers() {
        // Couleurs hex
        jQuery('.color-picker').wpColorPicker({
            change: (event, ui) => {
                this.updatePreview();
            }
        });
        
        // Validation des couleurs RGBA
        jQuery('.color-field').on('input', function() {
            const value = jQuery(this).val();
            const isValid = ElegantPopupsAdmin.validateRGBA(value);
            
            if (isValid) {
                jQuery(this).removeClass('invalid');
            } else {
                jQuery(this).addClass('invalid');
            }
            
            ElegantPopupsAdmin.updatePreview();
        });
    },
    
    // Validation RGBA
    validateRGBA(value) {
        const rgbaRegex = /^rgba?\(\s*(\d+)\s*,\s*(\d+)\s*,\s*(\d+)\s*(?:,\s*(0|1|0?\.\d+))?\s*\)$/;
        const hexRegex = /^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/;
        
        return rgbaRegex.test(value) || hexRegex.test(value);
    },
    
    // Configuration de l'aperçu
    setupPreview() {
        this.previewContainer = jQuery('#preview-container');
        
        jQuery('#preview-welcome').on('click', () => {
            this.showPreview('welcome');
        });
        
        jQuery('#preview-exit').on('click', () => {
            this.showPreview('exit');
        });
    },
    
    // Affichage de l'aperçu
    showPreview(type) {
        const data = this.getFormData(type);
        const popup = this.createPreviewPopup(data, type);
        
        this.previewContainer.empty().append(popup);
        
        // Animation d'entrée
        setTimeout(() => {
            popup.addClass('active');
        }, 100);
    },
    
    // Création du pop-up d'aperçu
    createPreviewPopup(data, type) {
        const popup = jQuery('<div>', {
            class: 'preview-popup elegant-popup ' + type + '-popup',
            css: {
                width: data.width + 'px',
                height: data.height + 'px',
                background: data.background_color,
                color: data.text_color,
                fontSize: data.font_size + 'px',
                maxWidth: '90%',
                maxHeight: '80%',
                position: 'relative',
                transform: 'none',
                top: 'auto',
                left: 'auto'
            }
        });
        
        // Bouton de fermeture
        const closeBtn = jQuery('<button>', {
            class: 'close-btn',
            html: '&times;',
            click: () => popup.fadeOut()
        });
        
        // Contenu avec taille de police forcée
        const content = jQuery('<div>', {
            class: 'elegant-popup-content',
            html: data.content,
            css: {
                fontSize: data.font_size + 'px !important',
                maxHeight: '100%',
                overflowY: 'auto'
            }
        });
        
        // Forcer la taille de police sur tous les éléments enfants
        content.find('*').css('fontSize', data.font_size + 'px');
        
        popup.append(closeBtn, content);
        
        return popup;
    },
    
    // Récupération des données du formulaire
    getFormData(type) {
        const prefix = type === 'welcome' ? 'welcome_' : 'exit_';
        
        return {
            content: this.getEditorContent(type + '_content'),
            background_color: jQuery('input[name="' + prefix + 'bg_color"]').val(),
            text_color: jQuery('input[name="' + prefix + 'text_color"]').val(),
            font_size: jQuery('input[name="' + prefix + 'font_size"]').val(),
            width: jQuery('input[name="' + prefix + 'width"]').val(),
            height: jQuery('input[name="' + prefix + 'height"]').val()
        };
    },
    
    // Récupération du contenu de l'éditeur
    getEditorContent(editorId) {
        if (typeof tinyMCE !== 'undefined' && tinyMCE.get(editorId)) {
            return tinyMCE.get(editorId).getContent();
        }
        return jQuery('#' + editorId).val();
    },
    
    // Mise à jour automatique de l'aperçu
    updatePreview() {
        if (this.previewContainer.find('.preview-popup').length > 0) {
            setTimeout(() => {
                if (this.currentTab === 'preview') {
                    const activeType = this.previewContainer.find('.welcome-popup').length > 0 ? 'welcome' : 'exit';
                    this.showPreview(activeType);
                }
            }, 300);
        }
    },
    
    // Configuration de la validation des formulaires
    setupFormValidation() {
        // Validation des nombres
        jQuery('input[type="number"]').on('input', function() {
            const min = parseInt(jQuery(this).attr('min'));
            const max = parseInt(jQuery(this).attr('max'));
            const value = parseInt(jQuery(this).val());
            
            if (value < min) {
                jQuery(this).val(min);
            } else if (value > max) {
                jQuery(this).val(max);
            }
        });
        
        // Validation avant soumission
        jQuery('form').on('submit', (e) => {
            return this.validateForm();
        });
    },
    
    // Validation du formulaire
    validateForm() {
        let isValid = true;
        const errors = [];
        
        // Vérifier les couleurs RGBA
        jQuery('.color-field').each(function() {
            const value = jQuery(this).val();
            if (value && !ElegantPopupsAdmin.validateRGBA(value)) {
                isValid = false;
                errors.push('Format de couleur invalide: ' + value);
            }
        });
        
        // Vérifier les dimensions
        jQuery('input[name$="_width"], input[name$="_height"]').each(function() {
            const value = parseInt(jQuery(this).val());
            if (value < 150 || value > 1000) {
                isValid = false;
                errors.push('Dimensions invalides (150-1000px requis)');
            }
        });
        
        if (!isValid) {
            alert('Erreurs de validation:\n' + errors.join('\n'));
        }
        
        return isValid;
    },
    
    // Liaison des événements
    bindEvents() {
        // Mise à jour de l'aperçu en temps réel
        jQuery('input, textarea, select').on('input change', () => {
            clearTimeout(this.updateTimeout);
            this.updateTimeout = setTimeout(() => {
                this.updatePreview();
            }, 500);
        });
        
        // Gestion des éditeurs TinyMCE
        if (typeof tinyMCE !== 'undefined') {
            jQuery(document).on('tinymce-editor-init', (event, editor) => {
                editor.on('change keyup', () => {
                    this.updatePreview();
                });
            });
        }
        
        // Copier les paramètres entre pop-ups
        jQuery('.copy-settings').on('click', function() {
            const from = jQuery(this).data('from');
            const to = jQuery(this).data('to');
            ElegantPopupsAdmin.copySettings(from, to);
        });
        
        // Reset des paramètres
        jQuery('.reset-settings').on('click', function() {
            const type = jQuery(this).data('type');
            if (confirm('Êtes-vous sûr de vouloir réinitialiser ces paramètres ?')) {
                ElegantPopupsAdmin.resetSettings(type);
            }
        });
    },
    
    // Copie des paramètres
    copySettings(from, to) {
        const fromPrefix = from + '_';
        const toPrefix = to + '_';
        
        // Copier les valeurs communes
        const commonFields = ['bg_color', 'text_color', 'font_size', 'width', 'height'];
        
        commonFields.forEach(field => {
            const fromValue = jQuery('input[name="' + fromPrefix + field + '"]').val();
            jQuery('input[name="' + toPrefix + field + '"]').val(fromValue);
        });
        
        // Copier le contenu de l'éditeur
        const fromContent = this.getEditorContent(from + '_content');
        if (typeof tinyMCE !== 'undefined' && tinyMCE.get(to + '_content')) {
            tinyMCE.get(to + '_content').setContent(fromContent);
        } else {
            jQuery('#' + to + '_content').val(fromContent);
        }
        
        this.updatePreview();
        alert('Paramètres copiés avec succès !');
    },
    
    // Reset des paramètres
    resetSettings(type) {
        const defaults = {
            welcome: {
                content: 'Bienvenue sur notre site ! <a href="#" style="color: #3b82f6;">Découvrez nos offres</a>',
                bg_color: 'rgba(255, 255, 255, 0.1)',
                text_color: '#1f2937',
                font_size: '16',
                width: '400',
                height: '300',
                delay: '3000'
            },
            exit: {
                content: 'Attendez ! Ne partez pas sans découvrir <a href="#" style="color: #ef4444;">nos promotions exclusives</a>',
                bg_color: 'rgba(239, 68, 68, 0.1)',
                text_color: '#1f2937',
                font_size: '16',
                width: '400',
                height: '250'
            }
        };
        
        const settings = defaults[type];
        if (!settings) return;
        
        Object.keys(settings).forEach(key => {
            const fieldName = type + '_' + key;
            const field = jQuery('input[name="' + fieldName + '"]');
            
            if (key === 'content') {
                if (typeof tinyMCE !== 'undefined' && tinyMCE.get(type + '_content')) {
                    tinyMCE.get(type + '_content').setContent(settings[key]);
                } else {
                    jQuery('#' + type + '_content').val(settings[key]);
                }
            } else {
                field.val(settings[key]);
            }
        });
        
        this.updatePreview();
        alert('Paramètres réinitialisés !');
    }
};

// Styles CSS pour l'administration
jQuery(document).ready(function($) {
    // Ajouter des styles pour les champs invalides
    $('<style>')
        .prop('type', 'text/css')
        .html(`
            .color-field.invalid {
                border-color: #d63638 !important;
                box-shadow: 0 0 0 1px #d63638 !important;
            }
            .preview-popup.active {
                opacity: 1;
                transform: scale(1);
            }
            .preview-popup * {
                font-size: inherit !important;
            }
        `)
        .appendTo('head');
});