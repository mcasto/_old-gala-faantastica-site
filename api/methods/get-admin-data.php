<?php
function getAdminData($db, $request, $util)
{
    $util->success(
        [
            'donations' => $db->fetchAll("SELECT * FROM donations")
        ],
        true
    );
}
