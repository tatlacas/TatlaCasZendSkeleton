rm -R ./module/Application/src/Application/Entity
mkdir ./module/Application/src/Application/Entity
chmod +x ./vendor/doctrine/doctrine-module/bin/doctrine-module
chmod +x ./vendor/bin/doctrine-module
./vendor/bin/doctrine-module orm:convert-mapping --namespace="Application\Entity\\" --force  --from-database annotation ./module/Application/src/
./vendor/bin/doctrine-module orm:generate-entities ./module/Application/src/ --generate-annotations=true