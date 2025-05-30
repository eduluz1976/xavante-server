
// console.log("\n\n\n\nInitializing MongoDB for Xavante... \n\n\n\n");


connect('xavante');

db.placeholder.insertOne({
    name: "filler",
});


// db.service.createIndex({ name:1, host:1, port:1, status:1 },{unique: true});




