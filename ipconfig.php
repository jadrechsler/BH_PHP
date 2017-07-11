<?php

$IPADDRESS = 'localhost';

function AddrLink($path) {
    global $IPADDRESS;

    return "http://$IPADDRESS/$path";
}

?>