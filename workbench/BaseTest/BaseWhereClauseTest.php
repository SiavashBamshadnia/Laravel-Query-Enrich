<?php

namespace Workbench\BaseTest;

use Illuminate\Support\Facades\DB;
use sbamtr\LaravelQueryEnrich\Exception\InvalidArgumentException;
use sbamtr\LaravelQueryEnrich\QE;
use Workbench\App\Models\Author;
use Workbench\App\Models\Book;
use function sbamtr\LaravelQueryEnrich\c;

abstract class BaseWhereClauseTest extends BaseTest
{
    public function testCondition()
    {
        $expected = Book::where('year', '>', 2000)->toRawSql();
        $actual = Book::whereRaw(
            QE::condition(c('year'), '>', 2000)
        )->toRawSql();

        self::assertEquals($expected, $actual);

        self::expectException(InvalidArgumentException::class);
        QE::condition(1, 'invalid operator', 2);
    }

    public function testConditionInvalidArgumentException()
    {
        self::expectException(InvalidArgumentException::class);
        QE::condition(1, 'invalid operator', 2);
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
                'author_id'   => $author_2->id,
                'price'       => 150,
                'year'        => $this->faker->year,
            ],
        );

        $queryResult = DB::table('books')->select(
            'author_id',
        )->whereRaw(
            QE::exists(
                Db::table('authors')->where('authors.id', c('books.author_id'))
            )
        )->get();

        self::assertEquals(1, count($queryResult));

        $actual_1 = $queryResult[0]->author_id;
        $expected_1 = $author_2->id;

        self::assertEquals($expected_1, $actual_1);
    }

    public function testIsNull()
    {
        $author_1 = Author::create([
            'first_name' => $this->faker->firstName,
            'last_name'  => $this->faker->lastName,
            'email'      => $this->faker->email,
        ]);
        Book::insert([
            [
                'title'       => $this->faker->title,
                'description' => $this->faker->text,
                'author_id'   => $author_1->id,
                'price'       => 150,
                'year'        => $this->faker->year,
            ],
            [
                'title'       => $this->faker->title,
                'description' => null,
                'author_id'   => $author_1->id,
                'price'       => 150,
                'year'        => $this->faker->year,
            ],
        ]);

        $queryResult = DB::table('books')->whereRaw(
            QE::isNull(c('description'))
        )->count();

        $actual_1 = $queryResult;
        $expected_1 = 1;

        self::assertEquals($expected_1, $actual_1);
    }

    public function testAnd()
    {
        $author_1 = Author::create([
            'first_name' => $this->faker->firstName,
            'last_name'  => $this->faker->lastName,
            'email'      => $this->faker->email,
        ]);
        Book::insert([
            [
                'title'       => $this->faker->title,
                'description' => $this->faker->text,
                'author_id'   => $author_1->id,
                'price'       => 50,
                'year'        => $this->faker->year,
            ],
            [
                'title'       => $this->faker->title,
                'description' => null,
                'author_id'   => $author_1->id,
                'price'       => 150,
                'year'        => $this->faker->year,
            ],
            [
                'title'       => $this->faker->title,
                'description' => null,
                'author_id'   => $author_1->id,
                'price'       => 250,
                'year'        => $this->faker->year,
            ],
        ]);

        $queryResult = DB::table('books')->whereRaw(
            QE::and(
                QE::condition(c('price'), '>', 100),
                QE::condition(c('price'), '<', 200),
            )
        )->get();

        $actual_1 = count($queryResult);
        $expected_1 = 1;

        self::assertEquals($expected_1, $actual_1);

        $actual_2 = $queryResult[0]->price;
        $expected_2 = 150;

        self::assertEquals($expected_2, $actual_2);
    }

    public function testOr()
    {
        $author_1 = Author::create([
            'first_name' => $this->faker->firstName,
            'last_name'  => $this->faker->lastName,
            'email'      => $this->faker->email,
        ]);
        Book::insert([
            [
                'title'       => $this->faker->title,
                'description' => $this->faker->text,
                'author_id'   => $author_1->id,
                'price'       => 50,
                'year'        => $this->faker->year,
            ],
            [
                'title'       => $this->faker->title,
                'description' => null,
                'author_id'   => $author_1->id,
                'price'       => 150,
                'year'        => $this->faker->year,
            ],
            [
                'title'       => $this->faker->title,
                'description' => null,
                'author_id'   => $author_1->id,
                'price'       => 250,
                'year'        => $this->faker->year,
            ],
        ]);

        $queryResult = DB::table('books')->whereRaw(
            QE::or(
                QE::condition(c('price'), 150),
                QE::condition(c('price'), 250),
            )
        )->get();

        $actual_1 = count($queryResult);
        $expected_1 = 2;

        self::assertEquals($expected_1, $actual_1);

        $actual_2 = $queryResult[0]->price;
        $expected_2 = 150;

        $actual_3 = $queryResult[1]->price;
        $expected_3 = 250;

        self::assertEquals($expected_2, $actual_2);
        self::assertEquals($expected_3, $actual_3);
    }

    public function testNot()
    {
        $author_1 = Author::create([
            'first_name' => $this->faker->firstName,
            'last_name'  => $this->faker->lastName,
            'email'      => $this->faker->email,
        ]);
        Book::insert([
            [
                'title'       => $this->faker->title,
                'description' => $this->faker->text,
                'author_id'   => $author_1->id,
                'price'       => 50,
                'year'        => $this->faker->year,
            ],
            [
                'title'       => $this->faker->title,
                'description' => null,
                'author_id'   => $author_1->id,
                'price'       => 150,
                'year'        => $this->faker->year,
            ],
            [
                'title'       => $this->faker->title,
                'description' => null,
                'author_id'   => $author_1->id,
                'price'       => 250,
                'year'        => $this->faker->year,
            ],
        ]);

        $queryResult = DB::table('books')->whereRaw(
            QE::not(
                QE::condition(c('price'), 150),
            )
        )->get();

        $actual_1 = count($queryResult);
        $expected_1 = 2;

        self::assertEquals($expected_1, $actual_1);

        $actual_2 = $queryResult[0]->price;
        $expected_2 = 50;

        $actual_3 = $queryResult[1]->price;
        $expected_3 = 250;

        self::assertEquals($expected_2, $actual_2);
        self::assertEquals($expected_3, $actual_3);
    }

    public function testStartsWith()
    {
        Author::insert([
            [
                'first_name' => 'Walter',
                'last_name' => 'White',
                'email' => 'info@example.com',
            ],
            [
                'first_name' => 'Walt',
                'last_name' => 'White',
                'email' => 'info2@example.com',
            ],
            [
                'first_name' => 'Skyler',
                'last_name' => 'White',
                'email' => 'info3@example.com',
            ],
        ]);

        $queryResult = DB::table('authors')->whereRaw(
            QE::startsWith(c('first_name'), 'Walt')
        )->get();

        self::assertEquals(2, count($queryResult));
        self::assertEquals('Walter', $queryResult[0]->first_name);
        self::assertEquals('Walt', $queryResult[1]->first_name);
    }


    public function testEndsWith()
    {
        Author::insert([
            [
                'first_name' => 'Sandor',
                'last_name' => 'Clegane',
                'email' => 'info@example.com',
            ],
            [
                'first_name' => 'Gregor',
                'last_name' => 'Clegane',
                'email' => 'info2@example.com',
            ],
            [
                'first_name' => 'Gared',
                'last_name' => 'Clegane',
                'email' => 'info3@example.com',
            ],
        ]);

        $queryResult = DB::table('authors')->whereRaw(
            QE::endsWith(c('first_name'), 'or')
        )->get();

        self::assertEquals(2, count($queryResult));
        self::assertEquals('Sandor', $queryResult[0]->first_name);
        self::assertEquals('Gregor', $queryResult[1]->first_name);
    }

    public function testContains()
    {
        Author::insert([
            [
                'first_name' => 'Sandor',
                'last_name' => 'Clegane',
                'email' => 'info@example.com',
            ],
            [
                'first_name' => 'Gregor',
                'last_name' => 'Clegane',
                'email' => 'info2@example.com',
            ],
            [
                'first_name' => 'Gared',
                'last_name' => 'Clegane',
                'email' => 'info3@example.com',
            ],
        ]);

        $queryResult = DB::table('authors')->whereRaw(
            QE::contains(c('first_name'), 'o')
        )->get();

        self::assertEquals(2, count($queryResult));
        self::assertEquals('Sandor', $queryResult[0]->first_name);
        self::assertEquals('Gregor', $queryResult[1]->first_name);
    }
}
