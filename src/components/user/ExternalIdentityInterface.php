<?php

namespace kirillemko\yci\components\user;


interface ExternalIdentityInterface
{
    /**
     * Получение юзера, аутентифицированного внутри Codeigniter
     * @return ExternalIdentityInterface|null
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function getIdentity();


    /**
     * Returns an ID that can uniquely identify a user identity.
     * @return string|int an ID that uniquely identifies a user identity.
     */
    public function getId();

}
