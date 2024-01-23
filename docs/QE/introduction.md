# QE

QE is a helper class that makes writing complex SQL queries in Laravel simpler. It gives you a bunch of functions for
things you often need in databases. Here are some of QE's functions:

## 1. Basic Functions

- **[`c(string $name)`](Basic/column.md):** Gets a reference to a specific database column.
- **[`raw(string $sql, array $bindings = [])`](Basic/raw.md):** Represents a raw SQL expression. Good for when you need
  something special.

## 2. Advanced Expressions

- **[`case()`](Advanced/case.md):** Handles conditional logic.
- **[`coalesce(...$parameters)`](Advanced/coalesce.md):** Picks the first non-null value from a list.
- **[`exists(QueryBuilder|EloquentBuilder $query)`](Advanced/exists.md):** Checks if a subquery has any results.
- **[`if(DBFunction $condition, $valueIfTrue, $valueIfFalse)`](Advanced/if.md):** Does something if a condition is true,
  and something else if false.
- **[`isNull($parameter)`](Advanced/isNull.md):** Checks if a value is empty.

## 3. Conditional Operators

- **[`condition(mixed $parameter1, string $operator = null, mixed $parameter2 = null)`](Conditional/condition.md):**
  Creates a condition.
- **[`and(...$parameters)`]:** Combi(Conditional/and.md)nes conditions with AND.
- **[`or(...$parameters)`]:** Combi(Conditional/or.md)nes conditions with OR.
- **[`not($parameter)`](Conditional/not.md):** Negates a condition.

## 4. Date Functions

Working with dates made easy.

- **[`addDate($subject, $value, Date\Unit $interval = Date\Unit::DAY)`](Date/addDate.md):** Adds a specific value to a
  date/datetime.
- **[`currentDate()`](Date/currentDate.md):** Retrieves the current date.
- **[`currentTime()`](Date/currentTime.md):** Retrieves the current time.
- **[`date($parameter)`](Date/date.md):** Extracts the date part from a datetime expression.
- **[`dateDiff($date1, $date2)`](Date/dateDiff.md):** Returns the number of days between two date values.
- **[`dayOfWeek($parameter)`](Date/dayOfWeek.md):** Returns the weekday index for a given date/datetime.
- **[`hour($parameter)`](Date/hour.md):** Returns the hour part for a given time/datetime.
- **[`minute($parameter)`](Date/minute.md):** Returns the minute part for a given time/datetime.
- **[`month($parameter)`](Date/month.md):** Returns the month part for a given date/datetime.
- **[`monthName($parameter)`](Date/monthName.md):** Returns the name of the month for a given date/datetime.
- **[`now()`](Date/now.md):** Returns the current date and time.
- **[`second($parameter)`](Date/second.md):** Returns the seconds part of a time/datetime.
- **[`subDate($subject, $value, Date\Unit $interval = Date\Unit::DAY)`](Date/subDate.md):** Subtracts a time/date
  interval from a date and then returns the date.
- **[`time($parameter)`](Date/time.md):** Extracts the time part from a given time/datetime.
- **[`year($parameter)`](Date/year.md):** Returns the week number for a given date/datetime.

## 5. Numeric Functions

Performing math operations and dealing with numbers.

- **[`abs($parameter)`](Numeric/abs.md):** Returns the absolute value of a number.
- **[`acos($parameter)`](Numeric/acos.md):** Returns the arc cosine of a number.
- **[`add(...$parameters)`](Numeric/add.md):** Adds multiple numeric parameters.
- **[`asin($parameter)`](Numeric/asin.md):** Returns the arc sine of a number.
- **[`atan($parameter)`](Numeric/atan.md):** Returns the arc tangent of a number.
- **[`atan2($y, $x)`]:(Numeric/atan2.md)** Returns the arc tangent of two numbers.
- **[`avg($parameter)`](Numeric/avg.md):** Returns the average value of an expression.
- **[`ceil($parameter)`](Numeric/ceil.md):** Returns the smallest integer value that is >= to a number.
- **[`cos($parameter)`](Numeric/cos.md):** Returns the cosine of a number.
- **[`cot($parameter)`](Numeric/cot.md):** Returns the cotangent of a number.
- **[`count($parameter)`](Numeric/count.md):** Returns the number of records returned by a select query.
- **[`degreesToRadian($parameter)`](Numeric/degreesToRadian.md):** Converts a degree value into radians.
- **[`divide(...$parameters)`](Numeric/divide.md):** Divide the first numeric parameter by subsequent numeric
  parameters.
- **[`exp($parameter)`](Numeric/exp.md):** Returns e raised to the power of a specified number.
- **[`floor($parameter)`](Numeric/floor.md):** Returns the largest integer value that is <= to a number.
- **[`greatest(...$parameters)`](Numeric/greatest.md):** Returns the greatest value of the list of arguments.
- **[`least(...$parameters)`](Numeric/least.md):** Returns the smallest value of the list of arguments.
- **[`ln($parameter)`](Numeric/ln.md):** Returns the natural logarithm of a number.
- **[`log($number, $base = 2)`](Numeric/log.md):** Returns the natural logarithm of a number, or the logarithm of a
  number to a specified
  base.
- **[`max($parameter)`](Numeric/log.md):** Returns the maximum value in a set of values.
- **[`min($parameter)`](Numeric/max.md):** Returns the minimum value in a set of values.
- **[`mod($x, $y)`](Numeric/min.md):** Returns the remainder of a number divided by another number.
- **[`multiply(...$parameters)`](Numeric/mod.md):** Multiply multiple numeric parameters.
- **[`pi()`](Numeric/multiply.md):** Returns the value of PI.
- **[`pow($x, $y)`:](Numeric/pi.md)** Returns the value of a number raised to the power of another number.
- **[`radianToDegrees($parameter)`](Numeric/pow.md):** Converts a value in radians to degrees.
- **[`rand($seed = null)`](Numeric/radianToDegrees.md):** Returns a random number.
- **[`round($parameter, $decimals = 0)`](Numeric/rand.md):** Rounds a number to a specified number of decimal places.
- **[`sign($parameter)`](Numeric/round.md):** Returns the sign of a number.
- **[`sin($parameter)`](Numeric/sign.md):** Returns the sine of a number.
- **[`sqrt($parameter)`](Numeric/sin.md):** Returns the square root of a number.
- **[`subtract(...$parameters`](Numeric/sqrt.md):** Subtracts multiple numeric parameters.
- **[`sum($parameter)`](Numeric/subtract.md):** Calculates the sum of a set of values.
- **[`tan($parameter)`](Numeric/sum.md):** Returns the tangent of a number.

## 6. String Functions

Manipulating text has never been simpler.

- **[`concat(...$parameters)`](String/concat.md):** Adds two or more expressions together.
- **[`concatWS($separator, ...$parameters)`](String/concatWS.md):** Adds two or more expressions together with a
  separator.
- **[`left($string, $numberOfChars)`](String/left.md):** Extracts a number of characters from a string (starting from
  left).
- **[`length($parameter)`](String/length.md):** Returns the length of a string.
- **[`lower($parameter)`](String/lower.md):** Converts a string to lower-case.
- **[`padLeft($string, $length, $pad = ' ')`](String/padLeft.md):** Left-pads a string with another string, to a certain
  length.
- **[`ltrim($parameter)`](String/ltrim.md):** Removes leading spaces from a string.
- **[`padRight($string, $length, $pad = ' ')`](String/padRight.md):** Right-pads a string with another string, to a
  certain length.
- **[`position($subString, $string)`](String/position.md):** Finds the position of a substring within a string.
- **[`repeat($parameter, $number)`](String/repeat.md):** Repeats a string a specified number of times.
- **[`replace($string, $substring, $newString)`](String/replace.md):** Replaces occurrences of a substring with a new
  string.
- **[`reverse($parameter)`](String/reverse.md):** Reverses the characters of a string.
- **[`right($string, $numberOfChars)`](String/right.md):** Extracts a number of characters from a string (starting from
  right).
- **[`rtrim($parameter)`](String/rtrim.md):** Removes trailing spaces from a string.
- **[`space($parameter)`](String/space.md):** Returns a string consisting of a specified number of spaces.
- **[`substr($string, $start, $length = null)`](String/substr.md):** Extracts a substring from a string starting at a
  specified position with optional length.
- **[`trim($parameter)`](String/trim.md):** Removes leading and trailing spaces from a string.
- **[`upper($parameter)`](String/upper.md):** Converts a string to upper-case.
