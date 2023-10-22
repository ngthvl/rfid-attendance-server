import express from 'express';
import { createServer } from 'node:http';

const app = express();
const server = createServer(app);

const apiVer = 'v1';
const baseApi = `ws-api/${apiVer}`;

app.get(`${baseApi}`, (req, res) => {
    res.send('<h1>Hello world</h1>');
});

server.listen(3000, '0.0.0.0', () => {
    console.log('server running at http://localhost:3000');
});
