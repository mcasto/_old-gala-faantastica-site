<?php
function getAdminData($db, $request, $util)
{
  $util->success(
    [
      'donations' => $db->fetchAll("SELECT * FROM donations ORDER BY date DESC"),
      'contacts' => $db->fetchAll("SELECT * FROM contacts ORDER BY date DESC")
    ],
    true
  );
}
