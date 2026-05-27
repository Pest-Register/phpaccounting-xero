<?php

namespace PHPAccounting\Xero\Foundation\Traits;

trait ParametersTrait
{
    protected array $parameters = [];

    public function getParameters(): array
    {
        return $this->parameters;
    }

    public function getParameter(string $key)
    {
        return $this->parameters[$key] ?? null;
    }

    public function setParameter(string $key, $value): static
    {
        $this->parameters[$key] = $value;
        return $this;
    }

    public function initialize(array $parameters = []): static
    {
        $this->parameters = [];

        foreach ($parameters as $key => $value) {
            $this->setParameter($key, $value);
        }

        return $this;
    }
}
