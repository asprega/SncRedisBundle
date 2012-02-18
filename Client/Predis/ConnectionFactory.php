<?php

/*
 * This file is part of the SncRedisBundle package.
 *
 * (c) Henrik Westphal <henrik.westphal@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Snc\RedisBundle\Client\Predis;

use Predis\Profiles\IServerProfile;
use Snc\RedisBundle\Logger\RedisLogger;
use Snc\RedisBundle\Client\Predis\Network\ConnectionWrapper;

class ConnectionFactory extends \Predis\ConnectionFactory
{
    /**
     * @var ConnectionWrapper
     */
    protected $wrapper;

    /**
     * @var RedisLogger
     */
    protected $logger;

    /**
     * Sets the logger
     *
     * @param RedisLogger $logger A RedisLogger instance
     */
    public function setLogger(RedisLogger $logger = null)
    {
        $this->logger = $logger;
    }

    /**
     * Sets the connection wrapper class used to wrap an actual
     * connection object and enable logging.
     *
     * @param string $class Fully qualified name of the connection wrapper class.
     */
    public function setConnectionWrapperClass($class)
    {
        $this->wrapper = $class;
    }

    /**
     * {@inheritdoc}
     */
    public function create($parameters, IServerProfile $profile = null)
    {
        $connection = parent::create($parameters);

        if ($connection->getParameters()->logging) {
            if (null !== $this->wrapper) {
                $wrapper = $this->wrapper;
                $connection = new $wrapper($connection);
            }
            $connection->setLogger($this->logger);
        }

        return $connection;
    }
}
