# Tasks

## API
- Create Workflow (done)
- List all Workflows (done)
- Get a particular Workflow (done)
- Update a Workflow (done)
- Delete a Workflow (done)

- Add a Task to an Workflow (done)

- Add authentication layer (login / logout)
- Add header with user information
- Add Authorization layers (permission to use their own resources)


- Add extra scenarios on API tests
    - inexistent Workflow when retrievening 
    - invalid id


- Start execution of a Workflow 
    - return the JobId


- Get all Jobs 
    - filters:
        - status = {pending, running, paused, done, cancelled}
        - user (owner) 

- Get a particular Job details
- Update Context of a particular Job 
    - change Variables values, which can lead to move to next task

- Update Status of a particular Job 
    - request change status, like pause, resume, restart, or cancel 



## Definitions


- Workflow
    - has Tasks
    - has Transitions


- Task
    - name
    - description
    - type




## Tests

### Authentication



### Workflow

- user trying to get a Workflow from other user
- user should see only his/her Workflows on get/
- user should only be able to delete/update his/her Workflows



1. Create user on DB 
    - authenticate (with an admin user token)
    - submit POST request 
    - get the secret (and annotate), and the client_id

2. Create credentials (X-ACCESS-TOKEN and X-ACCESS-CHECK) 
    - with `secret` and `client_id`, make a PUT request to `/api/v1/auth` 

3. Authenticate as that client
    - make a POST request to `/api/v1/auth` with headers X-ACCESS-TOKEN and X-ACCESS-CHECK
    - get the `Authorization` header from response.

4. Perform other authenticated operations
    - pass the `Authorization: Bearer <token>`



3,4
3,1,2,3,4
