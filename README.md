# Projet soutenance

## Guide d'utilisation de git

### Récupération du projet

Dans un dossier vide, faire cette commande pour récupérer le projet :

```
git clone https://github.com/teikaro/CS-Coaching.git
cd CS-Coaching
```

<br>

### Début de la session de travail

Au début de la session de travail, on ouvre son cmder dans le dossier de travail, on vérifie qu'on est bien sur la branche main/master, puis on fait un **git pull** pour rapatrier le travail qui a été fait entre temps.

<br>

### Partie travail

On crée une branche qui descend de 'main', puis on se place dessus pour travailler. Si elle existe déjà, on se place dessus. Pour faire cela on utilise différentes commandes :

- **git branch** permet de voir les différentes branches (en local). Celle avec l'étoile est la branche sur laquelle on est. <br>
- **git branch nom_de_la_branche** permet de créer une branche. <br>
- **git checkout nom_de_la_branche** permet de se déplacer entre les branches.
- **git branch -d nom_de_la_branche** permet de supprimer la branche lorsque nous ne sommes pas dessus.

```
git branch develop-prenom // Création de la branche
git checkout develop-prenom // Déplacement sur la branche develop
```

<br>

### Partie commit

Une fois notre tâche accomplie on peut faire un commit, et envoyer notre branche develop-nom-user sur le dépôt distant. Pour ce faire on utilise les commandes suivantes :

- **git status** pour contrôler les fichiers ayant subi des modifications, ainsi que les fichiers supprimés ou ajoutés.<br>
- **git add .** pour préparer les fichiers au commit.<br>
- **git commit -m "message"** pour faire le commit avec un message.

<br>

### Partie verification

Pour vérifier si son travail est viable dans la version actuelle du projet (pour rappel avec le git pull sur la branche main/master, vous mettez à jour votre dépôt local), on doit fusionner sa branche main sur sa branche develop-nom. Et vérifier que tout le travail s'intègre bien.<br>
Pour se faire on s'assurera d'être sur la branche develop-nom et on entrera cette commande :

- **git merge main**

Ça aura pour effet de fusionner les deux branches. Il faudra ensuite tester que les nouvelles fonctionnalités que vous avez codées s'intègre bien dans le projet. Alors seulement, vous pourrez pousser votre travail sur le dépôt distant.

<br>

### Partie push

Si vous voulez envoyer votre branche *develop-nom* pour la première fois, il faudra taper une commande spéciale :

- **git push --set-upstream origin develop-nom-user**

Sinon :

- **git push** pour envoyer le contenu de votre branche sur le dépôt distant.

<br>

## Activer le projet symfony récupéré

!!! Attention !!! - depuis samedi, après avoir créé un symfony neuf, vous pouvez rencontrer des soucis lors de la création d'entité, au moment d'ajouter un nouveau champ une erreur apparaît (lorsque l'on demande de voir les différents types avec "**?**" aussi.) :

```
In MakeEntity.php line 379:
  Undefined constant Doctrine\DBAL\Types\Type::JSON_ARRAY
```

<br>

Apparemment, le type json_array est déprécié, mais cela depuis la version 2.6 de doctrine : [voir la doc ici](https://www.doctrine-project.org/projects/doctrine-dbal/en/latest/reference/types.html#json-array)

Notre erreur intervient lorsque dans symfony\Bundle\makerBundle\Maker\MakeEntity.php la fonction unset est appelée, et ce, à plusieurs endroits, l.379 dans la méthode "**askForNextField**" et l.433 dans la méthode "**printAvailableTypes**" : 

```
unset($allTypes[Type::JSON_ARRAY]);
```

Pour régler le problème, j'ai choisis de la commenter. Cependant, les vendors n'étant pas sur Git, il va falloir faire cette manipulation dès le moment que l'on souhaite ajouter une nouvelle propriété.

<br>

Après avoir récupéré le projet via un **git pull** ou **git clone**, il faudra taper les commandes suivantes :

```
composer install
symfony console doctrine:database:create
```

<br>

## Installation de symfony (Ne pas refaire !)

Procédure suivie pour l'installation de symfony

```
1. symfony new ryl-scope-3 --full

2. Changement dans .env :
   DATABASE_URL="mysql://root:@127.0.0.1:3306/rylscope3?serverVersion=5.7"

3. symfony console doctrine:database:create
```

<br>

## Création du contrôleur

```
4. symfony console make:controller MainController

5. modifier la route par un simple "/" et le name par "main_" dans MainController.php
```

<br>

## Création des entités

Pour User :

```
symfony console make:user 

Nom de l'entité > User

Est-ce que l'on stock les infos dans la BDD > yes

La propriété utilisée > email

Voulez vous hash les mot de passe utilisateurs > yes
```

<br>

Mise à jour des données de User :
```
symfony console make:entity User

propriété de nom -> lastName
    type: string
    longueur: 50
    null: no
    
propriété de prénom -> firstName
    type: string
    longueur: 50
    null: no
    
propriété du téléphone -> phone
    type: string
    longueur: 15
    null: yes
    
propriété date de souscription -> registeredAt
    type: datetime
    null: no
    
propriété date d'update -> updatedAt
    type: datetime
    null: no
```

<br>

Pour Service :

```
symfony console make:entity Service

propriété de nom -> name
    type: string
    longueur: 100
    null: no
    
propriété de secteur -> sector
    type: string
    longueur: 50
    null: no
    
propriété de description -> description
    type: text
    null: no
    
propriété date de création -> createdAt
    type: datetime
    null: no
    
propriété date d'update -> updatedAt
    type: datetime
    null: no
```

<br>

Pour Project :

```
symfony console make:entity Project

propriété de nom -> name
    type: string
    longueur: 100
    null: no
    
propriété de secteur -> sector
    type: string
    longueur: 50
    null: no
    
propriété de description -> description
    type: text
    null: no
    
propriété date de création -> createdAt
    type: datetime
    null: no
    
propriété date d'update -> updatedAt
    type: datetime
    null: no
```

<br>

Pour SocialNetwork :

```
symfony console make:entity SocialNetwork

propriété de nom -> name
    type: string
    longueur: 20
    null: no
    
propriété de l'adresse URL -> address
    type: string
    longueur: 255
    null: no
    
propriété date de création -> createdAt
    type: datetime
    null: no
    
propriété date d'update -> updatedAt
    type: datetime
    null: no
```

<br>

Pour Photo :

```
symfony console make:entity Photo

propriété de titre -> title
    type: string
    longueur: 50
    null: no
    
propriété date de création -> createdAt
    type: datetime
    null: no
    
propriété date d'update -> updatedAt
    type: datetime
    null: no
```

<br>

## Remplissage de la base de données

```
symfony console make:migration
symfony console doctrine:migrations:migrate
```

<br>

## Mise en place des relations

Pour la relation *User* 0-N **socialNetworks** 0-N *SocialNetwork* :

```
property name -> socialNetworks
    type > relation
    entity bind > SocialNetwork
    relation type > ManyToMany
    
    Add property to SocialNetwork: yes
    New field name inside SocialNetwork: users
```

<br>

Pour la relation *User* 0-1 **photos** 1-1 *Photo* :

```
property name -> photos
    type > relation
    entity bind > Photo
    relation type > OneToOne
    
    Allow to be null: yes
    Add property to Photo: yes
    New field name inside Photo: user
```

<br>

Pour la relation *User* 0-N **proposeServices** 1-1 *Service* :

```
property name -> proposeServices
    type > relation
    entity bind > Service
    relation type > ManyToOne

    Allow to be null: yes
    Add property to Service: yes
    New field name inside Service: users
```

<br>

Pour la relation *User* 0-N **presentProjects** 1-1 *Project* :

```
property name -> presentProjects
    type > relation
    entity bind > Project
    relation type > ManyToOne
    
    Allow to be null: yes
    Add property to Project: yes
    New field name inside Project: users
```


## Installation du système d'authentification

### Installation du bundle Security et paramétrage

```
symfony console make:auth
    what style of authentication > Login form authentication
    class name of the authenticator > LoginformAuthenticator
    class name for the controller > SecurityController
    want to generate a '/logout' URL > yes
```

<br>

Après la création du système d'authentification, il faut faire quelques modifications :

Dans le contrôleur SecurityController :

On décommente et on redirige sur 'home' si l'utilisateur est déjà connecté (dans la méthode **login**) :

```
if ($this->getUser()) {
     return $this->redirectToRoute('main_home');
 }
```

<br>

Dans le LoginFormAuthenticator :

On règle la redirection lorsque l'utilisateur se connecte (Dans la méthode **onAuthenticationSuccess**) :

```
return new RedirectResponse($this->urlGenerator->generate('main_home'));
```

<br>

Dans le Security.yaml :

On décommente et on redirige sur la page home lors de la déconnexion :

```
logout:
    path: app_logout
    # where to redirect after logout
    target: main_home
```

<br>

Il faut aussi changer la langue des messages générés par le bundle, dans translation.yaml :

```
framework:
    default_locale: fr
    translator:
        enabled_locales: ['fr']
        default_path: '%kernel.project_dir%/translations'
        fallbacks:
            - fr
```

<br>

### Mise en place d'une limitation aux tentatives de connexion

Pour se faire il faut installer le composant rate-limiter :

```
composer require symfony/rate-limiter
```

<br>

Puis on ajoute la limitation :

```
main:
    # On limite à 5 essaies et 15 minutes de délai avant de réessayer
    login_throttling:
        max_attempts: 5
        interval: '15 minutes'
```

<br>

### Paramétrage de la page de connexion

Dans login.html.twig :

Renommage de l'url de la page et modifications mineures de l'interface (texte du titre de la page, des boutons et champs, etc.) :

```php
@Route("/connexion/", name="app_login")
```

<br>

### Paramétrage de la page d'inscription

Dans register.html.twig :

Modifications mineures de l'interface (texte du titre de la page, des boutons et champs, etc.).

<br>

### Création du formulaire d'inscription

```
symfony console make:registration-form
    add a @UniqueEntity > yes
    send email to verify > no
    automatically authenticate > no
    redirect after connection > home
```

<br>

### Modification du formulaire d'inscription

Dans RegistrationFormType.php :

```php
->add('email', EmailType::class)
->add('firstName', TextType::class)
->add('lastName', TextType::class)
->add('phone', TextType::class)
    // TODO : Porblème avec le regex en attribue côté entité donc ici pour le moment.
->add('plainPassword', RepeatedType::class, [
    'type' => PasswordType::class,
    'invalid_message' => 'Le mot de passe doit être identique.',
    'mapped' => false,
    'constraints' => [
        new NotBlank([
            'message' => 'Merci de renseigner un mot de passe.',
        ]),
        new Regex([
            'pattern' => '/(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[ !\"\#\$%&\'\(\)*+,\-.\/:;<=>?@[\\^\]_`\{|\}~])^.{8,4096}$/',
            'message' => 'Votre mot de passe doit contenir au moins 8 caractères, dont obligatoirement une minuscule, une majuscule, un chiffre et un caractère spécial !',
        ],)
    ],
])
```

<br>

!!! Attention : Pour le moment le système d'attribut ne fonctionne pas avec tout, y compris le User du SecurityBundle, donc certaines choses resteront en annotations et d'autres en attribut, voir aussi dans le formulaire.

Mise en place de l'email unique sur le SecurityController :

```php
/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(fields={"email"}, message="L'email que vous avez renseigner exist déjà.")
 */
```

<br>

Mise en place des contraintes sur les propriétés de l'entité, dans User.php (et suppression des contraintes du RegistrationFormType) :

Pour l'email :

```php
/**
 * @ORM\Column(name="email", type="string", length=180, unique=true)
 */
#[Assert\NotBlank(
    message: 'Merci de renseigner une adresse Email !',
)]
#[Assert\Email(
    message: 'L\'adresse Email {{ value }} n\'est pas valide !',
)]
private $email;
```

<br>

!!! Attention : Le regex en attribut ne fonctionne pas pour le moment, il est donc placer dans le formulaire.

Pour le password :

```php
/**
 * @var string The hashed password
 * @ORM\Column(type="string")
 */
#[Assert\Length(
    min: 8,
    max: 4096,
    minMessage: 'Votre mot de passe doit faire au moins {{ limit }} caractères',
    maxMessage: 'Mot de passe trop grand !',
)]
private $password;
```

<br>

Pour le lastName :

```php
/**
 * @ORM\Column(type="string", length=50)
 */
#[Assert\NotBlank(
    message: 'Merci de renseigner un nom.',
)]
#[Assert\Length(
    min: 2,
    max: 50,
    minMessage: 'Le nom doit faire au moins {{ limit }} caractères.',
    maxMessage: 'Le nom ne peut dépasser {{ limit }} caractères.',
)]
#[Assert\Regex(
    pattern: '/[A-Za-z]/',
    message: 'Le nom ne peut contenir que des lettres.',
)]
private $lastName;
```

<br>

Pour le firstName :

```php
/**
 * @ORM\Column(type="string", length=50)
 */
#[Assert\NotBlank(
    message: 'Merci de renseigner un prénom.',
)]
#[Assert\Length(
    min: 2,
    max: 50,
    minMessage: 'Le prénom doit faire au moins {{ limit }} caractères.',
    maxMessage: 'Le prénom ne peut dépasser {{ limit }} caractères.',
)]
#[Assert\Regex(
    pattern: '/[A-Za-z]/',
    message: 'Le prénom ne peut contenir que des lettres.',
)]
private $firstName;
```

<br>

Pour le phone :

```php
/**
 * Facultatif
 *
 * @ORM\Column(type="string", length=15, nullable=true)
 */
// TODO : Note pour STAGE, max 15 caractères, considérer les numéros étrangers (min et max)
// Le numéro est au format 10 chiffres actuellement
#[Assert\Length(
    min: 10,
    max: 10,
    minMessage: 'Le numéro de téléphone doit contenir {{ 10 }} chiffres.',
    maxMessage: 'Le numéro de téléphone ne peut dépasser {{ limit }} caractères.',
)]
#[Assert\Regex(
    pattern: '/[0-9]/',
    message: 'Le numéro de téléphone ne peut contenir que des chiffres.',
)]
private $phone;
```


<br>

Dans RegistrationController :

On ajoute une redirection si l'utilisateur est déjà connecté :

```php
if($this->getUser()) {
    return $this->redirectToRoute('main_home');
}
```

<br>

Ajout du thème Bootstrap pour les formulaires dans twig.yaml :

```yaml
form_themes: ['bootstrap_5_layout.html.twig']
```

<br>

### Mise en place des rôles

Dans Security.yaml :

```
role_hierarchy:
    ROLE_SUPER_ADMIN:   ROLE_ADMIN
    ROLE_ADMIN:         [ROLE_MANAGER, ROLE_CLIENT, ROLE_CONSULTANT]
    ROLE_MANAGER:       ROLE_USER
    ROLE_CLIENT:        ROLE_USER
    ROLE_CONSULTANT:    ROLE_USER
```

<br>

Pour expliquer cette hiérarchie :

- SUPER_ADMIN > ADMIN > MANAGER, CLIENT, CONSULTANT > USER
- L***admin*** (administrateur principal) a accès à l'interface d'administration et peut donner les roles ***manager***, ***client*** et ***consultant***.
- Le ***manager*** a accès à l'interface d'administration et peut donner les rôles ***client*** et ***consultant***.
- Le ***client*** à accès à un profil public, à l'interface client, et au catalogue de prestation.
- Le ***consultant*** à accès à un profil public, à l'interface consultant, et au catalogue de projet.
- Le ***user*** à accès à son compte sans le profil public.

<br>

## Ajout de message flash lors de la création de compte

On ajoute dans RegistrationController avant la redirection :

```php
$this->addFlash('success', 'Votre compte à été créer avec succès !');
```

<br>

Puis, dans la vue le message :

```html
{% for message in app.flashes('success') %}
    <div style="alert alert-success text-center col-10">{{ message }}</div>
{% endfor %}
```

<br>

Ajout d'un css pour le placement du message :

```css
/* message de succès */
.alert-success{
    position: absolute;
    left: 50%;
    transform: translateX(-50%);
}
```

<br>

Ajout du fichier jquery-3.6.0.min.js, et du fichier app.js pour gérer la disparition du message :

```javascript
// Rend temporaire les div alerts (messages)
if ($('div.alert')) {
    $('div.alert').delay(2500).fadeOut(300, function() {
        $('div.alert').remove();
    });
}
```

## Réalisation des pages d'erreur 403, 404, 500 (Alex)

Création des dossiers **bundles/TwigBundle/Exception** dans **templates**, qui accueilleront les fichiers d'erreur **403**, **404** et **500**.

On étend le fichier **base.html.twig** et on ajoute un peu de contenu.

<br>

## Ajouts divers

- Titre du site en paramètre global dans twig.yaml :

```
globals:
    site_name: 'Scope 3'
```

- Refonte du base.html.twig pour placement des blocs, ajout du titre avec le nom du site en suffix et ajout du lien vers bootstrap et style.css :

```html
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        {# Bootstrap #}
        <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
        {# FontAwesome #}
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta2/css/all.min.css">
        {# Styles #}
        <link rel="stylesheet" href="{{ asset('css/styles.css') }}">

        {# bloc pour ajouter du CSS/JS additionnel #}
        {% block css %}{% endblock %}

        <title>{% block title %}{% endblock %} - {{ site_name }}</title>
    </head>
    <body>
        {% block body %}{% endblock %}

        {# bloc pour l'inclusion du JS Bootstrap #}
        <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>

        {# bloc pour ajouter du JS additionnel #}
        {% block js %}{% endblock %}
    </body>
</html>
```

<br>

## Ajout de la navbar

Rajout de la navbar bootstrap dans base.html.twig, ainsi que de sa partie css.

<br>

## Ajout de style pour les formulaires

Rajout de css pour l'esthétique des formulaires et modification des champs pour avoir des labels flottants :

```
Exemple pour le nom :

{{ form_row(registrationForm.lastName, {
    label: 'Nom',
    attr: {
        placeholder: 'Nom'
    },
    row_attr: {
        class: 'form-floating my-3',
    },
}) }}
```

<br>

## Ajout de condition sur la navbar

- Pour la gestion de la classe active on regarde si la route de la page courante correspond avec le lien, et si oui on ajoute la classe active :

```html
<a class="nav-link{{ route == 'main_home' ? ' active' }}" href="{{ path('main_home') }}">Accueil</a>
```

<br>

- Pour l'affichage ou non des boutons de connexion, déconnexion et d'inscription, on regarde si l'utilisateur est connecté ou pas :

```html
{% if not app.user %}
    <li class="nav-item">
        <a class="nav-link{{ route == 'app_login' ? ' active' }}" href="{{ path('app_login') }}">Connexion</a>
    </li>
    <li class="nav-item">
        <a class="nav-link{{ route == 'app_register' ? ' active' }}" href="{{ path('app_register') }}">Inscription</a>
    </li>
{% endif %}
{% if app.user %}
    <li class="nav-item">
        <a class="nav-link" href="{{ path('app_logout') }}">se déconnecter</a>
    </li>
{% endif %}
```

<br>

## Création de vues supplémentaires sur le MainController

Ajout des vues "*Nos missions*", "*Qui sommes-nous ?*" et "*À propos*" :

```php
/* Contrôleur de la vue Nos missions */
#[Route('/nos-mission/', name: 'missions')]
public function missions(): Response
{
    return $this->render('main/missions.html.twig');
}

/* Contrôleur de la vue qui sommes nous */
#[Route('/qui-sommes-nous/', name: 'who_are_we')]
public function whoAreWe(): Response
{
    return $this->render('main/whoAreWe.html.twig');
}

/* Contrôleur de la vue a propos */
#[Route('/a-propos/', name: 'about')]
public function about(): Response
{
    return $this->render('main/qui_suis_je.html.twig');
}
```

<br>

## Ajout du Lifecycle pour la gestion des dates sur les entités

1. Suppression de la partie date dans le RegistrationController
2. Changements opérés sur les setters des dates dans les entités :

```php
exmple pour User :

/**
 * @ORM\PrePersist
 */
public function setRegisteredAt(): void
{
    $this->registeredAt = new \DateTimeImmutable();
}

/**
 * @ORM\PreUpdate()
 */
public function setUpdatedAt(): void
{
    $this->UpdatedAt = new \DateTime();
}
```

<br>

Pour les createdAt (et le registeredAt) PrePersist et pour les updatedAt PreUpdate.

<br>


##Catalogue projets / services (Etienne / Corentin)

Mise en place de bundles/extensions :

    composer require antishov/doctrine-extensions-bundle
    composer require knplabs/knp-paginator-bundle

    composer require orm-fixtures --dev

Création d'un controller pour gérer l'affichage des Projets/View consultants :

```
symfony console make:controller ConsultantController
```

Ensuite, dans le dossier ***src/templates/project*** , création des pages Twig : <br>

**projectCreate.html.twig** -> créer une nouvelle entité Projet. <br>

**projectList.html.twig**   -> affichage des données des différentes entitées Project sous forme de vignette.<br>

**projectView.html.twig**   -> on récupère les données de la vignette cliquée depuis *projectList* et on l'affiche sur une view à part entière.<br>

**projectEdit.html.twig** -> page de modification d'une vignette de projet. <br>

**clientInterface.html.twig** -> Interface du client, avec 2 liens, un pour le catalogue des services et un autre pour la création d'un projet.

<br>

Puis, dans **src/templates/project** :

**serviceCreate.html.twig** -> créer une nouvelle entité Projet. <br>

**serviceList.html.twig** -> affichage des données des différentes entitées Project sous forme de vignette.<br>

**serviceView.html.twig** -> on récupère les données de la vignette cliquée depuis *projectList* et on l'affiche sur une view à part entière.<br>

**serviceEdit.html.twig** -> page de modification d'une vignette de projet. <br>

**clientInterface.html.twig** -> Interface du client, avec 2 liens, un pour le catalogue des services et un autre pour la création d'un projet.


<br>

### Mise en place des slugs

Slug est un bundle permettant d'afficher une donnée, tirée d'une entité, dans l'URL.

On doit paramétrer le fichier **config/packages/stof_doctrine_extensions.yaml**  pour activer les slugs :
```
stof_doctrine_extensions:
    default_locale: fr_FR
    orm:
        default:
            sluggable: true
```

Nous allons donc insérer une propriété "slug" dans l'entité **Project.php** ainsi que **Service.php** :

```php
    /**
     * @ORM\Column(type="string", length=160, unique=true)
     * @Gedmo\Slug(fields={"name"})
     */
    private $slug;

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }
```

Puis, faire une migration vers la BDD après avoir ajouté notre propriété :

    symfony console make:migration
    symfony console doctrine:migrations:migrate

Nos champs "name" sont donc liés avec des champs "slug" dans les tables *project* et *service*. <br>
Désormais, si l'on écrit une route d'URL tel que **projet/{slug}** et que notre nom de projet est : "toto", nous aurons une route **projet/toto**.

<br>

### Remplissage de la BDD de fausses données en utilisant les Fixtures & Faker, dans le fichier **AppFixtures.php**

####Utilisation d'un Hasher de password :
```php
private $encoder;

    public function  __construct(UserPasswordHasherInterface $encoder)
    {
        $this->encoder = $encoder;
    }
```
####Fonction pour charger les données en BDD :

```php
public function load(ObjectManager $manager) 
{ 
    /* Instanciation Faker + Paramètres d'Hydratation*/ 
}
```

####Instanciation du Faker

```php
$faker = Faker\Factory::create('fr_FR');
```

#### Paramètres d'Hydratation :

Pour l'entité User, rôle Admin (unique) :

```php
    $admin = new User();
        $admin
            ->setEmail('a@a.fr')
            ->setFirstName('Admin')
            ->setLastName('Admin')
            ->setRoles(["ROLE_ADMIN"])
            ->setPassword(
                $this->encoder->hashPassword($admin, 'Password1*')
            )
            ->setPhone('0000000000')

        $manager->persist($admin);
```

Pour l'entité User, rôle Manager :

```php
    for ($i = 0; $i < 10; $i++) {
        $user = new User();

        $user
            ->setEmail($faker->email)
            ->setFirstName($faker->firstName)
            ->setLastName($faker->lastName)
            ->setRoles(["ROLE_MANAGER"])
            ->setPassword(
                $this->encoder->hashPassword($user, 'Password1*')
            )
            ->setPhone('0000000000')

        $manager->persist($user);
    }
```

Pour l'entité User, rôle Client :

```php
    for ($i = 0; $i < 10; $i++) {
        $user = new User();

        $user
            ->setEmail($faker->email)
            ->setFirstName($faker->firstName)
            ->setLastName($faker->lastName)
            ->setRoles(["ROLE_CLIENT"])
            ->setPassword(
                $this->encoder->hashPassword($user, 'Password1*')
            )
            ->setPhone('0000000000')
            ->setRegisteredAt($faker->dateTimeBetween('-1 year', 'now'))
            ->setUpdatedAt($faker->dateTimeBetween('-1 year', 'now'));
            
        $manager->persist($user);

    }
```

Pour l'entité User, rôle Consultant :

```php
    for ($i = 0; $i < 10; $i++) {
        $user = new User();

        $user
            ->setEmail($faker->email)
            ->setFirstName($faker->firstName)
            ->setLastName($faker->lastName)
            ->setRoles(["ROLE_CONSULTANT"])
            ->setPassword(
                $this->encoder->hashPassword($user, 'Password1*')
            )
            ->setPhone('0000000000');

        $manager->persist($user);
    }
```

Pour l'entité User, rôle user (de base) :

```php
    for ($i = 0; $i < 10; $i++) {
        $user = new User();

        $user
            ->setEmail($faker->email)
            ->setFirstName($faker->firstName)
            ->setLastName($faker->lastName)
            ->setRoles(["ROLE_USER"])
            ->setPassword(
                $this->encoder->hashPassword($user, 'Password1*')
            )
            ->setPhone('0000000000');
            
        $manager->persist($user);
    }
```

Pour l'entité Project :

```php
    for ($i = 0; $i < 20; $i++) {
        $project = new Project();

        $project
            ->setName($faker->sentence(5))
            ->setDescription($faker->paragraph(50))
            ->setSector($faker->sentence(2));

        $manager->persist($project);
    }
```

Pour l'entité Service :

```php
    for ($i = 0; $i < 20; $i++) {
            $service = new Service();

            $service
                ->setName($faker->sentence(5))
                ->setDescription($faker->paragraph(50))
                ->setSector($faker->sentence(2));

            $manager->persist($service);
    }
```
<br>

1. À chaque fin de tour dans la boucle **for**, pour enregistrer les données auprès de Doctrine, on execute :

```php
$manager->persist($newVariableEntite); // exemple : $manager->persist($newProject)
```

2. Puis, une fois toutes les boucles **for** terminées, on sauvegarde en BDD les entités :

```php
$manager->flush();
```

3. Enfin, on finit par le chargement des fixtures dans la console :

```
symfony console doctrine:fixtures:load
```

### Supprimer un projet/service

Condition permettant de créer un "Token" de sécurité temporaire unique, généré lors de l'action de suppression par l'utilisateur :

```php
if(!$this->isCsrfTokenValid('delete_project_' . $project->getId(),
    $request->query->get('csrf_token'))) {

    $this->addFlash('error', 'Token sécurité invalide, veuillez ré-essayer.');
    
    } else {

    // Manager général
    $em = $this->getDoctrine()->getManager();

    // Suppression du projet
    $em->remove($project);
    $em->flush();

    // Message flash de succès et redirige vers la liste
    $this->addFlash('success', 'Le projet a été supprimée avec succès !');

}
```

Le token est affiché et récupéré en méthode **"GET"**, afin de supprimer l'objet *projet* ou *service* correspondant.

### Formulaire de création

Mis en place de Formulaires pour la création de projets / services via l'interface client OU consultant, selon leurs rôles :

    symfony console make:form CreateProjectFormType // Création d'un projet
    symfony console make:form CreateServiceFormType // Création d'un service

### Formulaire d'édition

Mis en place de Formulaires, concernant l'édition du projet / service sélectionné via le bouton **Modifier** sur l'interface utilisateur :

    symfony console make:form EditProjectFormType // Edition du projet
    symfony console make:form EditServiceFormType // Edition du service

<br>

Pour les deux cas avons besoin des bundles CKEditor ainsi que Purify :

    composer require friendsofsymfony/ckeditor-bundle
    composer require exercise/htmlpurifier-bundle

On va "purify" le champ description en sortie, de l'entité modifiée, dans **projectView.html.twig** ainsi que **serviceView.html.twig** :


    {{ project.description|purify }}
    {{ service.description|purify }}

Le purify consiste à supprimer les balises HTML apparentes en sortie, pour que le texte soit propre.

<br>

##Interface Consultant (Corentin)

###Ajout de la route pour l'interface Consultant dans le Controller ConsultantController.php

```php

#[Route('/interface-consultant/', name: 'project_interface')]
    public function createProject(Request $request): Response
    {
        $project = new Project();
        $form = $this->createForm(CreateProjectFormType::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // TODO : gestion du captcha pour STAGE
            

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($project);
            $entityManager->flush();

            $this->addFlash('success', 'Votre projet à été créer avec succès !');

            return $this->redirectToRoute('article/project_list.html.twig');
        }

        return $this->render('article/projectInterface.html.twig', [
            'CreateProjectForm' => $form->createView(),
        ]);
    }
```

###Ajout des paramètres du formulaire dans le Form EditProjectFormType.php

```php
    $builder

            ->add('name', TextType::class)
            ->add('description', CKEditorType::class)
            ->add('sector', TextType::class);
```

###Ainsi que des use nécessaire

```php
use App\Entity\Article;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
```

###Ajout des Assert\NotBlank et Assert\Lenght l'Entité Projet.php, ils sont nécessaires pour faire fonctionner correctement le formulaire au moment de son execution.

#####Assert\NotBlank sert à envoyer un message en cas de champ vide au moment de l'envoi du formulaire.

#####et

#####Assert\Lenght sert à définir les paramètres (nombre minimum/maximum de caractères demandés et messages à envoyer si conditions non respectées) au moment de l'envoi du formulaire.

###Asserts de "name" pour le titre du projet


```php
#[Assert\NotBlank(
        message: 'Merci de renseigner un titre.',
    )]
    #[Assert\Length(
        min: 2,
        max: 100,
        minMessage: 'Le titre doit faire au moins {{ limit }} caractères.',
        maxMessage: 'Le titre ne peut dépasser {{ limit }} caractères.',
    )]
```

###Asserts de "description" pour le contenu du projet

```php
#[Assert\NotBlank(
        message: 'Merci de renseigner un contenu.',
    )]
    #[Assert\Length(
        min: 2,
        max: 50000,
        minMessage: 'Le contenu doit faire au moins {{ limit }} caractères.',
        maxMessage: 'Le contenu ne peut dépasser {{ limit }} caractères.',
    )]
```

###Asserts de "sector" pour le secteur d'activité

```php
#[Assert\NotBlank(
        message: 'Merci de renseigner un secteur d\'activité.',
    )]
    #[Assert\Length(
        min: 2,
        max: 50,
        minMessage: 'Le secteur d\activité doit faire au moins {{ limit }} caractères.',
        maxMessage: 'Le secteur d\activité ne peut dépasser {{ limit }} caractères.',
    )]
```

###Remplissage du template service serviceEdit.html.twig pour l'affichage du formulaire sur le site.

```html
 <h1>Editer un article</h1>

    {# Afficher formulaire #}
    {{  form(form) }}
```

## Page compte : affichage conditionnel de certains éléments

On ajoute des conditions pour savoir si certains éléments sont présent ou pas.
Si oui on les affiche, sinon on affiche un élément de remplacement :

```html

{# Conrtôle si l'utilisateur à une photo ou pas #}
{% if app.user.photo %}
    <p>Photo de profil: <img src="{{ app.user.photos }}" alt=""></p>
{% else %}
    {# TODO : Ajouter une image par défaut s'il n'y en a pas #}
    <p>Photo de profil: image par défaut </p>
{% endif %}
```

<br>

