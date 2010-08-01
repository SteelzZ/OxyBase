<?php
/**
 * @author martynas
 * @copyright msc
 * @package msc build tools
 * @todo migrate to liquibase maybe sometime?
 */

$_dbCreateSqlTemplate = 'CREATE DATABASE <dbname> DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci';
switch ($argc) {
	case 3:
		$_conn = mysql_connect($argv[1]) or die(' connecting to database failed with arguments: ' . print_r($argv, true));
		break;
	case 4:
		$_conn = mysql_connect($argv[1], $argv[2]) or die(' connecting to database failed with arguments: ' . print_r($argv, true));
		break;
	case 5:
		$_conn = mysql_connect($argv[1], $argv[2], $argv[3]) or die(' connecting to database failed with arguments: ' . print_r($argv, true));
		break;
	default:
		die("\nUsage should be: <script_name> host user password <databasetoCREATE>\n");
		break;
}
mysql_set_charset('utf8', $_conn) or die(' failed setting utf8 charset, we like utf8 so should you!');
mysql_query('DROP DATABASE ' . $argv[4]);
mysql_query(str_replace('<dbname>', $argv[4], $_dbCreateSqlTemplate), $_conn) or die(' query failed: ' . str_replace('<dbname>', $argv[4], $_dbCreateSqlTemplate) . ' ' . mysql_error($_conn));
echo "\n Done creating database " . $argv[4] . ". time: " . date('Y-m-d H:i:s') . "\n";