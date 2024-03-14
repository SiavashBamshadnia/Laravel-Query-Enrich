<?php

namespace sbamtr\LaravelQueryEnrich;

use DateTime;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\Query\Expression;
use Illuminate\Support\Facades\DB;
use ReflectionClass;
use sbamtr\LaravelQueryEnrich\Exception\DatabaseNotSupportedException;

/**
 * Abstract class representing a database function as an SQL expression.
 *
 * This class is used to represent SQL functions in a database-agnostic way and
 * includes methods for converting to SQL strings, configuring function behavior,
 * and escaping parameters for safe SQL execution.
 */
abstract class DBFunction extends Expression
{
    public function __construct()
    {
    }

    /**
     * Indicates if the function result is a boolean value.
     *
     * @var bool
     */
    protected const IS_BOOLEAN = false;

    /**
     * The alias to use for the function in an SQL query.
     *
     * @var string|null
     */
    protected string|null $alias = null;

    /**
     * An array to keep track of SQLite configured functions.
     *
     * @var array
     */
    protected static array $sqliteConfiguredFunctions = [];

    /**
     * Convert the function to an SQL string.
     *
     * @return string
     */
    abstract protected function getQuery(): string;

    /**
     * Configure the function behavior specifically for SQLite.
     *
     * This method is intended to be overridden by subclasses to provide SQLite-specific configurations.
     *
     * @return void
     */
    public function configureForSqlite(): void
    {
        // Write sqlite function configurations here
    }

    /**
     * Configure the function behavior for SQLite, ensuring it is done only once.
     *
     * If the function has already been configured for SQLite, it skips the configuration.
     *
     * @return void
     */
    public function configureForSqliteOnce(): void
    {
        if (in_array(static::class, self::$sqliteConfiguredFunctions)) {
            return;
        }
        self::$sqliteConfiguredFunctions[] = static::class;
        $this->configureForSqlite();
    }

    /**
     * Get the first character used to surround database identifiers.
     *
     * @return string
     */
    public function getFirstSurroundingCharacter(): string
    {
        switch ($this->getDatabaseEngine()) {
            case EDatabaseEngine::MySQL:
                return '`';
            case EDatabaseEngine::PostgreSQL:
            case EDatabaseEngine::SQLite:
                return '"';
            case EDatabaseEngine::SQLServer:
                return '[';
        }
    }

    /**
     * Get the last character used to surround database identifiers.
     *
     * @return string
     */
    public function getLastSurroundingCharacter(): string
    {
        switch ($this->getDatabaseEngine()) {
            case EDatabaseEngine::MySQL:
                return '`';
            case EDatabaseEngine::PostgreSQL:
            case EDatabaseEngine::SQLite:
                return '"';
            case EDatabaseEngine::SQLServer:
                return ']';
        }
    }

    /**
     * Get the database engine used by the connection.
     *
     * @throws DatabaseNotSupportedException If the database engine is unknown.
     *
     * @return EDatabaseEngine
     */
    public function getDatabaseEngine(): EDatabaseEngine
    {
        $driver = config('database.connections')[config('database.default')]['driver'];
        switch ($driver) {
            case 'mysql':
            case 'mariadb':
                return EDatabaseEngine::MySQL;
            case 'pgsql':
                return EDatabaseEngine::PostgreSQL;
            case 'sqlite':
                return EDatabaseEngine::SQLite;
            case 'sqlsrv':
                return EDatabaseEngine::SQLServer;
            default:
                throw new DatabaseNotSupportedException('Unknown database engine');
        }
    }

    /**
     * Escape a parameter for safe inclusion in an SQL query.
     *
     * @param mixed $parameter The parameter to escape.
     *
     * @return mixed The escaped parameter.
     */
    protected function escape(mixed $parameter): mixed
    {
        if (is_numeric($parameter)) {
            return $parameter + 0;
        }
        if ($parameter === null) {
            return 'null';
        }
        if ($parameter instanceof DBFunction) {
            return $parameter->toSql();
        }
        if ($parameter instanceof EloquentBuilder || $parameter instanceof QueryBuilder) {
            return $parameter->toSql();
        }
        if (is_array($parameter)) {
            $queryGrammar = DB::connection()->getQueryGrammar();
            $count = count($parameter);
            for ($i = 0; $i < $count; $i++) {
                $parameter[$i] = $queryGrammar->escape($parameter[$i]);
            }

            return $parameter;
        }

        if ($parameter instanceof DateTime
            ||
            (
                is_string($parameter)
                &&
                $datetime = DateTime::createFromFormat('Y-m-d H:i:s', $parameter)
            )
        ) {
            if (isset($datetime)) {
                $parameter = $datetime;
            }
            $parameter = $parameter->format('Y-m-d H:i:s');
            if ($this->getDatabaseEngine() == EDatabaseEngine::PostgreSQL) {
                $parameter = DB::escape($parameter);

                return "timestamp $parameter";
            }
        }

        if (is_object($parameter)) {
            $reflection = new ReflectionClass($parameter::class);
            if ($reflection->isEnum()) {
                return strtolower($parameter->value);
            }
        }
        if (is_string($parameter)) {
            $parameter = addcslashes($parameter, '%');
        }

        return DB::escape($parameter);
    }

    /**
     * Set the alias for the function and return the current instance.
     *
     * @param string $alias The alias to set.
     *
     * @return static
     */
    public function as(string $alias): static
    {
        $this->alias = $alias;

        return $this;
    }

    /**
     * Convert the function to an SQL string with an optional alias.
     *
     * @return string
     */
    public function toSql(): string
    {
        if ($this->getDatabaseEngine() == EDatabaseEngine::SQLite) {
            $this->configureForSqliteOnce();
        }
        $sql = $this->getQuery();
        $alias = $this->alias;
        $firstSurroundingCharacter = $this->getFirstSurroundingCharacter();
        $lastSurroundingCharacter = $this->getLastSurroundingCharacter();
        if ($alias !== null) {
            if ($this->getDatabaseEngine() == EDatabaseEngine::SQLServer && static::IS_BOOLEAN) {
                $sql = QE::case()
                    ->when(QE::raw($sql))
                    ->then(1)
                    ->else(0)
                    ->getQuery();
            }

            $sql .= " as $firstSurroundingCharacter$alias$lastSurroundingCharacter";
        }

        return $sql;
    }

    public function __toString(): string
    {
        return $this->toSql();
    }

    public function getValue(...$parameters)
    {
        return $this->toSql();
    }

    /**
     * Get the SQL string for a function call with optional parameters.
     *
     * @param string $function   The function name.
     * @param mixed  $parameters The function parameters.
     *
     * @return string
     */
    public function getFunctionCallSql(string $function, array $parameters = null): string
    {
        if ($parameters === null) {
            return "$function()";
        }
        $parametersCount = count($parameters);
        for ($i = 0; $i < $parametersCount; $i++) {
            $parameters[$i] = $this->escape($parameters[$i]);
        }
        $implodedParameters = implode(', ', $parameters);

        return "$function($implodedParameters)";
    }

    /**
     * Get the SQL string for an expression with parameters separated by an operator.
     *
     * @param string $operator   The operator to separate parameters.
     * @param mixed  $parameters The parameters for the expression.
     *
     * @return string
     */
    public function getOperatorSeparatedSql(string $operator, array $parameters): string
    {
        $parametersCount = count($parameters);
        for ($i = 0; $i < $parametersCount; $i++) {
            $parameters[$i] = $this->escape($parameters[$i]);
        }
        $implodedParameters = implode(" $operator ", $parameters);

        return "($implodedParameters)";
    }
}
