# Budget Mobile 💰

Application de gestion de budget conçue pour suivre les dépenses et revenus du couple.

## ✨ Fonctionnalités principales

- 📊 Tableau de bord interactif avec graphiques en temps réel
- 💰 Gestion des revenus par personne
- 💸 Suivi des dépenses communes et individuelles
- 📅 Historique et archivage automatique mensuel

## 🚀 Installation

1. **Prérequis**
   - PHP 8.1+
   - Composer
   - MySQL
   - Node.js & NPM

2. **Installation**
```bash
# Cloner le projet
git clone https://github.com/votre-username/budget-mobile.git
cd budget-mobile

# Installer les dépendances
composer install
npm install

# Configurer l'environnement
cp .env.example .env
php artisan key:generate

# Migrer la base de données
php artisan migrate

# Compiler les assets
npm run dev

# Lancer le serveur
php artisan serve
```

## 🔧 Maintenance

Commande d'archivage automatique :
```bash
php artisan archive:last-month
```

## 🛡️ Sécurité

- ✅ Authentification requise
- ✅ Protection CSRF
- ✅ Validation des données
- ✅ Transactions sécurisées
- ✅ Verrouillage des archives

## 🎨 Personnalisation

- `public/css/history/styles.css` : styles globaux
- `tailwind.config.js` : thèmes Tailwind
- `.env` : configurations

## 📝 Licence

Projet sous licence MIT.
