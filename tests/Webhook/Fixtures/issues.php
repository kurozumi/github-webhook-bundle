<?php

return new Symfony\Component\RemoteEvent\RemoteEvent(
    name: 'issues',
    id: '72d3162e-cc78-11e3-81ab-4c9367dc0958',
    payload: json_decode(file_get_contents(str_replace('.php', '.json', __FILE__)), true)
);
