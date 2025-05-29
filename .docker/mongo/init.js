

db.createUser(
    {
        user: "kdb_user",
        pwd: "Ab123456",
        roles: [ "readWrite", "dbAdmin" ]
    }
);

connect('kdb');

db.service.createIndex({ name:1, host:1, port:1, status:1 },{unique: true});



