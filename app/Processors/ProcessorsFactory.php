<?php

namespace App\Processors;

use Exception;

/**
 * TODO: move to DB
 * Class ProcessorsFactory
 *
 * @package App\Processors
 */
class ProcessorsFactory
{
    /**
     * Available processors
     */
    const PROCESSORS_IDS = [
        Paymentwall::ID => Paymentwall::class
    ];

    /**
     * Available processors
     */
    const PROCESSORS_NAMES = [
        Paymentwall::SYSTEM_NAME => Paymentwall::class
    ];

    /**
     * @param int $id
     *
     * @return Processor
     * @throws Exception
     */
    public static function getById(int $id)
    {
        if (!isset(self::PROCESSORS_IDS[$id])) {
            throw new Exception("Unsupported processor id: $id");
        }

        $className = self::PROCESSORS_IDS[$id];

        return new $className();
    }

    /**
     * @param string $systemName
     *
     * @return Processor
     * @throws Exception
     */
    public static function getBySystemName(string $systemName)
    {
        if (!isset(self::PROCESSORS_NAMES[$systemName])) {
            throw new Exception("Unsupported processor system name: $systemName");
        }

        $className = self::PROCESSORS_NAMES[$systemName];

        return new $className();
    }
}
