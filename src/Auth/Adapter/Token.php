<?php

namespace Dez\Authorizer\Adapter;

use Dez\Authorizer\AuthException;
use Dez\Authorizer\Authorizer;
use Dez\Authorizer\Models\Auth\TokenModel;
use Dez\Authorizer\Models\CredentialModel;

class Token extends Authorizer {

    protected $tokenKey = 'token';

    protected $token = null;

    /**
     * Token constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->model = new TokenModel();
    }

    /**
     * @return $this
     */
    public function initialize()
    {
        if (false !== ($token = $this->request->getQuery($this->tokenKey, false))) {

            $tokenModel = $this->setToken($token)->cleanTokens()->findToken($this->token());

            if ($tokenModel->exists()) {
                $tokenModel->setUsedAt((new \DateTime())->format('Y-m-d H:i:s'))->save();
                $this->credentials = $tokenModel->credentials();
                $this->model = $tokenModel;
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
        $this->logout();

        $credentials = CredentialModel::query()
            ->where('email', $this->credentials()->getEmail())
            ->where('password', $this->hash($this->credentials()->getPassword()))
            ->first()
        ;

        if(! $credentials->exists()) {
            throw new AuthException("Authentication failed");
        }

        $this->credentials = $credentials;
        $this->createToken();

        return $this;
    }

    /**
     * @return $this
     */
    public function logout()
    {
        $this->getModel()->exists() && $this->getModel()->delete();

        return $this;
    }

    /**
     * @return string|null
     */
    public function token()
    {
        return $this->token;
    }

    /**
     * @param null $token
     * @return $this
     */
    public function setToken($token = null)
    {
        $this->token = $token;

        return $this;
    }

    /**
     * @return TokenModel
     */
    public function getModel()
    {
        return parent::getModel();
    }

    /**
     * @return Token
     */
    protected function createToken()
    {
        $date = (new \DateTime())->format('Y-m-d H:i:s');
        $hash = $this->randomHash();

        TokenModel::query()->where('unique_hash', $this->uniqueHash())->first()->delete();

        $this->getModel()
            ->setAuthId($this->credentials()->id())
            ->setCreatedAt($date)
            ->setUsedAt($date)
            ->setExpiryDate((new \DateTime('+ 90 days'))->format('Y-m-d H:i:s'))
            ->setToken($hash)
            ->setUniqueHash($this->uniqueHash())
            ->save(true)
        ;

        return $this->setToken($hash);
    }

    /**
     * @param null $token
     * @return TokenModel
     */
    protected function findToken($token = null)
    {
        return TokenModel::query()
            ->where('token', $token)
            ->where('expiry_date', (new \DateTime())->format('Y-m-d H:i:s'), '>')
            ->first()
        ;
    }

    /**
     * @return $this
     */
    protected function cleanTokens()
    {
        TokenModel::query()
            ->where('expiry_date', (new \DateTime())->format('Y-m-d H:i:s'), '<=')
            ->delete()
        ;

        return $this;
    }
    
}