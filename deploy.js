var http = require('http')
var createHandler = require('github-webhook-handler')
var handler = createHandler({ path: '/', secret: '*TCYZa.lao1L8h' })//secret一定要和github上配置的一致，上面的 path 保持和 GitHub 后台设置的一致

function run_cmd(cmd, args, callback) {
  var spawn = require('child_process').spawn;
  var child = spawn(cmd, args);
  var resp = "";

  child.stdout.on('data', function(buffer) { resp += buffer.toString(); });
  child.stdout.on('end', function() { callback (resp) });
}

http.createServer(function (req, res) {
  handler(req, res, function (err) {
    res.statusCode = 404
    res.end('no such location')
  })
}).listen(7777)//这里看到监听的是6666端口，所以在github上配置的url如果是ip+port的形式，那么port也是7777

handler.on('error', function (err) {
  console.error('Error:', err.message)
})

handler.on('push', function (event) {
  console.log('Received a push event for %s to %s',
    event.payload.repository.name,
    event.payload.ref);
  run_cmd('/bin/sh', ['sh /home/Evilmass.github.io/deploy.sh'], function(text){ console.log(text) });//上面那行代码表示执行本文件所在目录下的shell脚本deploy.sh
    })
