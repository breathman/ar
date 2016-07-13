@echo off

echo This will remove database and creates new with fixtures
set /p answer=Do you want to renew now (Y/N)?

if /i "%answer:~,1%" EQU "Y" goto execute
exit /b

:execute
php bin/console cache:clear
php bin/console doctrine:database:drop --force
php bin/console doctrine:database:create
php bin/console doctrine:schema:create
php bin/console doctrine:migrations:migrate -n
php bin/console doctrine:fixtures:load -n
