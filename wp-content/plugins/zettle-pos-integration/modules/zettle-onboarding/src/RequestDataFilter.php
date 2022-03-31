<?php

declare(strict_types=1);

namespace Inpsyde\Zettle\Onboarding;

class RequestDataFilter
{

    /**
     * @var array
     */
    private $requestData;

    public function __construct(array $requestData)
    {
        $this->requestData = $requestData;
    }

    /**
     * @param mixed ...$things
     * phpcs:disable Inpsyde.CodeQuality.ArgumentTypeDeclaration.NoArgumentType
     *
     * @return bool
     */
    public function guard(...$things): bool
    {
        foreach ($things as $thing) {
            if (!$this->guardField($thing)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param $thing
     * phpcs:disable Inpsyde.CodeQuality.ArgumentTypeDeclaration.NoArgumentType
     *
     * @return bool
     */
    private function guardField($thing): bool
    {
        if (is_string($thing)) {
            $found = array_key_exists($thing, $this->requestData);

            if (!$found) {
                return false;
            }
        }

        if (is_array($thing)) {
            $key = $thing[0];

            if (!array_key_exists($key, $this->requestData)) {
                return false;
            }

            $compare = $thing[1];

            $found = filter_var($this->requestData[$key]);

            if ($found !== $compare) {
                return false;
            }
        }

        return true;
    }
}
