<?php
function submitRec($db, $request, $util)
{

    $request->body->rec->submitted_date = date("Y-m-d");
    $db->query("INSERT INTO %n %v", $request->body->table, (array) $request->body->rec);
    $util->success($request->body);
}
