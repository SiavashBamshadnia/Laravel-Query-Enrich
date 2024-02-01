<?php

namespace Workbench\BaseTest;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use sbamtr\LaravelQueryEnrich\QE;
use Workbench\App\Models\Author;
use function sbamtr\LaravelQueryEnrich\c;

abstract class BaseBasicFunctionsTest extends BaseTest
{
    public function testEscapeCondition()
    {
        Author::insert([
            'first_name' => $this->faker->firstName,
            'last_name'  => $this->faker->lastName,
            'email'      => $this->faker->email,
        ]);

        $queryResult = Author::whereRaw(
            QE::condition(c('first_name'), 'like', '%%')
        )->count();

        self::assertEquals(0, $queryResult);
    }

    public function testEscapeDatetime()
    {
        $datetime = Carbon::parse($this->faker->dateTime)->toDateTimeString();

        $queryResult = DB::selectOne('SELECT '.QE::raw('?', [$datetime])->as('result'));

        self::assertEquals($datetime, $queryResult->result);
    }

    public function testEscapeNull()
    {
        $queryResult = DB::selectOne('SELECT '.QE::raw('?', [null])->as('result'));

        self::assertNull($queryResult->result);
    }
}
