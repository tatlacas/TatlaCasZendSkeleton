rm -R ./module/Shiri/src/Shiri/Model/Entity
mkdir ./module/Shiri/src/Shiri/Model/Entity
mkdir ./module/Application/src/Application/Temp
mv ./module/Application/src/Application/Entity/ ./module/Application/src/Application/Temp/
chmod +x ./vendor/doctrine/doctrine-module/bin/doctrine-module
chmod +x ./vendor/bin/doctrine-module
./vendor/bin/doctrine-module orm:convert-mapping --namespace="Application\Entity\\" --force  --from-database annotation ./module/Application/src/
./vendor/bin/doctrine-module orm:generate-entities ./module/Application/src/ --generate-annotations=true
mv ./module/Application/src/Application/Entity/ ./module/Shiri/src/Shiri/Model/
mv ./module/Application/src/Application/Temp/Entity/ ./module/Application/src/Application/ 
rm -R ./module/Application/src/Application/Temp/