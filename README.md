# Symfony-Websockets-Game-Experiment

Experiment with PHP 8.2, Symfony 6.3, Websocket about building a realtime game template.


## Run the application

- `docker compose up`
- `symfony server:start`
- `php bin/console GameServerCommand`


## Preview

https://github.com/valx76/Symfony-Websockets-Game-Experiment/assets/1681898/a10c28a0-3b78-43e3-8ce5-0afff0ffb817


## Additional information

This project is just a POC - It shouldn't be used as-is since the server sends the whole world data on each message, which is heavy on the server.
<br/>
Instead, it would be better to send the data at a regular interval and only send what has been modified (as well as adding client side prediction).
