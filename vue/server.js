const express = require('express');
const serveStatic = require('serve-static')
const path = require('path')

const app = express();

app.use('/', serveStatic(path.join(__dirname, '/dist')))

const port = process.env.PORT || 8080;

app.listen(port);

//app.get(/.*/, function(req,res){
 // res.sendfile(__dirname + "dist/index.html");
//})

console.log("Server started on port:" + port) 