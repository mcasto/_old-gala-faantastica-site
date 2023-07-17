<?php
function getAdminData($db, $request, $util)
{
    $util->success(
        [
            'registrations' => $db->fetchAll("SELECT * FROM registrations ORDER BY `date`"),
            'donations' => $db->fetchAll("SELECT * FROM donations ORDER BY `date`")
        ],
        true
    );
}
