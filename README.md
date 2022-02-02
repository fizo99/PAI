# Taxamo - Simple invoices creator

# Views
- /login - login page
- /register - register page
- /create - invoice creator
- /list - invoice list

# Run
1. Fill database credentials in config.php
2. Run `composer install`
3. Make sure your docker deamon is running  
Then run `docker compose up --build`
4. Go to <http://localhost:8080/>

# TODO

- [x] Handle payment type when generating DOC
- [ ] Refactor Code
- [x] New CSS for whole project
- [x] New CSS for whole project - mobile version
- [x] Rewrite Router to handle HTTP methods
- [x] Translate to PL
- [x] Recalculate on quantity change
