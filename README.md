ITE 314 Performance Task
Deadline: Sept 12 2025

- Main page - Dashboard
- Customer Page
- Items Page
- Transaction


Apache needs to be configured to recognize index.php as an index file.
https://stackoverflow.com/questions/2384423/index-php-not-loading-by-default
Just append 'index.php' (beside index.html) to DirectoryIndex in /etc/httpd/conf/httpd.conf

website: http://127.0.0.1:8080
adminer: http://127.0.0.1:8081