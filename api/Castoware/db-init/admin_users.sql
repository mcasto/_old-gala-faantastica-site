CREATE TABLE "admin_users" (
	`id` INTEGER PRIMARY KEY AUTOINCREMENT,
	`email` text,
	`pass_hash` text,
	`token` text
);