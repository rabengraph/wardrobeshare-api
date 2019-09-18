## Create Fixtures

    bin/console doctrine:fixtures:load -n

    docker-compose exec php bin/console doctrine:schema:update --force
    docker-compose exec php bin/console doctrine:fixtures:load -n
