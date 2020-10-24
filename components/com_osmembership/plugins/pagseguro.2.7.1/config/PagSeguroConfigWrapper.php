<?php
/**
 * 2007-2014 [PagSeguro Internet Ltda.]
 *
 * NOTICE OF LICENSE
 *
 *Licensed under the Apache License, Version 2.0 (the "License");
 *you may not use this file except in compliance with the License.
 *You may obtain a copy of the License at
 *
 *http://www.apache.org/licenses/LICENSE-2.0
 *
 *Unless required by applicable law or agreed to in writing, software
 *distributed under the License is distributed on an "AS IS" BASIS,
 *WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 *See the License for the specific language governing permissions and
 *limitations under the License.
 *
 *  @author    PagSeguro Internet Ltda.
 *  @copyright 2007-2014 PagSeguro Internet Ltda.
 *  @license   http://www.apache.org/licenses/LICENSE-2.0
 */

class PagSeguroConfigWrapper
{
    /**
     * production or sandbox
     */
    const PAGSEGURO_ENV = "sandbox";
    /**
     *
     */
    const PAGSEGURO_EMAIL = "pagamentos@plaas.com.br";
    /**
     *
     */
    const PAGSEGURO_TOKEN_PRODUCTION = "bf4b531a-b660-4a28-beea-27d29447aa57314cb72a4effb0fbb7223223a69da94e77ce-9704-49e6-9bca-5f8c1154fc14";
    /**
     *
     */
    const PAGSEGURO_TOKEN_SANDBOX = "1FD718413516441D9803333021F13503";
    /**
     *
     */
    const PAGSEGURO_APP_ID_PRODUCTION = "plaas-agrotech-site";
    /**
     *
     */
    const PAGSEGURO_APP_ID_SANDBOX = "app0105923030";
    /**
     *
     */
    const PAGSEGURO_APP_KEY_PRODUCTION = "3287D0892B2BCC4774F23F8DD3D4C3E0";
    /**
     *
     */
    const PAGSEGURO_APP_KEY_SANDBOX = "529BB1F2DBDBFBAAA4C6CF8A72A1BF9D";
    /**
     * UTF-8, ISO-8859-1
     */
    const PAGSEGURO_CHARSET = "ISO-8859-1";
    /**
     *
     */
    const PAGSEGURO_LOG_ACTIVE = true;
    /**
     * Informe o path completo (relativo ao path da lib) para o arquivo, ex.: ../PagSeguroLibrary/logs.txt
     */
    const PAGSEGURO_LOG_LOCATION = "";

    /**
     * @return array
     */
    public static function getConfig()
    {
        $PagSeguroConfig = array();

        $PagSeguroConfig = array_merge_recursive(
            self::getEnvironment(),
            self::getCredentials(),
            self::getApplicationEncoding(),
            self::getLogConfig()
        );

        return $PagSeguroConfig;
    }

    /**
     * @return mixed
     */
    private static function getEnvironment()
    {
        $PagSeguroConfig['environment'] = getenv('PAGSEGURO_ENV') ?: self::PAGSEGURO_ENV;

        return $PagSeguroConfig;
    }

    /**
     * @return mixed
     */
    private static function getCredentials()
    {
        $PagSeguroConfig['credentials'] = array();
        $PagSeguroConfig['credentials']['email'] = getenv('PAGSEGURO_EMAIL')
            ?: self::PAGSEGURO_EMAIL;
        $PagSeguroConfig['credentials']['token']['production'] = getenv('PAGSEGURO_TOKEN_PRODUCTION')
            ?: self::PAGSEGURO_TOKEN_PRODUCTION;
        $PagSeguroConfig['credentials']['token']['sandbox'] = getenv('PAGSEGURO_TOKEN_SANDBOX')
            ?: self::PAGSEGURO_TOKEN_SANDBOX;
        $PagSeguroConfig['credentials']['appId']['production'] = getenv('PAGSEGURO_APP_ID_PRODUCTION')
            ?: self::PAGSEGURO_APP_ID_PRODUCTION;
        $PagSeguroConfig['credentials']['appId']['sandbox'] = getenv('PAGSEGURO_APP_ID_SANDBOX')
            ?: self::PAGSEGURO_APP_ID_SANDBOX;
        $PagSeguroConfig['credentials']['appKey']['production'] = getenv('PAGSEGURO_APP_KEY_PRODUCTION')
            ?: self::PAGSEGURO_APP_KEY_PRODUCTION;
        $PagSeguroConfig['credentials']['appKey']['sandbox'] = getenv('PAGSEGURO_APP_KEY_SANDBOX')
            ?: self::PAGSEGURO_APP_KEY_SANDBOX;

        return $PagSeguroConfig;
    }

    /**
     * @return mixed
     */
    private static function getApplicationEncoding()
    {
        $PagSeguroConfig['application'] = array();
        $PagSeguroConfig['application']['charset'] = ( getenv('PAGSEGURO_CHARSET')
            && ( getenv('PAGSEGURO_CHARSET') == "UTF-8" || getenv('PAGSEGURO_CHARSET') == "ISO-8859-1") )
            ?: self::PAGSEGURO_CHARSET;

        return $PagSeguroConfig;
    }

    /**
     * @return mixed
     */
    private static function getLogConfig()
    {
        $PagSeguroConfig['log'] = array();
        $PagSeguroConfig['log']['active'] = ( getenv('PAGSEGURO_LOG_ACTIVE')
            && getenv('PAGSEGURO_LOG_ACTIVE') == 'true' ) ?: self::PAGSEGURO_LOG_ACTIVE;
        $PagSeguroConfig['log']['fileLocation'] = getenv('PAGSEGURO_LOG_LOCATION')
            ?: self::PAGSEGURO_LOG_LOCATION;

        return $PagSeguroConfig;
    }
}
