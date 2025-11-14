Dragonstone - Finalized notes

Changes made (non-destructive, applied to a copy):
- Added config/config.php (BASE_URL and DB credentials placeholders + asset() helper)
- Header updated to use the asset() helper for CSS/JS/images
- Admin login (admin/index.php) updated to use DB-based authentication when possible. If DB is not available, a development fallback admin/admin123 remains (please change immediately).
- Added README_FINAL.txt with minimal deployment instructions.

Deployment steps:
1) Place the project in your web server root.
2) Edit config/config.php: set BASE_URL, DB_HOST, DB_NAME, DB_USER, DB_PASS
3) Import database/create_tables.sql into your MySQL server (or run the setup using the admin page which will create an admin if none exists).
4) Secure the admin account: login and change the password in the admins table.

Notes:
- This is a light, safety-focused finalization. If you want visual redesign (Tailwind/Bootstrap), checkout changes, or deeper security hardening (CSRF tokens, prepared statements everywhere, XSS sanitization), tell me and I will apply them next.
