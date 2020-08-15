# Users Core API

<img src="https://github.com/Leao-E/Manage-Users-Api/blob/master/heroimage.svg" align="right" width="100"/>

## About

The idea behind this API is to manage user, systems and the system's hirers. 
You can create hirers and associate they with your systems and then create and associate users, or you can simply make a register key and let the user make it own register.
You can set an expiration time to the hirer license and can 

## Documentation

You can use the following endpoints to manage:

* User Authentication:
    * POST::api/login               
    * POST::api/register
    * POST::api/refreshToken
    * POST::api/checkToken
    * POST::api/logout

* Manage Users:
    * GET::api/user/getAll
    * GET::api/user/{user_id}/get
    * GET::api/user/{user_id}/systems
    * GET::api/user/{user_id}/hirers
    * POST::api/user/create  
    * PUT::api/user/{user_id}/update
    * DELETE::api/user/{user_id}/delete      

* Manage Systems:
    * GET::api/system/getAll
    * GET::api/system/{system_id}/get
    * GET::api/system/{system_id}/hirers
    * GET::api/system/{system_id}/users
    * POST::api/system/create 
    * PUT::api/system/{system_id}/update
    * DELETE::api/system/{system_id}/delete

* Manage Hirers:
    * GET::api/hirer/getAll   
    * GET::api/hirer/{hirer_id}/get
    * GET::api/hirer/{hirer_id}/systems
    * GET::api/hirer/{hirer_id}/users
    * GET::api/hirer/{hirer_id}/self        
    * GET::api/hirer/{hirer_id}/getRegKeys
    * GET::api/hirer/{hirer_id}/checkExpire
    * POST::api/hirer/create
    * POST::api/hirer/createRegKey         
    * PUT::api/hirer/{hirer_id}/update
    * DELETE::api/hirer/{hirer_id}/delete
    * DELETE::api/hirer/{hirer_id}/deleteRegKey   

* Associate Entities
    * POST::api/associate/UserHirerSystem
    * POST::api/associate/HirerSystem
