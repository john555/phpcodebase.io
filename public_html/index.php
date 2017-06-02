<?php

$app = include "../System/Bootstrap.php";

$router = $app->getRouter();

include "../Routes/Tests.php";

$app->start();