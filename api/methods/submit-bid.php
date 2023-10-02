<?php
function submitBid($db, $request, $util)
{
  try {
    $db->query("INSERT INTO online_bids %v", (array) $request->body);
    $util->success($request->body);
  } catch (Exception $e) {
    $util->fail($e->getMessage());
  }
}
