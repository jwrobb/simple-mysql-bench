simple-mysql-bench
==================

A PHP script to perform simple benchmarking against a MySQL database

Notice I said simple. This script takes a Drupal settings.php file and connects to a database, creates a table then inserts some junk data, and finally cleans up after itself. It outputs timing after each operation to give you an idea how how quickly it is executing.

This can easily be modified to use any database conneciton, just hardcode the connection information. More to come, but for now, keep it simple stupid.

Copy all the files to the same directory, then run 'php mysql_bench.php' to run the benchmark.
