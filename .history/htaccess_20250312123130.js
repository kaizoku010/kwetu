Options +FollowSymLinks
RewriteEngine On

# Allow direct access to assets directory
RewriteRule ^assets/ - [L]

# PHP Error Reporting for troubleshooting
php_flag display_errors on
php_value error_reporting E_ALL

# Handle direct access to user_auth directory
RewriteRule ^user_auth/?$ /user_auth/user_login.php [L]

# Protect against directory listing
Options -Indexes

# Set default character set
AddDefaultCharset UTF-8

# Handle PHP sessions
php_value session.cookie_httponly 0
php_value session.use_only_cookies 0
