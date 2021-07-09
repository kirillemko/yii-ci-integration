Yii2 injection into CodeIgniter
=========================


Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
composer require kirillemko/yii-ci-integration "dev-master"
```

or add

```json
"kirillemko/yii-ci-integration": "dev-master"
```

to the require section of your composer.json.


Usage
-----

Create yii_config.php file in your CodeIgniter config folder
```
$ci = &get_instance();

return [
    'id' => 'yii_sub_app',
    'basePath' => dirname(__DIR__),
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host='.$ci->db->hostname.';dbname='.$ci->db->database.'',
            'username' => $ci->db->username,
            'password' => $ci->db->password,
            'charset' => 'utf8',
        ],
    ]
];
```

To sync Yii User component with CodeIgniter one, please register my user component, set identityClass the same way you make it in Yii and implement ExternalIdentityInterface in this class

```
'components' => [
    ...
    'user' => [
        'class' => 'kirillemko\yci\components\user\User',
        'identityClass' => 'App\domain\common\models\Users',
    ],
```
```
class Users extends ActiveRecord implements ExternalIdentityInterface
{

    public function getId() {
        return $this->id;
    }

    public static function getIdentity() {
        $ci = &get_instance();
        $user_id = $ci->ion_auth->get_user_id();
        if( !$user_id ){
            return null;
        }
        return static::findOne($user_id);
    }
}
```


Finally, load Yii core where needed

```
require_once(APPPATH . '../vendor/yiisoft/yii2/Yii.php');
$yiiConfig = require APPPATH . 'config/yii_config.php';
new \yii\web\Application($yiiConfig); // Do NOT call run() here
```

Credits
-------

Authors: Kirill Emelianenko, Evgeny Starodubcev

Email: kirill.emko@mail.ru

