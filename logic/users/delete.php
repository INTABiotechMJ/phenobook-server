<?php
$admin = true;
require "../../files/php/config/require.php";
$item = Entity::load("User", _request("id"));
$item->active = 0;
Entity::update($item);
redirect("index.php?m=User deleted");
