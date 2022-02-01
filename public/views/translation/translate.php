<?php

function translateStatePL($state)
{
    if ($state == "UNPAID") $plState = "Niezapłacona";
    else if ($state == "PAID") $plState = "Zapłacona";
    else if ($state == "CANCELLED") $plState = "Anulowana";
    else return $state;

    return $plState;
}
