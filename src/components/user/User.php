<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace kirillemko\yci\components\user;

use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;
use yii\base\InvalidValueException;


class User extends \yii\web\User
{

    public $identityClass;
    private $_identity = false;


    /**
     * Initializes the application component.
     */
//    public function init()
//    {
//        parent::init();
//
//        if ($this->identityClass === null) {
//            throw new InvalidConfigException('User::identityClass must be set.');
//        }
//    }



    /**
     * Returns the identity object associated with the currently logged-in user.
     * When [[enableSession]] is true, this method may attempt to read the user's authentication data
     * stored in session and reconstruct the corresponding identity object, if it has not done so before.
     * @param bool $autoRenew whether to automatically renew authentication status if it has not been done so before.
     * This is only useful when [[enableSession]] is true.
     * @return ExternalIdentityInterface|null the identity object associated with the currently logged-in user.
     * `null` is returned if the user is not logged in (not authenticated).
     * @see login()
     * @see logout()
     */
    public function getIdentity($autoRenew = true)
    {
        if ($this->_identity === false) {
            /* @var $class ExternalIdentityInterface */
            $class = $this->identityClass;
            $identity = $class::getIdentity();
            $this->setIdentity($identity);
        }

        return $this->_identity;
    }


    public function setIdentity($identity)
    {
        if ($identity instanceof ExternalIdentityInterface) {
            $this->_identity = $identity;
        } elseif ($identity === null) {
            $this->_identity = null;
        } else {
            throw new InvalidValueException('The identity object must implement CodeigniterIdentityInterface.');
        }
    }



    /**
     * Returns a value that uniquely represents the user.
     * @return string|int the unique identifier for the user. If `null`, it means the user is a guest.
     * @see getIdentity()
     */
    public function getId()
    {
        $identity = $this->getIdentity();

        return $identity !== null ? $identity->getId() : null;
    }

}
