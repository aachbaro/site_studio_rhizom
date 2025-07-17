const bcrypt = require("bcrypt");

const password = ""; // remplace ici par le vrai mdp voulu

bcrypt.hash(password, 10).then((hash) => {
  console.log("Hash bcrypt à mettre dans .env :");
  console.log(hash);
});
