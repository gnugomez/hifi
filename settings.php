<?php
define("MYSQL_URL", parse_url(getenv("CLEARDB_DATABASE_URL")));
define("MYSQL_SERVER", MYSQL_URL["host"]);
define("MYSQL_USER", MYSQL_URL["user"]);
define("MYSQL_PASSWORD", MYSQL_URL["pass"]);
define("MYSQL_DATABASE_NAME", substr(MYSQL_URL["path"], 1));
define("IS_DEBUG", true);
