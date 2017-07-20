<?php

$IPADDRESS = '192.168.1.70';

function AddrLink($path) {
    global $IPADDRESS;

    return "http://$IPADDRESS/$path";
}

?>