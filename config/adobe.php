<?php

return [
    "org_id" => env("ADOBE_ORGID"),
    "tech_acc_id" => env("ADOBE_TECHACCID"),
    "client_id" => env("ADOBE_CLIENTID"),
    "private_key" => env("ADOBE_PRIVKEY"),
    "client_secret" => env("ADOBE_CLIENTSECRET"),
    "days" => env("ADOBE_DAYS", '365 days'),
    "group_id" => env("ADOBE_GROUPID")
];
