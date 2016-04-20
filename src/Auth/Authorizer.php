<?php

namespace Dez\Authorizer;

use Dez\Authorizer\Hasher\UUID;
use Dez\Authorizer\Models\Auth\SessionModel;
use Dez\Authorizer\Models\Auth\TokenModel;
use Dez\Authorizer\Models\CredentialModel;
use Dez\DependencyInjection\Injectable;
use Dez\Http\Cookies;
use Dez\Http\Request;

/**
 * @property Request request
 * @property Cookies cookies
 */

abstract class Authorizer extends Injectable {

    protected $credentials = null;

    protected $model = null;
    
    protected $salt = 'XG78MtKRPpumgdlcmMZR';

    /**
     * Authorizer constructor.
     */
    public function __construct()
    {
        $this->credentials = new CredentialModel();
    }

    /**
     * @param null $email
     * @param null $password
     * @param string $status
     * @return $this
     * @throws AuthException
     */
    public function register($email = null, $password = null, $status = CredentialModel::STATUS_ACTIVE)
    {
        if($this->isGuest()) {
            $date = (new \DateTime())->format('Y-m-d H:i:s');
            
            $this->credentials()
                ->setEmail($email)
                ->setPassword($this->hash($password))
                ->setStatus($status)
                ->setCreatedAt($date)
                ->setUpdatedAt($date)
            ;
            
            if(! $this->credentials()->save(true)) {
                throw new AuthException('Can not register user');
            }
        }

        return $this;
    }

    /**
     * @return bool
     */
    public function isGuest()
    {
        return ! ($this->credentials->id() > 0);
    }

    /**
     * @return CredentialModel
     */
    public function credentials()
    {
        return $this->credentials;
    }

    /**
     * @param null $password
     * @return bool
     */
    public function verifyPassword($password = null)
    {
        return ($this->credentials()->getPassword() === $this->hash($password));
    }

    /**
     * @param null $value
     * @return string
     */
    public function hash($value = null)
    {
        return UUID::v5($this->getSalt() . (string) $value . $this->getSalt());
    }

    /**
     * @return string
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * @return string
     */
    public function uniqueHash()
    {
        return UUID::v5($this->request->getClientIP() . $this->request->getUserAgent());
    }

    /**
     * @return string
     */
    public function randomHash()
    {
        return UUID::v4();
    }

    /**
     * @param string $salt
     * @return static
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;

        return $this;
    }

    /**
     * @param $email
     * @return $this
     */
    public function setEmail($email)
    {
        $this->credentials()->setEmail($email);

        return $this;
    }

    /**
     * @param $password
     * @return $this
     */
    public function setPassword($password)
    {
        $this->credentials()->setPassword($password);

        return $this;
    }

    /**
     * @return SessionModel|TokenModel
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @param SessionModel|TokenModel $model
     * @return static
     */
    public function setModel($model)
    {
        $this->model = $model;

        return $this;
    }

    /**
     * @return $this
     */
    abstract public function initialize();

    /**
     * @return $this
     */
    abstract public function login();

    /**
     * @return $this
     */
    abstract public function logout();

    
}