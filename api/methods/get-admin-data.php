<?php
function getAdminData($db, $request, $util)
{
    $util->success(
        [
            'donations' => $db->fetchAll("SELECT * FROM donations ORDER BY submitted_date DESC"),
            'contacts' => $db->fetchAll("SELECT * FROM contacts ORDER BY submitted_date DESC")
        ],
        true
    );
}
