const { spawn } = require('child_process');
const nodemon = require('nodemon');

// Configure nodemon
nodemon({
  script: 'server.js',
  ext: 'php,css,js,html',
  watch: ['.'],
  ignore: ['node_modules/**/*'],
});

// Start PHP server
let phpServer;

function startPHPServer() {
  phpServer = spawn('php', ['-S', 'localhost:8000'], {
    stdio: 'inherit'
  });
}

// On restart, kill the previous PHP server and start a new one
nodemon.on('restart', function () {
  console.log('Files changed, restarting PHP server...');
  if (phpServer) {
    phpServer.kill();
  }
  startPHPServer();
});

// Start the initial server
startPHPServer();

// Handle nodemon exit
nodemon.on('quit', function () {
  console.log('Stopping PHP server...');
  if (phpServer) {
    phpServer.kill();
  }
  process.exit();
});
