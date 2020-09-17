<?php
file_put_contents("native_translation.dat", gzdeflate(file_get_contents("native_translation.txt")));
