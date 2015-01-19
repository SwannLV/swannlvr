var DEBUG = true
var PORT = process.env.PORT
var HOST = process.env.IP
var INIT_MESSAGES = 5
var http = require('http');
var fs = require('fs');
var url = require("url");
var path = require("path");


var server = http.createServer(function(request, response) {
 
  var uri = url.parse(request.url).pathname
    , filename = path.join(process.cwd(), uri);
  
  path.exists(filename, function(exists) {
    if(!exists) {
      response.writeHead(404, {"Content-Type": "text/plain"});
      response.write("404 Not Found\n");
      response.end();
      return;
    }
 
    if (fs.statSync(filename).isDirectory()) filename += 'Index.html';
 
    fs.readFile(filename, "binary", function(err, file) {
      if(err) {        
        response.writeHead(500, {"Content-Type": "text/plain"});
        response.write(err + "\n");
        response.end();
        return;
      }
 
      response.writeHead(200);
      response.write(file, "binary");
      response.end();
    });
  });
}).listen(PORT)

var io = require('socket.io').listen(server)
io.set ('transports', ['websocket', 'polling'])


io.sockets.on('connection', function(client) {

    if (DEBUG) console.log("New Connection: ", client.id)

    client.emit("init", JSON.stringify(''))

    client.on('msg', function(msg) {
      
        //if (DEBUG) console.log("Message: " + msg)

        var data = JSON.parse(msg);

        client.broadcast.emit('msg', data);
        //client.emit('msg', _data);
    })

    client.on('disconnect', function() {
        if (DEBUG) console.log("Disconnected: ", client.id)
    })
})
