<?php

/**
*   This file contains application constants
*/

// Directory separator
if(preg_match("/\//", __DIR__))
{ 
    // unix environment
    defined("DS") or define("DS", "/");
}
else
{
    // windows environment
    defined("DS") or define("DS", "\\");
}
