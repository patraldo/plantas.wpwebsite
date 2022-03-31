<?php

declare(strict_types=1);

namespace Inpsyde\Zettle\Container;

use Dhii\Collection\ClearableContainerInterface;
use Dhii\Collection\MutableContainerInterface;
use Exception;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class WpOptionContainer implements ContainerInterface, MutableContainerInterface, ClearableContainerInterface
{

    /**
     * @var array
     */
    private $options;

    /**
     * @var string
     */
    private $optionKey;

    public function __construct(string $optionKey)
    {
        $this->options = get_option($optionKey, []);
        $this->optionKey = $optionKey;
    }

    public function clear(): void
    {
        update_option($this->optionKey, []);
    }

    public function get($id)
    {
        if ($this->has($id)) {
            return $this->options[$id];
        }
        $exceptionMessage = sprintf(
            'Could not find entry %s in the "%s" wp options array',
            $id,
            $this->optionKey
        );
        throw new class ($exceptionMessage) extends Exception implements NotFoundExceptionInterface {

        };
    }

    public function has($id)
    {
        return isset($this->options[$id]);
    }

    public function set(string $key, $value): void
    {
        $this->options[$key] = $value;
        update_option($this->optionKey, $this->options);
    }

    public function unset(string $key): void
    {
        unset($this->options[$key]);
        update_option($this->optionKey, $this->options);
    }
}
