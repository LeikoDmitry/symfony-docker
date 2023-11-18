# Symfony Docker

[![codecov](https://codecov.io/github/LeikoDmitry/symfony-docker/graph/badge.svg?token=PCBJCLRCD0)](https://codecov.io/github/LeikoDmitry/symfony-docker)

A [Docker](https://www.docker.com/)-based installer and runtime for the [Symfony](https://symfony.com) web framework

## Getting Started

1. If not already done, [install Docker Compose](https://docs.docker.com/compose/install/) (v2.10+)
2. Run `docker compose build --no-cache` to build fresh images
3. Run `docker compose up --pull -d --wait` to start the project
4. Open `https://localhost` in your favorite web browser and [accept the auto-generated TLS certificate](https://stackoverflow.com/a/15076602/1352334)
5. Run `docker compose down --remove-orphans` to stop the Docker containers.

## Using Xdebug

The default development image is shipped with [Xdebug](https://xdebug.org/),
a popular debugger and profiler for PHP.

Because it has a significant performance overhead, the step-by-step debugger is disabled by default.
It can be enabled by setting the `XDEBUG_MODE` environment variable to `debug`.

On Linux and Mac:

```
XDEBUG_MODE=coverage,debug docker compose up -d --build
```

## License

Symfony Docker is available under the MIT License.

