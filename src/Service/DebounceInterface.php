<?php


namespace Kikwik\DebounceBundle\Service;


interface DebounceInterface
{
    public function check(string $email): array;
}