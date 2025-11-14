# Riot Take-Home Technical Challenge

## Table of Contents

- [Overview](#overview)
- [Prerequisites](#prerequisites)
- [Setup](#setup)
- [Running the API](#running-the-api)
- [Running Tests](#running-tests)


## Overview

API implemented in PHP using the Laravel framework. \
Exposes 4 endpoints `/encrypt`, `/decrypt`, `/sign`, `/verify`, as specified in this [repository](https://github.com/tryriot/take-home).

## Prerequisites

- Have PHP installed
- Have `composer` installed, the PHP package manager, via your favorite package manager on your machine or their [download page](https://getcomposer.org/download/).
- Have Docker installed on your machine.

## Setup

#### Step 1
Install project dependencies using the following bash command:

```bash
composer install
```
This will also install `laravel/sail`, the Laravel package that provides us with docker functionalities to run the project.

#### Step 2

You **must** provide the private key used by the HMAC algorithm through the following env variable: `DATA_SIGNATURE_KEY`

You can also configure the hashing algorithm used by HMAC via `DATA_SIGNATURE_HASHING_ALGO` to use any of the ones listed [here](https://www.php.net/manual/en/function.hash-hmac-algos.php). \
If `DATA_SIGNATURE_HASHING_ALGO` is left empty, sha256 will be used by default.

#### Step 3
Once the project dependencies are installed, we can build the docker image for the API using:
```bash
./vendor/bin/sail build
```

## Running the API
You only need to run the following command:
```bash
./vendor/bin/sail up
```
This will launch the API on `http://127.0.0.1:8000`

or

You can run the docker containers in daemon mode:
```bash
./vendor/bin/sail up -d
```
and then stop the containers using:
```bash
./vendor/bin/sail down
```

## Running Tests
To run Unit and Integration tests, make sure the API is running through `sail` and then:
```bash
./vendor/bin/sail test
```
After running the command, you should see the number of passing tests and the number of total assertions

