## Overview

This application provides the following API features.

- List All Institutions  
- Search by keyword
- Create a ticket if the search keyword doesn't exist

## Requirements and dependencies

- PHP >= 7.2
- Symfony CLI version  v4.28.1

## Features

- This system allows to search the institutions through the search parameter.
- If the search keyword contains the institutions, it will return the results
- Create a ticket with hitting the API request if the search keyword doesn't exist
- The following assumption implements this solution - 
  - The access token needs to be added in the project config files to run
  - The search is a required field.


## Installation

First, clone the repo:
```bash
$ git clone https://github.com/princelonappan/elucidate.git
```

#### Update Token

Need to update the token in the following section to use the API

- .env file with 'TOKEN' parameter
- 'TOKEN' parameter in the phpunit.xml.dist file

#### Running as a Docker container

The following docker command will run the application.

```
$ cd mytheresa
$ docker-compose up -d
```
This will start the application.

#### Run API Swagger

You can access the Swagger API through the following end point. <br />
```localhost:8000/api/doc```

#### Run Test

- Identify the container id by running '**docker ps**' 
- Run the following command - 
- **docker exec -ti *containerid* bash**
- Run the command **php bin/phpunit**
