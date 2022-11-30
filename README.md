## general

Use this application to easily run create new entries or run scripts for Advent Of Code assignments.
Uses the following folder structure:

    |- events
    |  - [year]
    |    - [day]
    |- util

### create 

Create a new set of files needed to complete an assignment in the appropriate folder structure for the given year and day. Generates:
- `part-a.php`: script to solve the first parts of the assignment. Filled with content from ./util/defaultCode.php
- `part-b.php`: script to solve the first parts of the assignment. Filled with content from ./util/defaultCode.php
- `input.txt`: input data to be used in both parts. Automatically downloaded from adventofcode.com is config value is set.
- `tests.php`: A place where you can place your test inputs and expected answers

Takes two optional arguments to indicate for what year and day files need to created.

Ex: `php app.php create 2022 12`

Will create a set of files for the twelfth assignment of the 2022 event, stored in events/2022/12/.
If year is omitted, with autofill with the current year if the current date is in december, otherwise uses the previous year.
If day is omitted, uses the daynumber of the current date, regardless of it being a daynumber lower than 25.

It is possible to overwrite existing files by adding 'overwrite' as a final argument. This will overwrite all of the three mentioned files with new content. Will download the input data again from adventofcode.com.

Ex: `php app.php create 2022 12 overwrite`.


### run

Run a script for the given year, day and part indicator. First and second parts of an assignment are indicated with a and b respectively.
Can accept al values as one string, or separated as individual arguments in order.
All examples below will attempt to run the second part of the first day assignment of the 2022 event.

`php app.php run 20221b`

`php app.php run 202201b`

Running tests is as easy as adding a 'test' parameter at the end of your run command:
php app.php run 20221b test


### help
`php app.php help`

Show this help output
