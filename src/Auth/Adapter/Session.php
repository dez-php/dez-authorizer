<?php

namespace Dez\Authorizer\Adapter;

use Dez\Authorizer\AuthException;
use Dez\Authorizer\Authorizer;
use Dez\Authorizer\Models\Auth\SessionModel;
use Dez\Authorizer\Models\CredentialModel;

class Session extends Authorizer
{

    const COOKIE_KEY = 'dez-auth-key';

    /**
     * Session constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->model = new SessionModel();
    }

    /**
     * @return $this
     */
    public function initialize()
    {
        if ($authKey = $this->cookies->get($this->cookieKey(), false)) {

            $sessionModel = $this->findSession($this->protectedHash($authKey));

            if ($sessionModel->exists()) {
                $sessionModel->setUsedAt((new \DateTime())->format('Y-m-d H:i:s'))->save();
                $this->credentials = $sessionModel->credentials();
                $this->model = $sessionModel;
            }
        }

        return $this;
    }

    /**
     * @return $this
     * @throws AuthException
     */
    public function login()
    {
        $credentials = CredentialModel::query()
            ->where('email', $this->credentials()->getEmail())
            ->where('password', $this->hash($this->credentials()->getPassword()))
            ->first()
        ;

        if(! $credentials->exists()) {
            throw new AuthException("Authentication failed");
        }

        $this->credentials = $credentials;
        $this->createSession();

        return $this;
    }

    /**
     * @return $this
     */
    public function logout()
    {
        $this->cookies->get($this->cookieKey())->delete();

        if($this->model->exists()) {
            $this->model->delete();
        }

        return $this;
    }

    /**
     *
     */
    protected function createSession()
    {
        $date = (new \DateTime())->format('Y-m-d H:i:s');
        $hash = $this->randomHash();

        $this->cookies->set($this->cookieKey(), $hash, time() + 90 * 86400)->send();

        $this->getModel()
            ->setAuthId($this->credentials()->id())
            ->setCreatedAt($date)
            ->setUsedAt($date)
            ->setExpiryDate((new \DateTime('+ 90 days'))->format('Y-m-d H:i:s'))
            ->setAuthHash($this->protectedHash($hash))
            ->setUniqueHash($this->uniqueHash())
            ->save()
        ;
    }

    /**
     * @return string
     */
    protected function cookieKey()
    {
        return static::COOKIE_KEY . '_' . $this->hash($this->uniqueHash());
    }

    /**
     * @param null $hash
     * @return SessionModel
     */
    protected function findSession($hash = null)
    {
        return SessionModel::query()
            ->where('auth_hash', $hash)
            ->where('expiry_date', (new \DateTime())->format('Y-m-d H:i:s'), '>')
            ->first()
        ;
    }

    /**
     * @param $hash
     * @return string
     */
    protected function protectedHash($hash)
    {
        return $this->hash($hash);
    }
    
}