# Introducción #

Instructivo para resetear el password de root de MYSQL


# Detalles #

To reset a root password that you forgot (using paths on our system):

killall mysqld

/usr/libexec/mysqld -Sg --user=root &

You may have better luck with:

mysqld --skip-grant-tables --user=root

Go back into MySQL with the client:

mysql

Welcome to the MySQL monitor.  Commands end with ; or g.
Your MySQL connection id is 1 to server version: 3.23.41
Type 'help;' or 'h' for help. Type 'c' to clear the buffer.

mysql> USE mysql

Reading table information for completion of table and column names
You can turn off this feature to get a quicker startup with -A
Database changed

mysql> UPDATE user

-> SET password=password("newpassword")

-> WHERE user="root";

Query OK, 2 rows affected (0.04 sec)
Rows matched: 2  Changed: 2  Warnings: 0

mysql> flush privileges;

Query OK, 0 rows affected (0.01 sec)

mysql> exit;

killall mysqld

service mysqld start