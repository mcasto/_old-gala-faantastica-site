<?php
function submitDonation($db, $request, $util)
{
    $request->body->submitted_date = date("Y-m-d");
    $db->query("INSERT INTO donations %v", (array) $request->body);
    $util->success($request->body);
}
