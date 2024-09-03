# TOKO-BLOG

## Implementation

After cloning this soft, please create .env file in `./src/application/` and set up the environment based on `./src/application/.env.example` seeing `dokcer-compose.yml`.

Additionally, please execute the command below to generate app_key

```
  $ make dev
  $ docker compose exec php bash
  $ cd application
  $ php artisan key:generate
```

this soft is started by the follwing command
```
  $ make dev 
```

this soft is finished by the following command
```
  $ make down 
```

## Demo
The video is below(https://drive.google.com/file/d/1d46R03iGNbYu6IdZkWG9VB2WDFszoIVo/view?usp=drive_link)

## Furture Work
- Direct Message with other user 
- Comment function for the blog 
- User follow/following function