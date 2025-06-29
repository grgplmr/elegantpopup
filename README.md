# Elegant Popups - Plugin WordPress

Un plugin WordPress moderne pour afficher des pop-ups personnalisables avec un design glassmorphism élégant.

## Fonctionnalités

### Types de Pop-ups
- **Pop-up d'accueil** : S'affiche après un délai personnalisable
- **Pop-up de sortie d'intention** : Se déclenche quand l'utilisateur veut quitter la page

### Design
- **Glassmorphism** : Effet de verre moderne avec flou d'arrière-plan
- **Responsive** : Optimisé pour tous les appareils
- **Animations fluides** : Transitions CSS3 élégantes
- **Accessible** : Navigation clavier et lecteurs d'écran

### Personnalisation
- Éditeur WYSIWYG pour le contenu
- Couleurs personnalisables (arrière-plan et texte)
- Taille de police ajustable
- Dimensions personnalisables
- Délai d'apparition configurable
- Affichage unique par visiteur (optionnel)

### Interface d'administration
- Interface intuitive avec onglets
- Aperçu en temps réel
- Validation des formulaires
- Sélecteurs de couleur intégrés

## Installation

### Installation manuelle
1. Téléchargez le dossier `elegant-popups`
2. Placez-le dans `/wp-content/plugins/`
3. Activez le plugin dans l'administration WordPress
4. Accédez à "Elegant Popups" dans le menu admin

### Via l'administration WordPress
1. Allez dans "Extensions" > "Ajouter"
2. Recherchez "Elegant Popups"
3. Installez et activez le plugin

## Configuration

### Pop-up d'accueil
1. Activez le pop-up d'accueil
2. Rédigez votre contenu avec l'éditeur WYSIWYG
3. Personnalisez les couleurs et dimensions
4. Définissez le délai d'apparition (en millisecondes)
5. Choisissez si le pop-up ne doit s'afficher qu'une fois

### Pop-up de sortie d'intention
1. Activez le pop-up de sortie
2. Personnalisez le contenu et l'apparence
3. Le pop-up se déclenchera automatiquement lors de l'intention de sortie

### Aperçu
Utilisez l'onglet "Aperçu" pour voir vos pop-ups en temps réel avant publication.

## Compatibilité

### Thèmes
- **GeneratePress** : Compatibilité complète testée
- **Autres thèmes** : Compatible avec tous les thèmes respectant les standards WordPress

### Éditeurs
- **Gutenberg** : Pleine compatibilité
- **Éditeur classique** : Supporté

### Navigateurs
- Chrome, Firefox, Safari, Edge (versions récentes)
- Support des anciens navigateurs avec fallback

### Appareils
- Desktop : Détection souris
- Mobile : Détection tactile et gestes
- Tablette : Responsive automatique

## Technique

### Structure des fichiers
```
elegant-popups/
├── elegant-popups.php          # Fichier principal
├── templates/                  # Templates PHP
│   ├── admin-page.php         # Interface admin
│   └── frontend-popups.php    # Pop-ups frontend
├── assets/                    # Ressources
│   ├── css/
│   │   ├── frontend.css       # Styles frontend  
│   │   └── admin.css          # Styles admin
│   └── js/
│       ├── frontend.js        # JavaScript frontend
│       └── admin.js           # JavaScript admin
├── languages/                 # Traductions
│   ├── elegant-popups.pot     # Template de traduction
│   └── elegant-popups-fr_FR.po # Traduction française
└── README.md                  # Documentation
```

### Base de données
Les options sont stockées dans `wp_options` sous la clé `elegant_popups_options` :

```php
array(
    'welcome_popup' => array(
        'enabled' => true,
        'content' => 'Contenu HTML',
        'background_color' => 'rgba(255, 255, 255, 0.1)',
        'text_color' => '#1f2937',
        'font_size' => '16',
        'width' => '400',
        'height' => '300',
        'delay' => '3000',
        'show_once' => true
    ),
    'exit_popup' => array(
        'enabled' => true,
        'content' => 'Contenu HTML',
        'background_color' => 'rgba(239, 68, 68, 0.1)',
        'text_color' => '#1f2937',
        'font_size' => '16',
        'width' => '400',
        'height' => '250'
    )
)
```

### Hooks disponibles

#### Actions
```php
// Avant l'affichage des pop-ups
do_action('elegant_popups_before_render');

// Après l'affichage des pop-ups  
do_action('elegant_popups_after_render');
```

#### Filtres
```php
// Modifier les options avant affichage
$options = apply_filters('elegant_popups_options', $options);

// Modifier le contenu d'un pop-up
$content = apply_filters('elegant_popups_content', $content, $type);

// Modifier les styles CSS
$css = apply_filters('elegant_popups_css', $css);
```

### API JavaScript
```javascript
// Forcer l'affichage d'un pop-up
window.ElegantPopups.forceShowPopup('welcome');
window.ElegantPopups.forceShowPopup('exit');

// Fermer tous les pop-ups
window.ElegantPopups.closeAllPopups();

// Réinitialiser le pop-up d'accueil
window.ElegantPopups.resetWelcomePopup();

// Événements personnalisés
window.addEventListener('elegantPopupShown', function(e) {
    console.log('Pop-up affiché:', e.detail.type);
});

window.addEventListener('elegantPopupClosed', function(e) {
    console.log('Pop-up fermé');
});
```

## Sécurité

### Mesures implémentées
- Échappement de toutes les sorties
- Validation et assainissement des entrées
- Vérification des nonces
- Vérification des capacités utilisateur
- Protection contre l'accès direct aux fichiers

### Bonnes pratiques
- Aucune donnée personnelle collectée
- Aucun tracking externe
- Code respectant les standards WordPress
- Traductions sécurisées

## Performance

### Optimisations
- Les fichiers CSS et JS fournis ne sont pas minifiés par défaut (à minifier pour la production)
- Chargement conditionnel (admin/frontend)
- Pas de jQuery requis en frontend
- Images optimisées via Pexels
- Cache des options

### Métriques
- Taille CSS : ~5,5KB par fichier (non minifié)
- Taille JS : ~12KB par fichier (non minifié)
- Impact performance : Minimal
- Temps de chargement : <100ms

## Désinstallation

### Automatique
Le plugin nettoie automatiquement :
- Options de la base de données
- Caches WordPress
- Données temporaires

### Manuelle
Si nécessaire, supprimez manuellement :
```sql
DELETE FROM wp_options WHERE option_name = 'elegant_popups_options';
DELETE FROM wp_options WHERE option_name = 'elegant_popups_version';
```

## Support et développement

### Bugs et suggestions
Créez une issue sur le repository GitHub ou contactez le support.

### Contribution
Les contributions sont les bienvenues ! Consultez les guidelines de contribution.

### Versions
- **1.0.0** : Version initiale
  - Pop-up d'accueil et de sortie
  - Design glassmorphism
- **1.0.1** : Corrections diverses
  - Prise en compte des modifications de texte pour les pop-ups
  - Interface d'administration complète
  - Support multilingue

## Licence

GPL v2 ou ultérieure. Voir le fichier LICENSE pour plus de détails.

## Crédits

Développé avec ❤️ pour la communauté WordPress.