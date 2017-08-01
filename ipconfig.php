<?php

$IPADDRESS = 'localhost/a/';

function AddrLink($path) {
    global $IPADDRESS;

    return "http://$IPADDRESS/$path";
}

?>