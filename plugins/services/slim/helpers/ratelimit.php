<?php
/**
 * @version     1.3.6
 * @package     Annatech.Plugin
 * @subpackage  Services.slim
 *
 * @copyright	Copyright (C) 2018 Annatech LLC. All rights reserved.
 * @license		GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

/**
 * Class RateLimitServicesSlim
 * @since 1.0
 */
class ServicesSlimHelpersRatelimit
{
    /**
     * @var null
     * @since 1.0
     */
    protected $token = null;

    public function __construct()
    {
        $this->rateLimitServicesSlim();
    }

    /**
     * RateLimitServicesSlim constructor.
     * @since 1.0
     */
    public function rateLimitServicesSlim()
    {
        $app = \Slim\Slim::getInstance();

        // Return false if no token is set
        if($app->config('token') === null){
            return false;
        }

        // Get a db connection.
        $db = JFactory::getDbo();

        // Create a new query object.
        $query = $db->getQuery(true);

        $query
            ->select($db->quoteName(array('last_used', 'api_throttle','api_rate_limit')))
            ->from($db->quoteName('#__services_tokens'))
            ->where($db->quoteName('token') . ' = ' . $db->quote($app->config('token')));

        $db->setQuery($query);

        if ($app->config('rate.limit') >= '1') {

            $minute = 60;
            $hour = 3600;
            $minute_limit = $app->config('rate.limit'); # API rate limit per minute
            $last_api_request = $db->loadObject()->last_used; # get from the DB;
            $last_api_diff = JFactory::getDate()->toUnix() - JFactory::getDate($last_api_request)->toUnix(); # in seconds
            $minute_throttle = $db->loadObject()->api_throttle; # get from the DB

            if (is_null($minute_limit)) {
                $new_minute_throttle = 0;
            } else {
                $new_minute_throttle = $minute_throttle - $last_api_diff;
                $new_minute_throttle = $new_minute_throttle < 0 ? 0 : $new_minute_throttle;
                $new_minute_throttle += $minute / $minute_limit;
                $minute_hits_remaining = floor(($minute - $new_minute_throttle) * $minute_limit / $minute);
                # can output this value with the request if desired:
                $minute_hits_remaining = $minute_hits_remaining >= 0 ? $minute_hits_remaining : 0;

                $app->response->headers->set(
                    'X-RateLimit-Remaining',
                    $minute_hits_remaining
                );
            }

            if ($new_minute_throttle > $minute) {
                $wait = ceil($new_minute_throttle - $minute);
                usleep(250000);

                $app->response->headers->set(
                    'Retry-After',
                    $wait
                );

                // Exits with status "429 Too Many Requests" (see doc below)
                $this->fail();
            }
            // Set rate headers
            $app->response->headers->set(
                'X-RateLimit-Limit',
                $app->config('rate.limit')
            );

            // TODO: Break out into separate function and implement try catch
            // Update last_used time.
            $query = $db->getQuery(true);
            // Fields to update.
            $fields = array(
                $db->quoteName('api_throttle') . '="' . $new_minute_throttle . '"',
                $db->quoteName('last_used') . '="'.JFactory::getDate().'"'
            );
            // Conditions for which records should be updated.
            $conditions = array(
                $db->quoteName('token') . '="' . $app->config('token') . '"',
            );
            $query->update($db->quoteName('#__services_tokens'))->set($fields)->where($conditions);
            $db->setQuery($query);
            $result = $db->execute();
        }
    }


    /**
     * Exits with status "429 Too Many Requests"
     *
     * Work around on Apache's issue: it does not support
     * status code 429 until version 2.4
     *
     * @link http://stackoverflow.com/questions/17735514/php-apache-silently-converting-http-429-and-others-to-500
     * @since 1.0
     */
    protected function fail()
    {
        $app = \Slim\Slim::getInstance();
        header('HTTP/1.1 429 Too Many Requests', false, 429);

        // Write the remaining headers
        foreach ($app->response->headers as $key => $value) {
            header($key . ': ' . $value);
        }
        echo 'Too Many Requests';
        exit;
    }

    /**
     * @return mixed
     * @since 1.0
     */
    function call(){
        return $this->next->call();
    }
}