# Secure Information Storage REST API

### Project setup

* Add `secure-storage.localhost` to your `/etc/hosts`: `127.0.0.1 secure-storage.localhost`

* Run `make init` to initialize project

* Open in browser: http://secure-storage.localhost:8000/item Should get `Full authentication is required to access this resource.` error, because first you need to make `login` call (see `postman_collection.json` or `SecurityController` for more info).

### Run tests

make tests

* Remove ```<env name="APP_ENV" value="dev"/>``` or set ```value="test"``` in ```phpunit.xml.dist``` file.
* Update ```<env name="DATABASE_URL" value=""/>``` value as per docker database setting in ```phpunit.xml.dist``` file.

### API credentials

* User: john
* Password: maxsecure

### API end-points
* login
    * Method: POST
    * Required: Raw data
        * username: your username 
        * Password: your password    
    * Response
        * Code: 200
        * Content: 
            ```
            {
                "username": "your usernmae",
                "roles": [
                    "ROLE_USER"
                ]
            }
            ```
    * Description: Allow authorization for CRUD operation.     
* logout
    * Method: POST
    * Required: Raw data
        * username: your username 
        * Password: your password    
    * Description: discard the user security to make CRUD operation secure.
* item
    * Method: GET
    * Response
        * Code: 200
        * Content: ```[{item1}, {item2}, ...]```
    * Description: Get All items with json response with respect of authorized user.    
* item
    * Method: POST
    * Required: Form data
        * data: string   
    * Response
        * Success
            * Code: 200
            * Content: ```[]```
        * Error
            * Code: 400
            * Content: ```{ "error": "No data parameter"}```    
    * Description: Create new item and return empty json response. 
* item
    * Method: PUT
    * Required: X-www-form-urlencoded data
        * id: valid item id
        * data: new string   
    * Response
        * Success
            * Code: 200
            * Content: ```[]```
        * Error
            * Code: 400
            * Content: ```{ "error": "No id parameter"} and { "error": "No data parameter"}```    
    * Description: Update item by id and return empty json response. 
* item/{id}
    * Method: DELETE
    * Required: 
        * id: valid item id
    * Response
        * Success
            * Code: 200
            * Content: ```[]```
        * Error
            * Code: 400
            * Content: ```{ "error": "No id parameter"} and { "error": "No item parameter"}```    
    * Description: Delete item by id and return empty json response.
    
* Change: 
PUT method can be handled through ```X-www-form-urlencoded data``` 
```OR``` 
It can be handled with ```_method:PUT in query parameter with POST request```. 

### Postman requests collection

You can import all available API calls to Postman using `postman_collection.json` file
