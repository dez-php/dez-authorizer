<?php

    namespace Dez\Authorizer\Models\Auth;

    use Dez\Authorizer\Models\AbstractIntermediateModel;
    use Dez\Authorizer\Models\CredentialModel;

    /**
     * Class AuthSession
     * @package Dez\Auth\Models
     */
    class TokenModel extends AbstractIntermediateModel {

        /**
         * @var string
         */
        static protected $table = 'auth_tokens';

        /**
         * @return CredentialModel
         * @throws \Dez\ORM\Exception
         */
        public function credentials() {
            return $this->hasOne( CredentialModel::class, 'id', 'auth_id' );
        }

        /**
         * @return int
         */
        public function getAuthId()
        {
            return $this->get('auth_id');
        }

        /**
         * @param int $auth_id
         * @return $this
         */
        public function setAuthId($auth_id)
        {
            $this->set('auth_id', $auth_id);

            return $this;
        }

        /**
         * @return string
         */
        public function getToken()
        {
            return $this->get('token');
        }

        /**
         * @param $token
         * @return $this
         */
        public function setToken($token)
        {
            $this->set('token', $token);

            return $this;
        }

        /**
         * @return string
         */
        public function getUniqueHash()
        {
            return $this->get('unique_hash');
        }

        /**
         * @param string $unique_hash
         * @return $this
         */
        public function setUniqueHash($unique_hash)
        {
            $this->set('unique_hash', $unique_hash);

            return $this;
        }

        /**
         * @return string
         */
        public function getCreatedAt()
        {
            return $this->get('created_at');
        }

        /**
         * @param string $created_at
         * @return $this
         */
        public function setCreatedAt($created_at)
        {
            $this->set('created_at', $created_at);

            return $this;
        }

        /**
         * @return string
         */
        public function getUsedAt()
        {
            return $this->get('used_at');
        }

        /**
         * @param string $used_at
         * @return $this
         */
        public function setUsedAt($used_at)
        {
            $this->set('used_at', $used_at);

            return $this;
        }

        /**
         * @return string
         */
        public function getExpiryDate()
        {
            return $this->get('expiry_date');
        }

        /**
         * @param string $expiry_date
         * @return $this
         */
        public function setExpiryDate($expiry_date)
        {
            $this->set('expiry_date', $expiry_date);

            return $this;
        }

    }