module.exports = function basicAuth(req, res, next) {
  const header = req.headers['authorization'];
  if (!header || !header.startsWith('Basic ')) {
    res.set('WWW-Authenticate', 'Basic realm="Admin"');
    return res.status(401).send('Authentication required');
  }
  const base64 = header.split(' ')[1];
  const [user, pass] = Buffer.from(base64, 'base64').toString().split(':');
  if (
    user === process.env.ADMIN_USER &&
    pass === process.env.ADMIN_PASS
  ) {
    return next();
  }
  res.set('WWW-Authenticate', 'Basic realm="Admin"');
  return res.status(401).send('Access denied');
};
