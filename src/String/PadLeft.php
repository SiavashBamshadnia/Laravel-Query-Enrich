<?php

namespace sbamtr\LaravelQueryEnrich\String;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use sbamtr\LaravelQueryEnrich\DBFunction;
use sbamtr\LaravelQueryEnrich\EDatabaseEngine;

/**
 * Left-pads a string with another string, to a certain length.
 */
class PadLeft extends DBFunction
{
    private mixed $string;
    private mixed $length;
    private mixed $pad;

    public function __construct(mixed $string, mixed $length, mixed $pad = ' ')
    {
        $this->string = $string;
        $this->length = $length;
        $this->pad = $pad;
    }

    protected function getQuery(): string
    {
        switch ($this->getDatabaseEngine()) {
            case EDatabaseEngine::MySQL:
            case EDatabaseEngine::PostgreSQL:
            case EDatabaseEngine::SQLite:
                return $this->getFunctionCallSql('lpad', [$this->string, $this->length, $this->pad]);
            case EDatabaseEngine::SQLServer:
                $string = $this->escape($this->string);
                $length = $this->escape($this->length);
                $pad = $this->escape($this->pad);

                return "left(replicate($pad, $length), $length - len($string)) + $string";
        }
    }

    public function configureForSqlite(): void
    {
        DB::connection()->getPdo()->sqliteCreateFunction('lpad', function ($string, $length, $pad) {
            return Str::padLeft($string, $length, $pad);
        }, 3);
    }
}
