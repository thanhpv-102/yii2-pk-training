ProjectKit Training - Yii2 Basic Template
============================


[![Build Status](https://travis-ci.org/thanhpv-102/yii2-pk-training.svg?branch=master)](https://travis-ci.org/thanhpv-102/yii2-pk-training)

Tag 0.1: Init app
--------------
Init basic app from [https://github.com/yiisoft/yii2-app-basic](https://github.com/yiisoft/yii2-app-basic)

```
composer global require "fxp/composer-asset-plugin:*"
composer create-project --prefer-dist yiisoft/yii2-app-basic yii2-training
```

Tag 0.2: Config DB and migration
--------------
Modify db config base on your mysql server:

```php
return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=localhost;dbname=pk-yii2',
    'username' => 'root',
    'password' => 'root',
    'charset' => 'utf8',
];
```

Run command on your command-line:
 
 ```
 yii migrate/create add_book_table
 ```
 
After that, yii2 will create a migration script on `migrations` folder. Example name: `m160823_032016_add_book_table.php`

Modify migration script for create table, insert test data, ... to database

Run `yii migrate` to apply your migration.

Tag 0.3: CRUD with gii generator
--------------

Example gii generator link: [http://localhost/yii2-training/web/index.php?r=gii](http://localhost/yii2-training/web/index.php?r=gii)

Generate model and CRUD for book table.

Tag 0.4: Pretty url and add custom routing
--------------

Enable pretty url in `urlManager` component config.

```php
'urlManager' => [
    'enablePrettyUrl' => true,
    'showScriptName' => false,
    'rules' => [
    ],
],
```

Add htaccess config for rewrite url in `web` folder.

```
RewriteEngine on
RewriteBase /yii2-training/web
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d 

RewriteRule . index.php
```

Use parameterizing route config
 
```
'urlManager' => [
    'enablePrettyUrl' => true,
    'showScriptName' => false,
    'rules' => [
        '<controller>/<action:(view)>/<id:\d+>' => '<controller>/<action>',
        '<controller>s' => '<controller>/index',
    ],
],
```

Now you can show list book in link: [http://localhost/yii2-training/web/books](http://localhost/yii2-training/web/books)

And view detail a book: [http://localhost/yii2-training/web/book/view/1](http://localhost/yii2-training/web/book/view/1)

Tag 0.5: Use access control
-----------------

We will implement that: `User must login to view list book, create, delete book`

Update `behaviors` function in `BookController`

```php
public function behaviors()
{
    return array_merge(parent::behaviors(), [
        'access' => [
            'class' => AccessControl::className(),
            'rules' => [
                // allow authenticated users
                [
                    'allow' => true,
                    'roles' => ['@'],
                ],
                // everything else is denied
            ],
        ],
    ]);
}
```

Tag 0.6: Implement API modules
----------------

Create folder `api` and implement API module in this folder.

You can list all book via rest api: [http://localhost/yii2-training/api/v1/books](http://localhost/yii2-training/api/v1/books)

Tag 0.7: Demo AngularJs
-----------------

Create view and action `angular` in SiteController.

Create `AngularAsset` and `JsAsset`, use them in `angular` view.

Tag 0.8: I18n and l10n
----------------

Add `i18n` component in config

```php
'i18n' => [
    'translations' => [
        'app*' => [
            'class' => 'yii\i18n\PhpMessageSource',
            'basePath' => '@app/messages',
            'sourceLanguage' => 'en-US',
            'fileMap' => [
                'app' => 'app.php',
                'app/error' => 'error.php',
                'app/pk' => 'pk.php'
            ],
        ],
    ],
]
```

Create folder `messages` and 2 sub-folder for `vi-VN` - Vietnamese and `jp-JP` - Japanese language.

Create file `app.php`, `pk.php` and define key-value from source language to target language.

Tag 0.9: Use ActiveRecord and ActiveQuery for custom sql query
--------------------

Yii2 ActiveRecord support 2 syntax for bind variable to query:
```php
where('subtotal > :threshold', [':threshold' => $threshold]); // syntax like yii1

where(['>', 'subtotal', $threshold]);
```

Add more condition in where clause:

The `where()` method specifies the `WHERE` fragment of a SQL query. You can use one of the three formats to specify a `WHERE` condition:

* string format, e.g. `'status=1'`
* hash format, e.g. `['status' => 1, 'type' => 2]`
* operator format, e.g. `['like', 'name', 'test']`

More operator for where condition: [http://www.yiiframework.com/doc-2.0/guide-db-query-builder.html#where](http://www.yiiframework.com/doc-2.0/guide-db-query-builder.html#where)

Selecting extra fields:

```php
$customers = Customer::find()
    ->select([
        '{{customer}}.*', // select all customer fields
        'COUNT({{order}}.id) AS ordersCount' // calculate orders count
    ])
    ->joinWith('orders') // ensure table junction
    ->groupBy('{{customer}}.id') // group the result to ensure aggregation function works
    ->all();
```

More: [http://www.yiiframework.com/doc-2.0/guide-db-active-record.html#selecting-extra-fields](http://www.yiiframework.com/doc-2.0/guide-db-active-record.html#selecting-extra-fields)

Working with relations data:

```php
//Add function to model
public function getOrders()
{
    return $this->hasMany(Order::className(), ['customer_id' => 'id']);
}
```

More: [http://www.yiiframework.com/doc-2.0/guide-db-active-record.html#relational-data](http://www.yiiframework.com/doc-2.0/guide-db-active-record.html#relational-data)

Tag 0.9.1: Add travis-ci.yml
---------------

Create .travis.yml and config to run codecept test functional

Tag 0.10: Use `getter` in model for statistical query
--------------

```php
public function getTotalQuestion()
{
    return self::find()->count();
}
```

Then you can get totalQuestion value in view:

```
/* @var $model app\models\Question */

$model->totalQuestion
```

Tag 0.11: Access control filter (ACF)
-------------

Access Control Filter (ACF) is a simple authorization method implemented as [yii\filters\AccessControl](http://www.yiiframework.com/doc-2.0/yii-filters-accesscontrol.html) which is best used by applications that only need some simple access control.

Now I attach `access` filter to `QuestionController`, I want guest can access index action,
but must authenticated user can update or delete.

We attach new config to `behaviors()` function:

```php
/**
 * @inheritdoc
 */
public function behaviors()
{
    return array_merge(parent::behaviors(), [
        'access' => [
            'class' => AccessControl::className(),
            'rules' => [
                [
                    'allow' => true,
                    'actions' => ['index', 'view'],
                    'roles' => ['?'],
                ],
                [
                    'allow' => true,
                    'actions' => ['create', 'update', 'delete'],
                    'roles' => ['@'],
                ],
            ],
        ],
        // ... other behaviors
    ]);
}
```

When guest user request to action `create`, it will be redirect to login page.

Tag 0.12: Role Base Access Control (RBAC)
------------------

Using `DbManager`: Add `authManager` component to `web.php` and `console.php`

```php
'components' => [
    'authManager' => [
        'class' => 'yii\rbac\DbManager',
    ],
    // ...
],
```

Before you can go on you need to create those tables in the database. To do this, you can use the migration stored in `@yii/rbac/migrations`:

```php
yii migrate --migrationPath=@yii/rbac/migrations
```

Create `RbacController` in `commands` folder:

```php
namespace app\commands;

use Yii;
use yii\console\Controller;

class RbacController extends Controller
{
    /**
     * This command create 2 permissions is createQuestion, updateQuestion
     * create 2 roles is author and admin
     * Assign author to user id 101 (demo/demo) and admin to user id 100 (admin/admin)
     */
    public function actionInit()
    {
        $auth = Yii::$app->authManager;

        // add "updateQuestion" permission
        $updateQuestion = $auth->createPermission('updateQuestion');
        $updateQuestion->description = 'Update question';
        $auth->add($updateQuestion);

        // add "createQuestion" permission
        $createQuestion = $auth->createPermission('createQuestion');
        $createQuestion->description = 'Create question';
        $auth->add($createQuestion);

        // add "author" role and give this role the "createQuestion" permission
        $author = $auth->createRole('author');
        $auth->add($author);
        $auth->addChild($author, $createQuestion);

        // add "admin" role and give this role the "updateQuestion" permission
        // as well as the permissions of the "author" role
        $admin = $auth->createRole('admin');
        $auth->add($admin);
        $auth->addChild($admin, $updateQuestion);
        $auth->addChild($admin, $author);

        $auth->assign($author, 101);
        $auth->assign($admin, 100);
    }
}
```

Then run `yii rbac/init` to apply `init` action.

Apply in `actionCreate` and `actionUpdate`:

```php
public function actionCreate()
{
    if(\Yii::$app->user->can('createQuestion')) {
        return parent::actionCreate();
    } else {
        throw new ForbiddenHttpException('You dont have permission to createQuestion');
    }
}

public function actionUpdate($id)
{
    if(\Yii::$app->user->can('updateQuestion')) {
        return parent::actionUpdate($id);
    } else {
        throw new ForbiddenHttpException('You dont have permission to updateQuestion');
    }
}
```

Tag 0.13: Login with facebook account
-------------------------------------

Create app on facebook, get `application id` and `app secret`

Use `yii2-authclient` extension for OAuth2 flow:

```
composer require --prefer-dist yiisoft/yii2-authclient
```

Add config `authClientCollection` component on your `web.php`

```
'authClientCollection' => [
    'class' => 'yii\authclient\Collection',
    'clients' => [
        'facebook' => [
            'class' => 'yii\authclient\clients\Facebook',
            'clientId' => 'facebook_client_id',
            'clientSecret' => 'facebook_client_secret',
        ],
    ],
]
```

Add `auth` action to your `SiteController` and setup callback:

```
'auth' => [
    'class' => 'yii\authclient\AuthAction',
    'successCallback' => [$this, 'onAuthSuccess'],
],
```

```
/**
 * @param $client ClientInterface
 */
public function onAuthSuccess($client)
{
    //Get user info
    /** @var array $attributes */
    $attributes = $client->getUserAttributes();
    $email = ArrayHelper::getValue($attributes, 'email'); //email info
    $id = ArrayHelper::getValue($attributes, 'id'); // id facebook user
    $name = ArrayHelper::getValue($attributes, 'name'); // name facebook account

    //Login user
    //For demo, I will login with admin/admin default account
    $admin = User::findByUsername('admin');
    Yii::$app->user->login($admin);
}
```

Tag 0.14: Security API server with JWT token
------------------------------------

We need to create an action for provide JWT token for clients, and an action provide public key
if use RSA algorithm.

Generate and private key via command:
```bash
ssh-keygen -t rsa -b 4096
```

I generated and private key and stored in `api/certs` folder.

I wrote an `JwtHelper` class for getPrivateKey, getPublicKey and generateToken.

Now clients can request new token, public key via link:
```
Generate by RSA key
POST http://<host>/api/v1/tokens/generate-public

Generate by HMAC
POST http://<host>/api/v1/tokens/generate-private

Get public key for verify token RSA key
http://<host>/api/v1/tokens/public-key
```

Tag 0.15: Use Rate Limit Filter to prevent abuse API
----------------------------

Default, `RateLimiter` limit API usage of each user. But with example, I will
 limit API usage of each IP.
 
I save info of IP and update allowance of IP in cache storage.

Create a model `IpRateLimiter` implements `yii\filters\RateLimitInterface`, and
implement function `getRateLimit`, `loadAllowance`, `saveAllowance`