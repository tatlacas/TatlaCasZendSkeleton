@ECHO OFF

call ./vendor/bin/doctrine-module orm:convert-mapping --namespace="Application\Entity\\" --force  --from-database annotation ./module/Application/src/
call ./vendor/bin/doctrine-module orm:generate-entities ./module/Application/src/ --generate-annotations=true

pause 