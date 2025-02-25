<?php

namespace Workbench\BaseTest;

use DateTime;
use Illuminate\Database\QueryException;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use sbamtr\LaravelQueryEnrich\Date\Unit;
use sbamtr\LaravelQueryEnrich\Exception\InvalidArgumentException;
use sbamtr\LaravelQueryEnrich\QE;
use Workbench\App\Models\Author;
use Workbench\App\Models\Book;

use function sbamtr\LaravelQueryEnrich\c;

abstract class BaseProjectionTest extends BaseTest
{
    public function testColumn()
    {
        Author::insert([
            'first_name' => $this->faker->firstName,
            'last_name'  => $this->faker->lastName,
            'email'      => $this->faker->email,
        ]);

        $author = Author::select(
            'authors.first_name',
            c('first_name')->as('name')
        )->first();

        $actual = $author->name;
        $expected = $author->first_name;

        self::assertEquals($expected, $actual);
    }

    public function testRaw()
    {
        $number_1 = $this->faker->randomNumber();
        $number_2 = $this->faker->randomNumber();

        $queryResult = DB::selectOne(
            'select '.QE::raw('?+?', [$number_1, $number_2])->as('result'),
        );

        $actual = $queryResult->result;
        $expected = $number_1 + $number_2;

        self::assertEquals($expected, $actual);
    }

    public function testRawInvalidArgumentException()
    {
        self::expectException(InvalidArgumentException::class);
        QE::raw('? + ?', [1]);
    }

    public function testCaseWhen()
    {
        $author = Author::create([
            'first_name' => $this->faker->firstName,
            'last_name'  => $this->faker->lastName,
            'email'      => $this->faker->email,
        ]);
        Book::insert([
            [
                'title'       => $this->faker->title,
                'description' => $this->faker->text,
                'author_id'   => $author->id,
                'price'       => 150,
                'year'        => $this->faker->year,
            ],
            [
                'title'       => $this->faker->title,
                'description' => $this->faker->text,
                'author_id'   => $author->id,
                'price'       => 62,
                'year'        => $this->faker->year,
            ],
            [
                'title'       => $this->faker->title,
                'description' => $this->faker->text,
                'author_id'   => $author->id,
                'price'       => 20,
                'year'        => $this->faker->year,
            ],
        ]);

        $books = Book::select(
            QE::case()
                ->when(c('price'), '>', 100)->then('expensive')
                ->when(QE::condition(50, '<', c('price')), QE::condition(c('price'), '<=', 100))->then('moderate')
                ->else('affordable')
                ->as('price_category')
        )->get();

        self::assertEquals('expensive', $books[0]->price_category);
        self::assertEquals('moderate', $books[1]->price_category);
        self::assertEquals('affordable', $books[2]->price_category);
    }

    public function testCaseWhenInvalidArgumentException()
    {
        self::expectException(InvalidArgumentException::class);
        QE::case()->when(1, 2, 3, 4)->then('expensive');
    }

    public function testIf()
    {
        $author = Author::create([
            'first_name' => $this->faker->firstName,
            'last_name'  => $this->faker->lastName,
            'email'      => $this->faker->email,
        ]);
        Book::insert([
            [
                'title'       => $this->faker->title,
                'description' => $this->faker->text,
                'author_id'   => $author->id,
                'price'       => 150,
                'year'        => $this->faker->year,
            ],
            [
                'title'       => $this->faker->title,
                'description' => $this->faker->text,
                'author_id'   => $author->id,
                'price'       => 62,
                'year'        => $this->faker->year,
            ],
        ]);

        $books = Book::select(
            QE::if(QE::condition(c('price'), '>', 100), 'expensive', 'not expensive')->as('price_category')
        )->get();

        self::assertEquals('expensive', $books[0]->price_category);
        self::assertEquals('not expensive', $books[1]->price_category);
    }

    public function testCoalesce()
    {
        $author = Author::create([
            'first_name' => $this->faker->firstName,
            'last_name'  => $this->faker->lastName,
            'email'      => $this->faker->email,
        ]);
        Book::insert([
            'title'     => $this->faker->title,
            'author_id' => $author->id,
            'price'     => 150,
            'year'      => $this->faker->year,
        ]);

        $book = Book::select(
            QE::coalesce(c('description'), '----')->as('description')
        )->first();

        $actualDescription = $book->description;
        $expectedDescription = '----';

        self::assertEquals($expectedDescription, $actualDescription);
    }

    public function testIsNull()
    {
        $author = Author::create([
            'first_name' => $this->faker->firstName,
            'last_name'  => $this->faker->lastName,
            'email'      => $this->faker->email,
        ]);
        Book::insert([
            [
                'title'       => $this->faker->title,
                'author_id'   => $author->id,
                'price'       => 150,
                'year'        => $this->faker->year,
                'description' => null,
            ],
            [
                'title'       => $this->faker->title,
                'author_id'   => $author->id,
                'price'       => 150,
                'year'        => $this->faker->year,
                'description' => $this->faker->text,
            ],
        ]);

        $books = Book::select(
            QE::isNull(c('description'))->as('is_null')
        )->get();

        $actualIsNull = $books[0]->is_null;

        self::assertEquals(true, $actualIsNull);

        $actualIsNull = $books[1]->is_null;

        self::assertEquals(false, $actualIsNull);
    }

    public function testCondition()
    {
        $queryResult = DB::selectOne('select '.
            QE::condition(1, 1)->as('equal').','.
            QE::condition(1, '=', 1)->as('equal_2').','.
            QE::condition(2, '>', 1)->as('greater').','.
            QE::condition(2, '>=', 1)->as('greater_equal').','.
            QE::condition(2, '>=', 2)->as('greater_equal_2').','.
            QE::condition(1, '<', 2)->as('less').','.
            QE::condition(1, '<=', 2)->as('less_equal').','.
            QE::condition(1, '<=', 1)->as('less_equal_2').','.
            QE::condition('a', 'like', 'a')->as('like').','.
            QE::condition(1, '<>', 2)->as('not_equal').','.
            QE::condition(1, '!=', 2)->as('not_equal_2').','.
            QE::condition('a', 'not like', 'b')->as('not_like').','.
            QE::condition('b', 'in', ['a', 'b', 'c', 'd'])->as('in').','.
            QE::condition('e', 'not in', ['a', 'b', 'c', 'd'])->as('not_in').','.
            QE::condition(1, 'is not', null)->as('is_not')
        );

        self::assertEquals(1, $queryResult->equal_2);
        self::assertEquals(1, $queryResult->equal);
        self::assertEquals(1, $queryResult->greater);
        self::assertEquals(1, $queryResult->greater_equal);
        self::assertEquals(1, $queryResult->greater_equal_2);
        self::assertEquals(1, $queryResult->less);
        self::assertEquals(1, $queryResult->less_equal);
        self::assertEquals(1, $queryResult->less_equal_2);
        self::assertEquals(1, $queryResult->like);
        self::assertEquals(1, $queryResult->not_equal);
        self::assertEquals(1, $queryResult->not_equal_2);
        self::assertEquals(1, $queryResult->not_like);
        self::assertEquals(1, $queryResult->in);
        self::assertEquals(1, $queryResult->not_in);
        self::assertEquals(1, $queryResult->is_not);
    }

    public function testAddDate()
    {
        $datetime = $this->faker->dateTime;

        $queryResult = DB::selectOne('select '.
            QE::addDate($datetime, 2, Unit::SECOND)->as('created_at_modified_second').','.
            QE::addDate($datetime, 2, Unit::MINUTE)->as('created_at_modified_minute').','.
            QE::addDate($datetime, 2, Unit::HOUR)->as('created_at_modified_hour').','.
            QE::addDate($datetime, 2, Unit::DAY)->as('created_at_modified_day').','.
            QE::addDate($datetime, 2, Unit::WEEK)->as('created_at_modified_week').','.
            QE::addDate($datetime, 2, Unit::MONTH)->as('created_at_modified_month').','.
            QE::addDate($datetime, 2, Unit::QUARTER)->as('created_at_modified_quarter').','.
            QE::addDate($datetime, 2, Unit::YEAR)->as('created_at_modified_year'));

        self::assertEquals(Carbon::parse($datetime)->addSeconds(2)->toDateTimeString(), $queryResult->created_at_modified_second);
        self::assertEquals(Carbon::parse($datetime)->addMinutes(2)->toDateTimeString(), $queryResult->created_at_modified_minute);
        self::assertEquals(Carbon::parse($datetime)->addHours(2)->toDateTimeString(), $queryResult->created_at_modified_hour);
        self::assertEquals(Carbon::parse($datetime)->addDays(2)->toDateTimeString(), $queryResult->created_at_modified_day);
        self::assertEquals(Carbon::parse($datetime)->addWeeks(2)->toDateTimeString(), $queryResult->created_at_modified_week);
        self::assertNotFalse(DateTime::createFromFormat('Y-m-d H:i:s', $queryResult->created_at_modified_month));
        self::assertNotFalse(DateTime::createFromFormat('Y-m-d H:i:s', $queryResult->created_at_modified_quarter));
        self::assertNotFalse(DateTime::createFromFormat('Y-m-d H:i:s', $queryResult->created_at_modified_year));
    }

    public function testCurrentDate()
    {
        $author = DB::selectOne(
            'select '.QE::currentDate()->as('d')
        );

        $actual = $author->d;
        $expected = Carbon::now()->toDateString();

        self::assertEquals($expected, $actual);
    }

    public function testCurrentTime()
    {
        $queryResult = DB::selectOne(
            'select '.QE::currentTime()->as('result')
        );

        $actual = $queryResult->result;
        $expected = Carbon::now()->toTimeString();

        $hasLessThan5SecondsDifference = Carbon::parse($actual)->diffInSeconds($expected) < 5;
        self::assertTrue($hasLessThan5SecondsDifference);
    }

    public function testDate()
    {
        $datetime = $this->faker->dateTime;

        $author = DB::selectOne(
            'select '.QE::date($datetime)->as('result')
        );

        $actual = $author->result;
        $expected = Carbon::parse($datetime)->toDateString();

        self::assertEquals($expected, $actual);
    }

    public function testDateDiff()
    {
        $date1 = $this->faker->date;
        $date2 = $this->faker->date;

        $author = DB::selectOne(
            'select '.QE::dateDiff($date1, $date2)->as('result')
        );

        $actual = $author->result;
        $expected = Carbon::parse($date2)->diffInDays($date1, false);

        self::assertEquals($expected, $actual);
    }

    public function testHour()
    {
        $datetime = $this->faker->dateTime;

        $queryResult = DB::selectOne(
            'select '.QE::hour($datetime)->as('result')
        );

        $actual = $queryResult->result;
        $expected = Carbon::parse($datetime)->hour;

        self::assertEquals($expected, $actual);
    }

    public function testMinute()
    {
        $datetime = $this->faker->dateTime;

        $queryResult = DB::selectOne(
            'select '.QE::minute($datetime)->as('result')
        );

        $actual = $queryResult->result;
        $expected = Carbon::parse($datetime)->minute;

        self::assertEquals($expected, $actual);
    }

    public function testMonth()
    {
        $datetime = $this->faker->dateTime;

        $queryResult = DB::selectOne(
            'select '.QE::month($datetime)->as('result')
        );

        $actual = $queryResult->result;
        $expected = Carbon::parse($datetime)->month;

        self::assertEquals($expected, $actual);
    }

    public function testMonthName()
    {
        $datetime = $this->faker->dateTime;

        $queryResult = DB::selectOne(
            'select '.QE::monthName($datetime)->as('result')
        );

        $actual = $queryResult->result;
        $expected = Carbon::parse($datetime)->monthName;

        self::assertEquals($expected, $actual);
    }

    public function testNow()
    {
        $queryResult = DB::selectOne(
            'select '.QE::now()->as('result'),
        );

        $actual = $queryResult->result;
        $expected = Carbon::now()->toDateTimeString();

        $hasLessThan5SecondsDifference = Carbon::parse($actual)->diffInSeconds($expected) < 5;
        self::assertTrue($hasLessThan5SecondsDifference);
    }

    public function testSecond()
    {
        $datetime = $this->faker->dateTime;

        $queryResult = DB::selectOne(
            'select '.QE::second($datetime)->as('result')
        );

        $actual = $queryResult->result;
        $expected = Carbon::parse($datetime)->second;

        self::assertEquals($expected, $actual);
    }

    public function testSubDate()
    {
        $datetime = $this->faker->dateTime;

        $queryResult = DB::selectOne('select '.
            QE::subDate($datetime, 2, Unit::SECOND)->as('created_at_modified_second').','.
            QE::subDate($datetime, 2, Unit::MINUTE)->as('created_at_modified_minute').','.
            QE::subDate($datetime, 2, Unit::HOUR)->as('created_at_modified_hour').','.
            QE::subDate($datetime, 2, Unit::DAY)->as('created_at_modified_day').','.
            QE::subDate($datetime, 2, Unit::WEEK)->as('created_at_modified_week').','.
            QE::subDate($datetime, 2, Unit::MONTH)->as('created_at_modified_month').','.
            QE::subDate($datetime, 2, Unit::QUARTER)->as('created_at_modified_quarter').','.
            QE::subDate($datetime, 2, Unit::YEAR)->as('created_at_modified_year'));

        self::assertEquals(Carbon::parse($datetime)->subSeconds(2)->toDateTimeString(), $queryResult->created_at_modified_second);
        self::assertEquals(Carbon::parse($datetime)->subMinutes(2)->toDateTimeString(), $queryResult->created_at_modified_minute);
        self::assertEquals(Carbon::parse($datetime)->subHours(2)->toDateTimeString(), $queryResult->created_at_modified_hour);
        self::assertEquals(Carbon::parse($datetime)->subDays(2)->toDateTimeString(), $queryResult->created_at_modified_day);
        self::assertEquals(Carbon::parse($datetime)->subWeeks(2)->toDateTimeString(), $queryResult->created_at_modified_week);
        self::assertNotFalse(DateTime::createFromFormat('Y-m-d H:i:s', $queryResult->created_at_modified_month));
        self::assertNotFalse(DateTime::createFromFormat('Y-m-d H:i:s', $queryResult->created_at_modified_quarter));
        self::assertNotFalse(DateTime::createFromFormat('Y-m-d H:i:s', $queryResult->created_at_modified_year));
    }

    public function testTime()
    {
        $datetime = $this->faker->dateTime;

        $queryResult = DB::selectOne(
            'select '.QE::time($datetime)->as('result')
        );

        $actual = $queryResult->result;
        $expected = Carbon::parse($datetime)->toTimeString();

        self::assertEquals($expected, $actual);
    }

    public function testDayOfWeek()
    {
        $datetime = $this->faker->dateTime;

        $queryResult = DB::selectOne(
            'select '.QE::dayOfWeek($datetime)->as('result')
        );

        $actual = $queryResult->result;
        $expected = Carbon::parse($datetime)->dayOfWeek;

        self::assertEquals($expected, $actual);
    }

    public function testYear()
    {
        $datetime = $this->faker->dateTime;

        $queryResult = DB::selectOne(
            'select '.QE::year($datetime)->as('result')
        );

        $actual = $queryResult->result;
        $expected = Carbon::parse($datetime)->year;

        self::assertEquals($expected, $actual);
    }

    public function testAbs()
    {
        $number_1 = $this->faker->numberBetween(-1000, 0);
        $number_2 = $this->faker->numberBetween();

        $queryResult = DB::selectOne('select '.
            QE::abs($number_1)->as('result_1').','.
            QE::abs($number_2)->as('result_2'));

        $actual_1 = $queryResult->result_1;
        $expected_1 = abs($number_1);

        self::assertEquals($expected_1, $actual_1);

        $actual_2 = $queryResult->result_2;
        $expected_2 = abs($number_2);

        self::assertEquals($expected_2, $actual_2);
    }

    public function testAcos()
    {
        $number = $this->faker->randomFloat(min: -1, max: 1);

        $queryResult = DB::selectOne(
            'select '.QE::acos($number)->as('result'),
        );

        $actual = $queryResult->result;
        $expected = acos($number);

        self::assertEqualsWithDelta($expected, $actual, 0.001);
    }

    public function testAdd()
    {
        $number_1 = $this->faker->randomFloat();
        $number_2 = $this->faker->randomFloat();

        $queryResult = DB::selectOne(
            'select '.QE::add($number_1, $number_2)->as('result')
        );

        $actual = $queryResult->result;
        $expected = $number_1 + $number_2;

        self::assertEqualsWithDelta($expected, $actual, 0.001);
    }

    public function testAsin()
    {
        $number = $this->faker->randomFloat(min: -1, max: 1);

        $queryResult = DB::selectOne(
            'select '.QE::asin($number)->as('result'),
        );

        $actual = $queryResult->result;
        $expected = asin($number);

        self::assertEqualsWithDelta($expected, $actual, 0.001);
    }

    public function testAtan()
    {
        $number = $this->faker->randomFloat(min: -100, max: 100);

        $queryResult = DB::selectOne(
            'select '.QE::atan($number)->as('result'),
        );

        $actual = $queryResult->result;
        $expected = atan($number);

        self::assertEqualsWithDelta($expected, $actual, 0.001);
    }

    public function testAtan2()
    {
        $y = $this->faker->randomFloat();
        $x = $this->faker->randomFloat();

        $queryResult = DB::selectOne(
            'select '.QE::atan2($y, $x)->as('result'),
        );

        $actual = $queryResult->result;
        $expected = atan2($y, $x);

        self::assertEqualsWithDelta($expected, $actual, 0.001);
    }

    public function testAvg()
    {
        $author_1 = Author::create([
            'first_name' => $this->faker->firstName,
            'last_name'  => $this->faker->lastName,
            'email'      => $this->faker->email,
        ]);
        $author_2 = Author::create([
            'first_name' => $this->faker->firstName,
            'last_name'  => $this->faker->lastName,
            'email'      => $this->faker->email,
        ]);
        $count = $this->faker->numberBetween(2, 100);
        $booksToInsert = [];
        for ($i = 0; $i < $count; $i++) {
            $booksToInsert[] = [
                'title'       => $this->faker->title,
                'description' => $this->faker->text,
                'price'       => $this->faker->randomFloat(2, 1, 100),
                'year'        => $this->faker->year,
            ];
            if ($i % 2 == 0) {
                $booksToInsert[$i]['author_id'] = $author_1->id;
            } else {
                $booksToInsert[$i]['author_id'] = $author_2->id;
            }
        }
        Book::insert($booksToInsert);

        $books = Book::select(
            QE::avg(c('price'))->as('result')
        )->groupBy(
            'author_id'
        )->orderBy(
            'author_id'
        )->get();

        $actual_1 = $books[0]->result;
        $expected_1 = Book::where('author_id', 1)->avg('price');

        self::assertEqualsWithDelta($expected_1, $actual_1, 0.001);

        $actual_2 = $books[1]->result;
        $expected_2 = Book::where('author_id', 2)->avg('price');

        self::assertEqualsWithDelta($expected_2, $actual_2, 0.001);
    }

    public function testCeil()
    {
        $number = $this->faker->randomFloat();

        $queryResult = DB::selectOne(
            'select '.QE::ceil($number)->as('result')
        );

        $actual = $queryResult->result;
        $expected = ceil($number);

        self::assertEquals($expected, $actual);
    }

    public function testCos()
    {
        $number = $this->faker->randomFloat();

        $queryResult = DB::selectOne(
            'select '.QE::cos($number)->as('result')
        );

        $actual = $queryResult->result;
        $expected = cos($number);

        self::assertEqualsWithDelta($expected, $actual, 0.001);
    }

    public function testCot()
    {
        $number = $this->faker->randomFloat(2, 1, 10);

        $queryResult = DB::selectOne(
            'select '.QE::cot($number)->as('result')
        );

        $actual = $queryResult->result;
        $expected = 1 / tan($number);

        self::assertEqualsWithDelta($expected, $actual, 0.01);
    }

    public function testCount()
    {
        $author_1 = Author::create([
            'first_name' => $this->faker->firstName,
            'last_name'  => $this->faker->lastName,
            'email'      => $this->faker->email,
        ]);
        $author_2 = Author::create([
            'first_name' => $this->faker->firstName,
            'last_name'  => $this->faker->lastName,
            'email'      => $this->faker->email,
        ]);
        $count = $this->faker->numberBetween(2, 100);
        $booksToInsert = [];
        for ($i = 0; $i < $count; $i++) {
            $booksToInsert[] = [
                'title'       => $this->faker->title,
                'description' => $this->faker->text,
                'price'       => 100,
                'year'        => $this->faker->year,
            ];
            if ($i % 2 == 0) {
                $booksToInsert[$i]['author_id'] = $author_1->id;
            } else {
                $booksToInsert[$i]['author_id'] = $author_2->id;
            }
        }
        Book::insert($booksToInsert);

        $books = Book::select(
            QE::count(c('author_id'))->as('result')
        )->groupBy(
            'author_id'
        )->orderBy(
            'author_id'
        )->get();

        $actual_1 = $books[0]->result;
        $expected_1 = Book::where('author_id', 1)->count('author_id');

        self::assertEquals($expected_1, $actual_1);

        $actual_2 = $books[1]->result;
        $expected_2 = Book::where('author_id', 2)->count('author_id');

        self::assertEquals($expected_2, $actual_2);
    }

    public function testCountWithoutParameter()
    {
        $author_1 = Author::create([
            'first_name' => $this->faker->firstName,
            'last_name'  => $this->faker->lastName,
            'email'      => $this->faker->email,
        ]);
        $author_2 = Author::create([
            'first_name' => $this->faker->firstName,
            'last_name'  => $this->faker->lastName,
            'email'      => $this->faker->email,
        ]);
        $count = $this->faker->numberBetween(2, 100);
        $booksToInsert = [];
        for ($i = 0; $i < $count; $i++) {
            $booksToInsert[] = [
                'title'       => $this->faker->title,
                'description' => $this->faker->text,
                'price'       => 100,
                'year'        => $this->faker->year,
            ];
            if ($i % 2 == 0) {
                $booksToInsert[$i]['author_id'] = $author_1->id;
            } else {
                $booksToInsert[$i]['author_id'] = $author_2->id;
            }
        }
        Book::insert($booksToInsert);

        $books = Book::select(
            QE::count()->as('result')
        )->groupBy(
            'author_id'
        )->orderBy(
            'author_id'
        )->get();

        $actual_1 = $books[0]->result;
        $expected_1 = Book::where('author_id', 1)->count();

        self::assertEquals($expected_1, $actual_1);

        $actual_2 = $books[1]->result;
        $expected_2 = Book::where('author_id', 2)->count();

        self::assertEquals($expected_2, $actual_2);
    }

    public function testRadianToDegrees()
    {
        $number = $this->faker->randomFloat();

        $queryResult = DB::selectOne(
            'select '.QE::radianToDegrees($number)->as('result')
        );

        $actual = $queryResult->result;
        $expected = rad2deg($number);

        self::assertEqualsWithDelta($expected, $actual, 0.001);
    }

    public function testDivide()
    {
        $number_1 = $this->faker->randomFloat(2);
        $number_2 = $this->faker->randomFloat(2, 1);

        $queryResult = DB::selectOne(
            'select '.QE::divide($number_1, $number_2)->as('result')
        );

        $actual = $queryResult->result;
        $expected = $number_1 / $number_2;

        self::assertEqualsWithDelta($expected, $actual, 0.001);
    }

    public function testExp()
    {
        $number = $this->faker->randomFloat(max: 10);

        $queryResult = DB::selectOne(
            'select '.QE::exp($number)->as('result')
        );

        $actual = $queryResult->result;
        $expected = exp($number);

        self::assertEqualsWithDelta($expected, $actual, 0.001);
    }

    public function testFloor()
    {
        $number = $this->faker->randomFloat();

        $queryResult = DB::selectOne(
            'select '.QE::floor($number)->as('result')
        );

        $actual = $queryResult->result;
        $expected = floor($number);

        self::assertEquals($expected, $actual);
    }

    public function testGreatest()
    {
        $array = [];
        $count = $this->faker->numberBetween(2, 100);
        for ($i = 0; $i < $count; $i++) {
            $array[] = $this->faker->randomFloat();
        }

        $queryResult = DB::selectOne(
            'select '.QE::greatest(...$array)->as('result')
        );

        $actual = $queryResult->result;
        $expected = max($array);

        self::assertEqualsWithDelta($expected, $actual, 0.001);
    }

    public function testLeast()
    {
        $array = [];
        $count = $this->faker->numberBetween(2, 100);
        for ($i = 0; $i < $count; $i++) {
            $array[] = $this->faker->randomFloat();
        }

        $queryResult = DB::selectOne(
            'select '.QE::least(...$array)->as('result')
        );

        $actual = $queryResult->result;
        $expected = min($array);

        self::assertEqualsWithDelta($expected, $actual, 0.001);
    }

    public function testLn()
    {
        $number = $this->faker->randomFloat(min: 1);

        $queryResult = DB::selectOne(
            'select '.QE::ln($number)->as('result')
        );

        $actual = $queryResult->result;
        $expected = log($number);

        self::assertEqualsWithDelta($expected, $actual, 0.001);
    }

    public function testLog()
    {
        $number = $this->faker->numberBetween(2, 1000);
        $base = $this->faker->numberBetween(2, 1000);

        $queryResult = DB::selectOne(
            'select '.QE::log($number, $base)->as('result')
        );

        $actual = $queryResult->result;
        $expected = log($number, $base);

        self::assertEqualsWithDelta($expected, $actual, 0.01, "$number, $base");
    }

    public function testMax()
    {
        $author_1 = Author::create([
            'first_name' => $this->faker->firstName,
            'last_name'  => $this->faker->lastName,
            'email'      => $this->faker->email,
        ]);
        $author_2 = Author::create([
            'first_name' => $this->faker->firstName,
            'last_name'  => $this->faker->lastName,
            'email'      => $this->faker->email,
        ]);
        $count = $this->faker->numberBetween(2, 100);
        $booksToInsert = [];
        for ($i = 0; $i < $count; $i++) {
            $booksToInsert[] = [
                'title'       => $this->faker->title,
                'description' => $this->faker->text,
                'price'       => $this->faker->randomFloat(2, 0, 100),
                'year'        => $this->faker->year,
            ];
            if ($i % 2 == 0) {
                $booksToInsert[$i]['author_id'] = $author_1->id;
            } else {
                $booksToInsert[$i]['author_id'] = $author_2->id;
            }
        }
        Book::insert($booksToInsert);

        $books = Book::select(
            QE::max(c('price'))->as('result')
        )->groupBy(
            'author_id'
        )->orderBy(
            'author_id'
        )->get();

        $actual_1 = $books[0]->result;
        $expected_1 = Book::where('author_id', 1)->max('price');

        self::assertEquals($expected_1, $actual_1);

        $actual_2 = $books[1]->result;
        $expected_2 = Book::where('author_id', 2)->max('price');

        self::assertEquals($expected_2, $actual_2);
    }

    public function testMin()
    {
        $author_1 = Author::create([
            'first_name' => $this->faker->firstName,
            'last_name'  => $this->faker->lastName,
            'email'      => $this->faker->email,
        ]);
        $author_2 = Author::create([
            'first_name' => $this->faker->firstName,
            'last_name'  => $this->faker->lastName,
            'email'      => $this->faker->email,
        ]);
        $count = $this->faker->numberBetween(2, 100);
        $booksToInsert = [];
        for ($i = 0; $i < $count; $i++) {
            $booksToInsert[] = [
                'title'       => $this->faker->title,
                'description' => $this->faker->text,
                'price'       => $this->faker->randomFloat(min: 0, max: 100),
                'year'        => $this->faker->year,
            ];
            if ($i % 2 == 0) {
                $booksToInsert[$i]['author_id'] = $author_1->id;
            } else {
                $booksToInsert[$i]['author_id'] = $author_2->id;
            }
        }
        Book::insert($booksToInsert);

        $books = Book::select(
            QE::min(c('price'))->as('result')
        )->groupBy(
            'author_id'
        )->orderBy(
            'author_id'
        )->get();

        $actual_1 = $books[0]->result;
        $expected_1 = Book::where('author_id', 1)->min('price');

        self::assertEquals($expected_1, $actual_1);

        $actual_2 = $books[1]->result;
        $expected_2 = Book::where('author_id', 2)->min('price');

        self::assertEquals($expected_2, $actual_2);
    }

    public function testMod()
    {
        $number_1 = $this->faker->randomNumber();
        $number_2 = $this->faker->numberBetween(1);

        $queryResult = DB::selectOne(
            'select '.QE::mod($number_1, $number_2)->as('result')
        );

        $actual = $queryResult->result;
        $expected = $number_1 % $number_2;

        self::assertEquals($expected, $actual);
    }

    public function testMultiply()
    {
        $number_1 = $this->faker->randomFloat(2, 1, 1000);
        $number_2 = $this->faker->randomFloat(2, 1, 1000);

        $queryResult = DB::selectOne(
            'select '.QE::multiply($number_1, $number_2)->as('result')
        );

        $actual = $queryResult->result;
        $expected = $number_1 * $number_2;

        self::assertEqualsWithDelta($expected, $actual, 0.001);
    }

    public function testPi()
    {
        $queryResult = DB::selectOne(
            'select '.QE::pi()->as('result')
        );

        $actual = $queryResult->result;
        $expected = pi();

        self::assertEqualsWithDelta($expected, $actual, 0.001);
    }

    public function testPow()
    {
        $number_1 = $this->faker->numberBetween(2, 5);
        $number_2 = $this->faker->numberBetween(2, 5);

        $queryResult = DB::selectOne(
            'select '.QE::pow($number_1, $number_2)->as('result')
        );

        $actual = $queryResult->result;
        $expected = pow($number_1, $number_2);

        self::assertEqualsWithDelta($expected, $actual, 0.001, "$number_1, $number_2");
    }

    public function testDegreesToRadian()
    {
        $number = $this->faker->randomFloat();

        $queryResult = DB::selectOne(
            'select '.QE::degreesToRadian($number)->as('result')
        );

        $actual = $queryResult->result;
        $expected = deg2rad($number);

        self::assertEqualsWithDelta($expected, $actual, 0.001);
    }

    public function testRand()
    {
        $queryResult_1 = DB::selectOne('select '.QE::rand()->as('rand'));
        self::assertIsNumeric($queryResult_1->rand);

        if ($this->getDatabaseEngine() == 'pgsql') {
            self::expectException(InvalidArgumentException::class);
        }
        $queryResult_2 = DB::selectOne('select '.
            QE::rand(1)->as('rand_with_seed_1').','.
            QE::rand(1)->as('rand_with_seed_2'));

        self::assertEquals($queryResult_2->rand_with_seed_1, $queryResult_2->rand_with_seed_2);
    }

    public function testRound()
    {
        $number = $this->faker->randomFloat();

        $queryResult = DB::selectOne('select '.
            QE::round($number)->as('round_1').','.
            QE::round($number, 2)->as('round_2'));

        self::assertEquals(round($number), $queryResult->round_1);
        self::assertEquals(round($number, 2), $queryResult->round_2);
    }

    public function testSign()
    {
        $number = $this->faker->randomFloat(min: -100, max: 100);

        $queryResult = DB::selectOne('select '.QE::sign($number)->as('result'));

        $expected = $number / abs($number);
        $actual = $queryResult->result;

        self::assertEquals($expected, $actual);
    }

    public function testSin()
    {
        $number = $this->faker->randomFloat();

        $queryResult = DB::selectOne(
            'select '.QE::sin($number)->as('result')
        );

        $actual = $queryResult->result;
        $expected = sin($number);

        self::assertEqualsWithDelta($expected, $actual, 0.001);
    }

    public function testSqrt()
    {
        $number = $this->faker->randomFloat();

        $queryResult = DB::selectOne(
            'select '.QE::sqrt($number)->as('result')
        );

        $actual = $queryResult->result;
        $expected = sqrt($number);

        self::assertEqualsWithDelta($expected, $actual, 0.001);
    }

    public function testSubtract()
    {
        $number_1 = $this->faker->randomFloat();
        $number_2 = $this->faker->randomFloat();

        $queryResult = DB::selectOne(
            'select '.QE::subtract($number_1, $number_2)->as('result')
        );

        $actual = $queryResult->result;
        $expected = $number_1 - $number_2;

        self::assertEqualsWithDelta($expected, $actual, 0.001);
    }

    public function testSum()
    {
        $author_1 = Author::create([
            'first_name' => $this->faker->firstName,
            'last_name'  => $this->faker->lastName,
            'email'      => $this->faker->email,
        ]);
        $author_2 = Author::create([
            'first_name' => $this->faker->firstName,
            'last_name'  => $this->faker->lastName,
            'email'      => $this->faker->email,
        ]);
        $count = $this->faker->numberBetween(2, 100);
        $booksToInsert = [];
        for ($i = 0; $i < $count; $i++) {
            $booksToInsert[] = [
                'title'       => $this->faker->title,
                'description' => $this->faker->text,
                'price'       => $this->faker->randomFloat(2, 1, 100),
                'year'        => $this->faker->year,
            ];
            if ($i % 2 == 0) {
                $booksToInsert[$i]['author_id'] = $author_1->id;
            } else {
                $booksToInsert[$i]['author_id'] = $author_2->id;
            }
        }
        Book::insert($booksToInsert);

        $books = Book::select(
            QE::sum(c('price'))->as('result')
        )->groupBy(
            'author_id'
        )->orderBy(
            'author_id'
        )->get();

        $actual_1 = $books[0]->result;
        $expected_1 = Book::where('author_id', 1)->sum('price');

        self::assertEqualsWithDelta($expected_1, $actual_1, 0.001);

        $actual_2 = $books[1]->result;
        $expected_2 = Book::where('author_id', 2)->sum('price');

        self::assertEqualsWithDelta($expected_2, $actual_2, 0.001);
    }

    public function testTan()
    {
        $number = $this->faker->randomFloat(2, 1, 10);

        $queryResult = DB::selectOne(
            'select '.QE::tan($number)->as('result')
        );

        $actual = $queryResult->result;
        $expected = tan($number);

        self::assertEqualsWithDelta($expected, $actual, 0.01);
    }

    public function testConcat()
    {
        Author::insert([
            'first_name' => $this->faker->firstName,
            'last_name'  => $this->faker->lastName,
            'email'      => $this->faker->email,
        ]);
        $author = Author::select(
            'first_name',
            'last_name',
            QE::concat(1, 2, 3, c('first_name'), ' ', c('last_name'))->as('result')
        )->first();

        $actual = $author->result;
        $expected = '123'.$author->first_name.' '.$author->last_name;

        self::assertEquals($expected, $actual);
    }

    public function testConcatWS()
    {
        Author::insert([
            'first_name' => $this->faker->firstName,
            'last_name'  => $this->faker->lastName,
            'email'      => $this->faker->email,
        ]);
        $author = Author::select(
            'first_name',
            'last_name',
            QE::concatWS(' ', c('first_name'), c('last_name'))->as('result')
        )->first();

        $actual = $author->result;
        $expected = $author->first_name.' '.$author->last_name;

        self::assertEquals($expected, $actual);
    }

    public function testLeft()
    {
        $queryResult = DB::selectOne(
            'select '.QE::left('Query Enrich', 5)->as('result')
        );

        $actual = $queryResult->result;
        $expected = 'Query';

        self::assertEquals($expected, $actual);
    }

    public function testLength()
    {
        $string = $this->faker->name;

        $queryResult = DB::selectOne(
            'select '.QE::length($string)->as('result')
        );

        $actual = $queryResult->result;
        $expected = strlen($string);

        self::assertEquals($expected, $actual);
    }

    public function testLower()
    {
        $string = 'Query Enrich';

        $queryResult = DB::selectOne(
            'select '.QE::lower($string)->as('result')
        );

        $actual = $queryResult->result;
        $expected = 'query enrich';

        self::assertEquals($expected, $actual);
    }

    public function testLtrim()
    {
        $string = '  Query Enrich  ';

        $queryResult = DB::selectOne(
            'select '.QE::ltrim($string)->as('result')
        );

        $actual = $queryResult->result;
        $expected = 'Query Enrich  ';

        self::assertEquals($expected, $actual);
    }

    public function testPadLeft()
    {
        $queryResult = DB::selectOne(
            'select '.QE::padLeft('James', 10, '-=')->as('result')
        );

        $actual = $queryResult->result;
        $expected = Str::padLeft('James', 10, '-=');

        self::assertEquals($expected, $actual);
    }

    public function testPadRight()
    {
        $queryResult = DB::selectOne(
            'select '.QE::padRight('James', 10, '-=')->as('result')
        );

        $actual = $queryResult->result;
        $expected = Str::padRight('James', 10, '-=');

        self::assertEquals($expected, $actual);
    }

    public function testPosition()
    {
        $queryResult = DB::selectOne(
            'select '.QE::position('World', 'Hello, World!')->as('result')
        );

        $actual = $queryResult->result;
        $expected = 8;

        self::assertEquals($expected, $actual);
    }

    public function testRepeat()
    {
        $string = $this->faker->name;
        $number = $this->faker->numberBetween(2, 10);

        $queryResult = DB::selectOne(
            'select '.QE::repeat($string, $number)->as('result')
        );

        $actual = $queryResult->result;
        $expected = Str::repeat($string, $number);

        self::assertEquals($expected, $actual);
    }

    public function testReplace()
    {
        $string = 'Hello, World!';
        $substring = 'World';
        $newString = 'Laravel';

        $queryResult = DB::selectOne(
            'select '.QE::replace($string, $substring, $newString)->as('result')
        );

        $actual = $queryResult->result;
        $expected = 'Hello, Laravel!';

        self::assertEquals($expected, $actual);
    }

    public function testReverse()
    {
        $string = 'Hello!';

        $queryResult = DB::selectOne(
            'select '.QE::reverse($string)->as('result')
        );

        $actual = $queryResult->result;
        $expected = '!olleH';

        self::assertEquals($expected, $actual);
    }

    public function testRight()
    {
        $author = DB::selectOne(
            'select '.QE::right('Query Enrich', 6)->as('result')
        );

        $actual = $author->result;
        $expected = 'Enrich';

        self::assertEquals($expected, $actual);
    }

    public function testRtrim()
    {
        $string = '  Query Enrich  ';

        $queryResult = DB::selectOne(
            'select '.QE::rtrim($string)->as('result')
        );

        $actual = $queryResult->result;
        $expected = 'Query Enrich  ';

        self::assertEquals($expected, $actual);
    }

    public function testSpace()
    {
        $queryResult = DB::selectOne(
            'select '.QE::space(8)->as('result')
        );

        $actual = $queryResult->result;
        $expected = '        ';

        self::assertEquals($expected, $actual);
    }

    public function testSubstr()
    {
        $string = 'The Laravel Framework';
        $start = 5;
        $length = 7;

        $queryResult = DB::selectOne(
            'select '.
            QE::substr($string, $start, $length)->as('result_1').','.
            QE::substr($string, $start)->as('result_2')
        );

        $actual_1 = $queryResult->result_1;
        $expected_1 = 'Laravel';

        self::assertEquals($expected_1, $actual_1);

        $actual_2 = $queryResult->result_2;
        $expected_2 = 'Laravel Framework';

        self::assertEquals($expected_2, $actual_2);
    }

    public function testTrim()
    {
        $string = '  Query Enrich  ';

        $queryResult = DB::selectOne(
            'select '.QE::trim($string)->as('result')
        );

        $actual = $queryResult->result;
        $expected = 'Query Enrich';

        self::assertEquals($expected, $actual);
    }

    public function testUpper()
    {
        $string = 'Query Enrich';

        $queryResult = DB::selectOne(
            'select '.QE::upper($string)->as('result')
        );

        $actual = $queryResult->result;
        $expected = 'QUERY ENRICH';

        self::assertEquals($expected, $actual);
    }

    public function testExists()
    {
        $author_1 = Author::create([
            'first_name' => $this->faker->firstName,
            'last_name'  => $this->faker->lastName,
            'email'      => $this->faker->email,
        ]);
        $author_2 = Author::create([
            'first_name' => $this->faker->firstName,
            'last_name'  => $this->faker->lastName,
            'email'      => $this->faker->email,
        ]);
        Book::insert(
            [
                'title'       => $this->faker->title,
                'description' => $this->faker->text,
                'author_id'   => $author_1->id,
                'price'       => 150,
                'year'        => $this->faker->year,
            ],
        );

        $queryResult = DB::table('authors')->select(
            'id',
            QE::exists(
                DB::table('books')->where('books.author_id', c('authors.id'))
            )->as('result')
        )->orderBy(
            'authors.id'
        )->get();

        $actual_1 = $queryResult[0]->result;
        $expected_1 = 1;

        self::assertEquals($expected_1, $actual_1);

        $actual_2 = $queryResult[1]->result;
        $expected_2 = 0;

        self::assertEquals($expected_2, $actual_2);
    }

    public function testAnd()
    {
        $true = QE::condition(1, 1);
        $false = QE::condition(1, 0);

        if ($this->getDatabaseEngine() === 'sqlsrv') {
            self::expectException(QueryException::class);
        }
        $queryResult = DB::selectOne(
            'select '.
            QE::and($false, $false)->as('result_1').','.
            QE::and($false, $true)->as('result_2').','.
            QE::and($true, $false)->as('result_3').','.
            QE::and($true, $true)->as('result_4')
        );
        if ($this->getDatabaseEngine() === 'sqlsrv') {
            return;
        }

        self::assertEquals(0, $queryResult->result_1);
        self::assertEquals(0, $queryResult->result_2);
        self::assertEquals(0, $queryResult->result_3);
        self::assertEquals(1, $queryResult->result_4);
    }

    public function testOr()
    {
        $true = QE::condition(1, 1);
        $false = QE::condition(1, 0);

        if ($this->getDatabaseEngine() === 'sqlsrv') {
            self::expectException(QueryException::class);
        }
        $queryResult = DB::selectOne('select '.
            QE::or($false, $false)->as('result_1').','.
            QE::or($false, $true)->as('result_2').','.
            QE::or($true, $false)->as('result_3').','.
            QE::or($true, $true)->as('result_4'));
        if ($this->getDatabaseEngine() === 'sqlsrv') {
            return;
        }

        self::assertEquals(0, $queryResult->result_1);
        self::assertEquals(1, $queryResult->result_2);
        self::assertEquals(1, $queryResult->result_3);
        self::assertEquals(1, $queryResult->result_4);
    }

    public function testNot()
    {
        $true = QE::condition(1, 1);
        $false = QE::condition(1, 0);

        if ($this->getDatabaseEngine() === 'sqlsrv') {
            self::expectException(QueryException::class);
        }
        $queryResult = DB::selectOne('select '.
            QE::not($false)->as('result_1').','.
            QE::not($true)->as('result_2'));
        if ($this->getDatabaseEngine() === 'sqlsrv') {
            return;
        }

        self::assertEquals(1, $queryResult->result_1);
        self::assertEquals(0, $queryResult->result_2);
    }

    public function testMd5()
    {
        $string = $this->faker->name;

        $queryResult = DB::selectOne('select '.QE::md5($string)->as('result'));

        $expected = md5($string);

        self::assertEquals($expected, $queryResult->result);
    }

    public function testStartsWith()
    {
        $string = 'Laravel Query Enrich';

        $queryResult = DB::selectOne(
            'select '.
            QE::startsWith($string, 'Laravel')->as('result_1').','.
            QE::startsWith($string, 'Laravel Query')->as('result_2').','.
            QE::startsWith($string, 'Query')->as('result_3')
        );

        self::assertEquals(1, $queryResult->result_1);
        self::assertEquals(1, $queryResult->result_2);
        self::assertEquals(0, $queryResult->result_3);
    }

    public function testEndsWith()
    {
        $string = 'Laravel Query Enrich';

        $queryResult = DB::selectOne(
            'select '.
            QE::endsWith($string, 'Enrich')->as('result_1').','.
            QE::endsWith($string, 'Query Enrich')->as('result_2').','.
            QE::endsWith($string, 'Query')->as('result_3')
        );

        self::assertEquals(1, $queryResult->result_1);
        self::assertEquals(1, $queryResult->result_2);
        self::assertEquals(0, $queryResult->result_3);
    }

    public function testContains()
    {
        $string = 'Laravel Query Enrich';

        $queryResult = DB::selectOne(
            'select '.
            QE::contains($string, 'Laravel Query Enrich')->as('result_1').','.
            QE::contains($string, 'Query Enrich')->as('result_2').','.
            QE::contains($string, 'Laravel Query')->as('result_3').','.
            QE::contains($string, 'Query')->as('result_4').','.
            QE::contains($string, 'Lumen')->as('result_5')
        );

        self::assertEquals(1, $queryResult->result_1);
        self::assertEquals(1, $queryResult->result_2);
        self::assertEquals(1, $queryResult->result_3);
        self::assertEquals(1, $queryResult->result_4);
        self::assertEquals(0, $queryResult->result_5);
    }
}
