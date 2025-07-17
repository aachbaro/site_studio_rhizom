FROM node:lts
WORKDIR /app
COPY package*.json ./
RUN npm install --production
COPY . ./
RUN mkdir -p /app/data
RUN mkdir -p /app/public/images
CMD ["node", "server.js"]