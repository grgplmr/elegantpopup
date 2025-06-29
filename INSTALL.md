# Guide d'installation - Elegant Popups

## Prérequis

### Serveur
- PHP 7.4 ou supérieur
- WordPress 5.0 ou supérieur
- MySQL 5.6 ou supérieur

### Recommandations
- PHP 8.0+ pour de meilleures performances
- HTTPS activé
- Cache WordPress (WP Rocket, W3 Total Cache, etc.)

## Installation

### Méthode 1 : Via l'administration WordPress (Recommandée)

1. **Connexion à l'administration**
   - Connectez-vous à votre tableau de bord WordPress
   - Allez dans "Extensions" → "Ajouter"

2. **Upload du plugin**
   - Cliquez sur "Téléverser une extension"
   - Choisissez le fichier `elegant-popups.zip`
   - Cliquez sur "Installer maintenant"

3. **Activation**
   - Cliquez sur "Activer l'extension"
   - Le plugin est maintenant actif !

### Méthode 2 : Via FTP

1. **Préparation des fichiers**
   - Extrayez le dossier `elegant-popups` du fichier ZIP
   - Connectez-vous à votre serveur via FTP

2. **Upload des fichiers**
   ```
   /votre-site/wp-content/plugins/elegant-popups/
   ```

3. **Activation**
   - Allez dans "Extensions" → "Extensions installées"
   - Trouvez "Elegant Popups" et cliquez sur "Activer"

### Méthode 3 : Via WP-CLI

```bash
# Upload et activation en une commande
wp plugin install elegant-popups.zip --activate

# Ou si vous avez déjà uploadé les fichiers
wp plugin activate elegant-popups
```

## Configuration initiale

### 1. Accès au menu d'administration

Après activation, vous trouverez "Elegant Popups" dans le menu principal de l'administration WordPress.

### 2. Configuration du pop-up d'accueil

1. **Activation**
   - Cochez "Activer le pop-up d'accueil"

2. **Contenu**
   - Utilisez l'éditeur WYSIWYG pour créer votre message
   - Ajoutez des liens, images, formatage selon vos besoins

3. **Apparence**
   ```
   Couleur de fond : rgba(255, 255, 255, 0.1)
   Couleur du texte : #1f2937
   Taille de police : 16px
   Largeur : 400px
   Hauteur : 300px
   ```

4. **Comportement**
   - Délai : 3000ms (3 secondes)
   - Affichage unique : Activé (recommandé)

### 3. Configuration du pop-up de sortie

1. **Activation**
   - Cochez "Activer le pop-up de sortie d'intention"

2. **Personnalisation**
   - Créez un message accrocheur pour retenir les visiteurs
   - Utilisez des couleurs plus vives pour attirer l'attention

3. **Exemple de configuration**
   ```
   Couleur de fond : rgba(239, 68, 68, 0.1)
   Couleur du texte : #1f2937
   Largeur : 450px
   Hauteur : 250px
   ```

### 4. Test et aperçu

1. **Aperçu en administration**
   - Utilisez l'onglet "Aperçu" pour tester vos pop-ups
   - Vérifiez l'apparence sur différentes tailles d'écran

2. **Test en conditions réelles**
   - Ouvrez votre site en navigation privée
   - Testez le pop-up d'accueil (attendez le délai)
   - Testez le pop-up de sortie (bougez la souris vers la barre d'adresse)

## Optimisation

### Performance

1. **Cache**
   - Videz le cache de votre site après installation
   - Le plugin est optimisé et n'impacte pas les performances

2. **Minification**
   - Les fichiers CSS/JS sont déjà optimisés
   - Compatible avec les plugins de minification

### SEO

1. **Indexation**
   - Les pop-ups n'affectent pas le référencement
   - Le contenu reste accessible aux moteurs de recherche

2. **Core Web Vitals**
   - Chargement asynchrone
   - Pas d'impact sur le CLS (Cumulative Layout Shift)

## Intégration avec les thèmes

### GeneratePress (Testé et approuvé)

```php
// Aucune configuration supplémentaire requise
// Compatibilité native complète
```

### Autres thèmes

La plupart des thèmes fonctionnent sans configuration. Si vous rencontrez des problèmes :

1. **Conflits CSS**
   ```css
   /* Ajoutez ce CSS dans votre thème si nécessaire */
   .elegant-popup {
       z-index: 999999 !important;
   }
   ```

2. **Conflits JavaScript**
   - Vérifiez la console du navigateur
   - Contactez le support si nécessaire

## Dépannage

### Problèmes courants

1. **Le pop-up ne s'affiche pas**
   - Vérifiez que le plugin est activé
   - Vérifiez que les pop-ups sont activés dans la configuration
   - Videz le cache du navigateur et du site

2. **Problème de style**
   - Vérifiez les conflits CSS avec le thème
   - Utilisez l'inspecteur web pour identifier les problèmes

3. **Le pop-up s'affiche trop souvent**
   - Vérifiez l'option "Affichage unique"
   - Videz le localStorage du navigateur pour tester

### Logs et débogage

1. **Activer le mode débogage WordPress**
   ```php
   // Dans wp-config.php
   define('WP_DEBUG', true);
   define('WP_DEBUG_LOG', true);
   ```

2. **Vérifier les logs**
   ```
   /wp-content/debug.log
   ```

3. **Console navigateur**
   - Ouvrez F12 → Console
   - Recherchez les erreurs JavaScript

## Migration et sauvegarde

### Sauvegarde des paramètres

```sql
-- Exporter les options
SELECT * FROM wp_options WHERE option_name = 'elegant_popups_options';
```

### Migration vers un autre site

1. **Export**
   - Utilisez un plugin de migration WordPress
   - Ou exportez manuellement les options de la base de données

2. **Import**
   - Installez le plugin sur le nouveau site
   - Importez les paramètres

## Mise à jour

### Automatique
- Les mises à jour automatiques sont gérées par WordPress
- Vos paramètres sont préservés lors des mises à jour

### Manuelle
1. Désactivez l'ancien plugin (ne pas supprimer)
2. Uploadez la nouvelle version
3. Réactivez le plugin

## Support

### Avant de contacter le support

1. **Vérifications**
   - Plugin à jour ?
   - WordPress à jour ?
   - Thème compatible ?
   - Conflits avec d'autres plugins ?

2. **Informations à fournir**
   - Version de WordPress
   - Version du plugin
   - Nom du thème
   - Liste des plugins actifs
   - Message d'erreur exact

### Canaux de support

- GitHub Issues (bugs et suggestions)
- Documentation en ligne
- Email support (premium)

## Désinstallation propre

### Via l'administration WordPress

1. Désactivez le plugin
2. Supprimez-le via "Extensions" → "Extensions installées"
3. Toutes les données sont automatiquement nettoyées

### Vérification manuelle

Si vous voulez vous assurer que tout est supprimé :

```sql
-- Vérifier qu'aucune donnée ne reste
SELECT * FROM wp_options WHERE option_name LIKE '%elegant_popups%';
```

Le résultat devrait être vide après désinstallation.

---

**Installation terminée avec succès !** 🎉

Votre plugin Elegant Popups est maintenant prêt à augmenter l'engagement de vos visiteurs avec de magnifiques pop-ups glassmorphism.