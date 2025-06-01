
connect('xavante');

db.users.insertOne({
    _id: ObjectId("64f8b1c2e4b0f8a1c8d9e7a1"),
    username: "xavante-internal-api",
    name: "Internal API User",
    type: "internal",
    secretKey: "xavante-secret-key-2025@Canada",
    createdAt: new Date(),
    updatedAt: new Date(),
    status: "active"
});




