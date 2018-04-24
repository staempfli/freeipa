"use strict";
const express = require('express');
const bodyParser = require('body-parser');
const app = express();
app.use(bodyParser.urlencoded({ extended: false }));

/* eslint complexity: "off" */
app.get('/ipa/config/ca.crt', function (req, res) {
    res.status(200).end('-----BEGIN CERTIFICATE-----\n' +
        'MIIDnzCCAoegAwIBAgIJALt+1pzIuQv9MA0GCSqGSIb3DQEBCwUAMGYxCzAJBgNV\n' +
        'BAYTAkNIMQ0wCwYDVQQIDARCZXJuMQ0wCwYDVQQHDARCZXJuMRUwEwYDVQQKDAxT\n' +
        'dGFlbXBmbGkgQUcxDDAKBgNVBAsMA09yZzEUMBIGA1UEAwwLZXhhbXBsZS5jb20w\n' +
        'HhcNMTgwNDIwMTA1NzAyWhcNMjAwNDE5MTA1NzAyWjBmMQswCQYDVQQGEwJDSDEN\n' +
        'MAsGA1UECAwEQmVybjENMAsGA1UEBwwEQmVybjEVMBMGA1UECgwMU3RhZW1wZmxp\n' +
        'IEFHMQwwCgYDVQQLDANPcmcxFDASBgNVBAMMC2V4YW1wbGUuY29tMIIBIjANBgkq\n' +
        'hkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA8aIAXmI9M/WAklFl49ye8aSILbqzuL/e\n' +
        'ZcZZsyjmkK+Is2CU4sbomTC1HXoNp8WKaxGYuQQ9+P9jTnyIuprUze0XaObNkSCx\n' +
        '/TPl5Zi6pc0vRmPqkek0K1d2a4YWHo/1ZHf21O+ruaAmerjPQbENgg2et2fukwTO\n' +
        'j5q4pLsCdtEpPVNuEYqBOQworokruVJvA+lqnXNR2p4cjnbAFDDVQ+W3AvcJrBtp\n' +
        '/VtTguqF9jtMobMn5caHKUanM2oRig60QXYn0iyyaEIM7eQhBK+VEA95j8fTm2Dv\n' +
        'UPisoDJDPwtCIEWR67zPmS5/ZkV8Xv5vjZ0mkWhqsaCAeXzMefVkRQIDAQABo1Aw\n' +
        'TjAdBgNVHQ4EFgQUa1McXCTApRQpO74iqvW6c7eCQR8wHwYDVR0jBBgwFoAUa1Mc\n' +
        'XCTApRQpO74iqvW6c7eCQR8wDAYDVR0TBAUwAwEB/zANBgkqhkiG9w0BAQsFAAOC\n' +
        'AQEAqYCaKEjcFSQ5ZnhzJ0n+Wo2SHqdnZ4ZiiXb+w3SzzuwvygRphTwBNTHQyyQD\n' +
        'CkKIIBulsy8+Nsbfe+97RQP5IewYFUEsQSxfsL4hxBqKWNmRydlmICBBN6WF5JHD\n' +
        'X9jiIEnlKYONVy1CIT/Jr22OLIcLgQQdKmAp9csezbq4JbKecl1Ngief0jYHnHXA\n' +
        '9SigU7Puf+WqKWjUkyHFvUiq3WkFtUmudvHndT3lyjmOcvSil5GpEVBdQQ98+duf\n' +
        'PkYGtzk0uRlazYcqQGw3OX8Z+DRZLza8Ta4sF8bnyxE782Fe760uzdlE3ZB8jsgh\n' +
        'wcQdIk1mwSSrtC5Cx0eyPAwZYw==\n' +
        '-----END CERTIFICATE-----');
});

app.post('/ipa/session/login_password', function (req, res) {
    if(req.headers['content-type'] !== 'application/x-www-form-urlencoded') {
        res.status(400).end('Content-Type must be application/x-www-form-urlencoded');
    }
    if(req.body.user === 'test' && req.body.password === 'test') {
        res.end('');
    } else {
        res.status(400).end('Invalid');
    }
});

app.post('/ipa/session/json', function (req, res, next) {
    var data = "";
    req.on('data', function(chunk){ data += chunk});
    req.on('end', function(){
        req.rawBody = data;
        req.jsonBody = JSON.parse(data);
        next();
        if(req.jsonBody.method === 'ping') {
            res.status(200).end('{"result": {"summary": "IPA server version 4.1.4. API version 2.114"}}')
        }
        if(req.jsonBody.method === 'user_find') {
            res.status(200).end('{' +
                '"count":1,' +
                '"messages":[{' +
                '"code":13001,' +
                '"message":"API Version number was not sent, forward compatibility not guaranteed. Assuming server\'s API version, 2.114",' +
                '"name":"VersionMissing",' +
                '"type":"warning"}],' +
                '"result":[' +
                '{"cn":["Marcel Hauri"],' +
                '"displayname":["Marcel Hauri"],' +
                '"dn":"uid=marcel,cn=users,cn=accounts,dc=demo,dc=lo",' +
                '"gecos":["Marcel Hauri"],' +
                '"gidnumber":["110018"],' +
                '"givenname":["Marcel"],' +
                '"has_keytab":true,' +
                '"has_password":true,' +
                '"homedirectory":["\\/rhome\\/marcel"],' +
                '"initials":["MH"],' +
                '"ipasshpubkey":["ssh-rsa ABCDEFGHIJKLMNOPQRSTUVWXYZ"],' +
                '"ipauniqueid":["aaaaaaaa-1111-2222-bbbb-333333333333"],' +
                '"krblastpwdchange":["20180424000000Z"],' +
                '"krbpasswordexpiration":["20190420000000Z"],' +
                '"krbprincipalname":["marcel@demo.lo"],' +
                '"loginshell":["\\/bin\\/bash"],' +
                '"mail":["marcel.hauri@staempfli.com"],' +
                '"memberof_group":["admin","dba"],' +
                '"memberof_hbacrule":["allow_demo"],' +
                '"memberof_role":["Demo"],' +
                '"memberof_sudorule":["demo"],' +
                '"nsaccountlock":false,' +
                '"objectclass":["ipaSshGroupOfPubKeys",' +
                '"ipaobject","mepOriginEntry",' +
                '"person","top",' +
                '"ipasshuser",' +
                '"inetorgperson",' +
                '"organizationalperson",' +
                '"krbticketpolicyaux",' +
                '"krbprincipalaux",' +
                '"inetuser",' +
                '"posixaccount"],' +
                '"sn":["Hauri"],' +
                '"sshpubkeyfp":["00:00:00:00:00:00:00:00:00:00:00:00:00:00:00:00 marcel@demo (ssh-rsa)"],' +
                '"uid":["marcel"],' +
                '"uidnumber":["100000"]}],' +
                '"summary":"1 user matched",' +
                '"truncated":false}');
        }
    });
});

const server = app.listen(8082, function () {
    const host = 'localhost';
    const port = server.address().port;

    console.log('Testing server listening at http://%s:%s', host, port); // eslint-disable-line no-console
});
