console.log("Creating user {MONGO_INITDB_ROOT_USERNAME} on database {MONGO_INITDB_DATABASE}");

db.createUser(
    {
        user: "{MONGO_INITDB_ROOT_USERNAME}",
        pwd: "{MONGO_INITDB_ROOT_PASSWORD}",
        roles: [ "readWrite", "dbAdmin" ]
    }
);


db.auth("{MONGO_INITDB_ROOT_USERNAME}", "{MONGO_INITDB_ROOT_PASSWORD}");

console.log("Creating system log collection on database {MONGO_INITDB_DATABASE}");
db2 = db.getSiblingDB("{MONGO_INITDB_DATABASE}");
db2.createCollection("system_logs");

console.log("Logging user creation action on system_logs collection");

db2.system_logs.insertOne({
    "user": "{MONGO_INITDB_ROOT_USERNAME}",
    "action": "create",
    "resource": "user",
    "timestamp": new Date()
});