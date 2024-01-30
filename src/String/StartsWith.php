<?php

namespace sbamtr\LaravelQueryEnrich\String;

use Illuminate\Support\Facades\DB;
use sbamtr\LaravelQueryEnrich\DBFunction;
use sbamtr\LaravelQueryEnrich\QE;

/**
 * Determines if a given string starts with a given substring.
 */
class StartsWith extends DBFunction
{
    protected const IS_BOOLEAN = true;
    private mixed $haystack;
    private mixed $needle;

    public function __construct(mixed $haystack, mixed $needle)
    {
        $this->haystack = $haystack;
        $this->needle = $needle;
    }

    protected function getQuery(): string
    {
        $haystack = $this->escape($this->haystack);
        $needle = QE::concat($this->needle, QE::raw(DB::escape('%')));
        return "$haystack like $needle";
    }
}