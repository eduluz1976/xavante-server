console.log("Creating user {MONGO_INITDB_ROOT_USERNAME} on database {MONGO_INITDB_DATABASE}");

db.createUser(
    {
        user: "{MONGO_INITDB_ROOT_USERNAME}",
        pwd: "{MONGO_INITDB_ROOT_PASSWORD}",
        roles: [ "readWrite", "dbAdmin" ]
    }
);

