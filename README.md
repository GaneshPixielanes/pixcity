# Pix.City

## Project architecture

### Symfony

- Pix.City use the latest version of the Symfony framework 
- Doctrine ORM for the database (with annotations)
- Routes are defined in controllers with annotations
- Twig for templating
- Webpack for the assets (Javascript, CSS and static images)
- Only few third party bundles are used :
    - [FroalaEditorBundle](https://github.com/froala/KMSFroalaEditorBundle) for the integration of the Froala Wysiwyg
    - [LiipImagineBundle](https://github.com/liip/LiipImagineBundle) for image size/thumb management
    - [OAuth2ClientBundle](https://github.com/knpuniversity/oauth2-client-bundle) for the Facebook/Google sign in
    - [KnpSnappyBundle](https://github.com/KnpLabs/KnpSnappyBundle) for invoice generation
        - To use KnpSnappyBundle [wkhtmltopdf](https://wkhtmltopdf.org) needs to be installed on the server
    - [RevolutPhpBundle](https://github.com/sverraest/revolut-php-bundle) for bank transfer using [Revolut](https://business.revolut.com)
    - [DdeboerVatinBundle](https://github.com/ddeboer/vatin-bundle) for VAT validation
    
### Directory structure

```
Project
└─── assets
│   ├──── admin // CMS assets
│   └──── front // Website assets
│    
├─── bin // console executable
├─── config // Project and environnement configuration files (YAML)
├─── public // Public root directory
├─── sql // Some database backup, for developpement purpose
├─── src
│   ├──── Constant // Project constants. Basically it's some classes with static variables and functions
│   ├──── Controller
│   │    ├──── Admin // CMS controllers
│   │    ├──── Front // Website controllers
│   │    └──── Api // Some routes only used asynchronously (Ajax) and shared between CMS and Website
│   │
│   ├──── Doctrine // Doctrine specific functions
│   ├──── Entity // Project entities - use Doctrine ORM annotations
│   ├──── Form
│   │    ├──── Admin // CMS form types
│   │    ├──── Front // Website form types
│   │    └──── Shared // Shared for types between CMS and Website
│   │
│   ├──── Migrations // Generated database migration files
│   ├──── Repository // Entities repositories (most DQL queries are centralized here)
│   ├──── Security // User login authenticators and guards
│   ├──── Service // Shared service across the project
│   ├──── Twig // Twig Extensions
│   └──── Utils // Some shared tools across the project 
│   
├─── templates
│   ├──── admin // CMS templates
│   ├──── email // Emails templates
│   └──── front // Front templates
│
└─── translations // some yml files for translations. Mostly used for the CMS and the website forms.
```


## Development environment

### Pre-requisites

- A local MySQL database
- Install [Composer](https://getcomposer.org/) on your local environment.
- Install [NodeJS](https://nodejs.org/en/download/) on your local environment.

### Install the project

1. Pull the repositoryCopy the last backup of the database
 
2. Import the last sql backup (from inside the `sql` folder) 

3. Install the project. `cd` into the project folder then run :

```
composer install
npm install
```

### Run the project on a local server

To start the PHP's built-in web server :

```
php bin/console server:run
```

Start assets watch with [Encore](https://www.npmjs.com/package/@symfony/webpack-encore) :

```
npm run watch
```

Or with `concurrently` both in the same time :

```
concurrently "npm run watch" "php bin/console server:run"
```


### Usefull commands

> To check all the project routes :

```
php bin/console debug:router
```

> If you change the database entities (Doctrine) :

- Backup the database
- Check the mapping with : `php bin/console doctrine:schema:validate`
- Create the diff file with : `php bin/console doctrine:migrations:diff`
- Check the content of the diff file in the `src/Migrations` folder
- Migrate the database with : `php bin/console doctrine:migrations:migrate`

> Clear the cache 

```
php bin/console cache:clear
```

## Miscellaneous


### Renew Instagram Access Token

```
https://www.instagram.com/oauth/authorize/?client_id=83becb285e6b44c18fec01b1473b4e93&redirect_uri=https://elfsight.com/service/generate-instagram-access-token/&response_type=code
```

## Update production

Build the assets with Encore :

```
npm run build
```

If needed, migrate the database on the production server :
```
php bin/console doctrine:migrations:migrate
```

Clear the cache :

```
php bin/console cache:clear
```


## Developpers

### Front-End (HTML/CSS)

Bertrand GONTARD - bertrand@lesindependants.net

### Back-End (PHP)

Adrien LAMOTTE - adrien@lesindependants.net