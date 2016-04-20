<?php

namespace Dez\Authorizer\Adapter;

use Dez\Authorizer\Authorizer;
use Dez\Authorizer\Models\Auth\SessionModel;

class Session extends Authorizer
{

    const COOKIE_KEY = 'dez-auth-key';

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

    public function login()
    {
        
    }

    public function logout()
    {
        $this->cookies->get($this->cookieKey())->delete();

        if($this->model->exists()) {
            $this->model->delete();
        }

        return $this;
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