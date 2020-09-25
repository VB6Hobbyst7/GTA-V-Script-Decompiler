<?php
ini_set("memory_limit", "256M");
file_put_contents("Entities.dat", gzdeflate(file_get_contents("Entities.txt")));
