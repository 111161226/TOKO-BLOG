dev:
	docker-compose up --build -d

mig:
	docker exec php-apache php application/artisan migrate:refresh --seed

in:
	docker compose exec php bash

tdb:
	docker exec php-apache php application/artisan migrate:refresh --seed --env=testing

clear:
	docker exec php-apache php artisan config:clear

clean:
	docker exec php-apache php application/artisan route:clear

down:
	docker-compose down