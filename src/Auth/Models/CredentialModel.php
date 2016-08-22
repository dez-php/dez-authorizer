<?php

    namespace Dez\Authorizer\Models;

    use Dez\Authorizer\Models\Auth\SessionModel;
    use Dez\Authorizer\Models\Auth\TokenModel;

    /**
     * Class AuthSession
     * @package Dez\Authorizer\Models
     */
    class CredentialModel extends AbstractIntermediateModel {

        const STATUS_ACTIVE = 'active';
        const STATUS_LOCKED = 'locked';
        const STATUS_DELETED = 'deleted';

        /**
         * @var string
         */
        static protected $table = 'auth_credentials';

        /**
         * @return TokenModel[]
         * @throws \Dez\ORM\Exception
         */
        public function tokens() {
            return $this->hasMany( TokenModel::class, 'auth_id' );
        }

        /**
         * @return SessionModel[]
         * @throws \Dez\ORM\Exception
         */
        public function sessions() {
            return $this->hasMany( SessionModel::class, 'auth_id' );
        }

        /**
         * @param string $email
         * @return $this
         */
        public function setEmail($email)
        {
            $this->set('email', $email);

            return $this;
        }

        /**
         * @return string
         */
        public function getEmail()
        {
            return $this->get('email');
        }

        /**
         * @param string $password
         * @return $this
         */
        public function setPassword($password)
        {
            $this->set('password', $password);

            return $this;
        }

        /**
         * @return string
         */
        public function getPassword()
        {
            return $this->get('password');
        }

        /**
         * @param string $status
         * @return $this
         */
        public function setStatus($status)
        {
            $this->set('status', $status);

            return $this;
        }

        /**
         * @return string
         */
        public function getStatus()
        {
            return $this->get('status');
        }

        /**
         * @param string $datetime
         * @return $this
         */
        public function setCreatedAt($datetime)
        {
            $this->set('created_at', $datetime);

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
         * @param string $datetime
         * @return $this
         */
        public function setUpdatedAt($datetime)
        {
            $this->set('updated_at', $datetime);

            return $this;
        }

        /**
         * @return string
         */
        public function getUpdatedAt()
        {
            return $this->get('updated_at');
        }

    }