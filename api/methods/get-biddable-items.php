<?php
function getBiddableItems($db, $request, $util)
{
  $util->success($db->fetchAll("SELECT * FROM biddable_items"), true);
}
