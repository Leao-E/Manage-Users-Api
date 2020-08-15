# Users Core API

<img src="https://github.com/Leao-E/Manage-Users-Api/blob/master/heroimage.svg" align="right" width="450"/>

## What it is?

The idea behind this API is to manage user, systems and the system's hirers.
 
You can create hirers and associate they with your systems and then create and associate users, or you can simply make a register key and let the user make it own register.
You can set an expiration time to the hirer license and can 

## Routes

You can use the following endpoints to manage:

* User Authentication:
    * [POST::api/login](https://github.com/Leao-E/Manage-Users-Api#post-routes)               
    * [POST::api/register](https://github.com/Leao-E/Manage-Users-Api#post-routes)
    * [POST::api/refreshToken](https://github.com/Leao-E/Manage-Users-Api#post-routes)
    * [POST::api/checkToken](https://github.com/Leao-E/Manage-Users-Api#post-routes)
    * [POST::api/logout](https://github.com/Leao-E/Manage-Users-Api#post-routes)

* Manage Users:
    * [GET::api/user/getAll](https://github.com/Leao-E/Manage-Users-Api#get-routes)
    * [GET::api/user/{user_id}/get](https://github.com/Leao-E/Manage-Users-Api#get-routes)
    * [GET::api/user/{user_id}/systems](https://github.com/Leao-E/Manage-Users-Api#get-routes)
    * [GET::api/user/{user_id}/hirers](https://github.com/Leao-E/Manage-Users-Api#get-routes)
    * [POST::api/user/create](https://github.com/Leao-E/Manage-Users-Api#post-routes)  
    * [PUT::api/user/{user_id}/update](https://github.com/Leao-E/Manage-Users-Api#put-routes)
    * [DELETE::api/user/{user_id}/delete](https://github.com/Leao-E/Manage-Users-Api#delete-routes)      

* Manage Systems:
    * [GET::api/system/getAll](https://github.com/Leao-E/Manage-Users-Api#get-routes)
    * [GET::api/system/{system_id}/get](https://github.com/Leao-E/Manage-Users-Api#get-routes)
    * [GET::api/system/{system_id}/hirers](https://github.com/Leao-E/Manage-Users-Api#get-routes)
    * [GET::api/system/{system_id}/users](https://github.com/Leao-E/Manage-Users-Api#get-routes)
    * [POST::api/system/create](https://github.com/Leao-E/Manage-Users-Api#post-routes) 
    * [PUT::api/system/{system_id}/update](https://github.com/Leao-E/Manage-Users-Api#put-routes)
    * [DELETE::api/system/{system_id}/delete](https://github.com/Leao-E/Manage-Users-Api#delete-routes)

* Manage Hirers:
    * [GET::api/hirer/getAll](https://github.com/Leao-E/Manage-Users-Api#get-routes)   
    * [GET::api/hirer/{hirer_id}/get](https://github.com/Leao-E/Manage-Users-Api#get-routes)
    * [GET::api/hirer/{hirer_id}/systems](https://github.com/Leao-E/Manage-Users-Api#get-routes)
    * [GET::api/hirer/{hirer_id}/users](https://github.com/Leao-E/Manage-Users-Api#get-routes)
    * [GET::api/hirer/{hirer_id}/self](https://github.com/Leao-E/Manage-Users-Api#get-routes)        
    * [GET::api/hirer/{hirer_id}/getRegKeys](https://github.com/Leao-E/Manage-Users-Api#get-routes)
    * [GET::api/hirer/{hirer_id}/checkExpire](https://github.com/Leao-E/Manage-Users-Api#get-routes)
    * [POST::api/hirer/create](https://github.com/Leao-E/Manage-Users-Api#post-routes)
    * [POST::api/hirer/createRegKey](https://github.com/Leao-E/Manage-Users-Api#post-routes)         
    * [PUT::api/hirer/{hirer_id}/update](https://github.com/Leao-E/Manage-Users-Api#put-routes)
    * [DELETE::api/hirer/{hirer_id}/delete](https://github.com/Leao-E/Manage-Users-Api#delete-routes)
    * [DELETE::api/hirer/{hirer_id}/deleteRegKey](https://github.com/Leao-E/Manage-Users-Api#delete-routes)   

* Associate Entities
    * [POST::api/associate/UserHirerSystem](https://github.com/Leao-E/Manage-Users-Api#post-routes)
    * [POST::api/associate/HirerSystem](https://github.com/Leao-E/Manage-Users-Api#post-routes)

## API Documentation

### GET Routes
All get routes follow the pattern "api/resource/resource_id/action", where the resource can be "hirer", "system" or "user".

The rosource_id is necessary only if the route needs it.

The action variates from each resource, but in general every resource has the "getAll" and "get" actions.

You can get the relations of each resource changing the action to the resource name, e.g. api/hirer/{hirer_id}/users.
#### Outlier GET Routes
##### api/hirer/{hirer_id}/self
Every hirer is an entity that includes users and systems, so it needs an admin user that is responsible for the
instance. This route returns the hirer instance admin data.  
##### api/hirer/{hirer_id}/getRegKeys
The hirer can provide a register key to user register itself in the Users Core. This route returns all the available 
register keys associated with an specific hirer.  
##### api/hirer/{hirer_id}/checkExpire  
Every register key has an expiration time. This route returns the remaining lifetime of a register key.
### POST Routes

### PUT Routes

### DELETE Routes

