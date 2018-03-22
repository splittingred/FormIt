<?php

namespace Sterc\FormIt\Service;

class StopForumSpam
{
    /**
     * @var \modX $modx
     */
    public $modx = null;

    /**
     * @var array $config
     */
    public $config = [];

    /**
     * StopForumSpam constructor.
     *
     * @param \modX $modx
     * @param array $config
     */
    public function __construct($modx, $config = [])
    {
        $this->modx = $modx;
        $this->config = array_merge([
            'host' => 'http://api.stopforumspam.org/',
            'path' => 'api',
            'method' => 'GET',
        ], $config);
    }

    /**
     * Check for spammer
     *
     * @param string $ip
     * @param string $email
     * @param string $username
     *
     * @return array An array of errors
     */
    public function check($ip = '', $email = '', $username = '')
    {
        $params = [];
        if (!empty($ip)) {
            if (in_array($ip, ['127.0.0.1', '::1', '0.0.0.0'])) {
                $ip = '72.179.10.158';
            }

            $params['ip'] = $ip;
        }

        if (!empty($email)) {
            $params['email'] = $email;
        }

        if (!empty($username)) {
            $params['username'] = $username;
        }

        $xml = $this->request($params);
        $i = 0;
        $errors = [];

        foreach ($xml->appears as $result) {
            if ($result === 'yes') {
                $errors[] = ucfirst($xml->type[$i]);
            }

            $i++;
        }

        return $errors;
    }

    /**
     * Make a request to stopforumspam.com
     *
     * @param array $params An array of parameters to send
     *
     * @return mixed The return SimpleXML object, or false if none
     */
    public function request($params = [])
    {
        $loaded = $this->getClient();
        if (!$loaded) {
            $this->modx->log(\modX::LOG_LEVEL_ERROR, '[StopForumSpam] Could not load REST client.');

            return true;
        }

        $response = $this->modx->rest->request($this->config['host'], $this->config['path'], $this->config['method'], $params);
        $responseXml = $response->toXml();

        return $responseXml;
    }

    /**
     * Get the REST Client
     *
     * @return \modRestClient|bool
     */
    private function getClient()
    {
        if (empty($this->modx->rest)) {
            $this->modx->getService('rest', 'rest.modRestClient');
            $loaded = $this->modx->rest->getConnection();

            if (!$loaded) {
                return false;
            }
        }

        return $this->modx->rest;
    }
}
