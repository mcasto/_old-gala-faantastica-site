<?php
function getAdminData($db, $request, $util)
{
  $util->success(
    [
      'donations' => $db->fetchAll("SELECT * FROM donations ORDER BY date DESC"),
      'contacts' => $db->fetchAll("SELECT * FROM contacts ORDER BY date DESC"),
      'online_bids' => $db->fetchAll("SELECT b.id, i.item_name_en AS item_name, b.highest_bid, b.name, b.email, b.phone FROM online_bids b, biddable_items i WHERE i.item_number=b.item_number ORDER BY i.item_name_en, b.highest_bid DESC")
    ],
    true
  );
}
