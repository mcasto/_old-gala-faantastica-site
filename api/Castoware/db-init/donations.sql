CREATE TABLE donations (
    [id] INTEGER PRIMARY KEY AUTOINCREMENT,
    [name] text,
    [business] text, 
    [description] text,
    [email] text,
    [phone] text,
    [whatsapp] integer
    [item_value] real,
    [restrictions] text, 
    [pickup] text, 
    [create_certificate] INTEGER,
    [submitted_date] text
);