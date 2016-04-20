<?php

if(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    echo "a";
} else {
    echo "b";
}