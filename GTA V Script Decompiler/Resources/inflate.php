<?php
file_put_contents("native_translation.txt", gzinflate(file_get_contents("native_translation.dat")));
